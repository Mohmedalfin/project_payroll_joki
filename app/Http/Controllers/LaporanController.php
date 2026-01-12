<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', date('Y-m'));
        [$tahun, $bulan] = explode('-', $periode);

        $karyawan = Karyawan::with('jabatan')->get();
        $laporan = [];

        foreach ($karyawan as $k) {
            $hasil = $this->hitungGajiRuntime($k, $bulan, $tahun);
            
            $laporan[] = (object)[
                'karyawan' => $k,
                'shift'     => $hasil['hadir'],
                'lembur'    => 0,
                'potongan'  => $hasil['potongan'],
                'bonus'     => $hasil['bonus'], 
                'total'     => $hasil['total'],
                'gaji_per_shift'      => $k->jabatan->gaji_pokok,
                'tunjangan_per_shift' => $k->jabatan->tunjangan_jabatan,
            ];
        }

        return view('admin.laporan', [
            'laporan'   => $laporan,
            'periode'   => $periode,
            'namaBulan' => Carbon::parse($periode)->translatedFormat('F Y'),
        ]);
    }

    public function cetak(Request $request)
    {
        $karyawanId = $request->karyawan;
        $periode    = $request->periode;
        [$tahun, $bulan] = explode('-', $periode);

        $karyawan = Karyawan::with('jabatan')->findOrFail($karyawanId);
        $hasil = $this->hitungGajiRuntime($karyawan, $bulan, $tahun);

        $dataGaji = (object) [
            'shift'               => $hasil['hadir'],
            'gaji_per_shift'      => $karyawan->jabatan->gaji_pokok,
            'tunjangan_per_shift' => $karyawan->jabatan->tunjangan_jabatan,
            'lembur'              => 0,
            'bonus'               => $hasil['bonus'],
            'potongan'            => $hasil['potongan'], 
            'total'               => $hasil['total']
        ];
        
        $periodeText = Carbon::parse($periode)->translatedFormat('F Y');

        $pdf = Pdf::loadView('user.cetak-slip', compact('karyawan', 'dataGaji', 'periodeText'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream("Slip-Gaji-{$karyawan->nama_karyawan}-{$periodeText}.pdf");
    }

    public function cetakSemua(Request $request)
    {
        $periode = $request->periode;
        [$tahun, $bulan] = explode('-', $periode);
        
        $karyawanAll = Karyawan::with('jabatan')->get();
        $laporanSemua = [];
        
        foreach($karyawanAll as $karyawan) {
            $hasil = $this->hitungGajiRuntime($karyawan, $bulan, $tahun);
            
            $laporanSemua[] = (object) [
                'nama'                => $karyawan->nama_karyawan,
                'jabatan'             => $karyawan->jabatan->nama_jabatan,
                'shift'               => $hasil['hadir'],
                
                'gaji_per_shift'      => $karyawan->jabatan->gaji_pokok,
                'tunjangan_per_shift' => $karyawan->jabatan->tunjangan_jabatan,
                
                'bonus'               => $hasil['bonus'],
                'potongan'            => $hasil['potongan'], 
                'total'               => $hasil['total']
            ];
        }
        
        $periodeText = Carbon::parse($periode)->translatedFormat('F Y');
        
        // Load View Table
        $pdf = Pdf::loadView('admin.laporan.cetak-semua', compact('laporanSemua', 'periodeText'))
            ->setPaper('A4', 'landscape'); // Landscape agar tabel muat
            
        return $pdf->stream("Laporan-Gaji-{$periodeText}.pdf");
    }
    
    

    // --- LOGIC PERHITUNGAN ---
    private function hitungGajiRuntime($karyawan, $bulan, $tahun)
    {
        $jabatan = $karyawan->jabatan;

        // 1. Hitung Absensi
        $kehadiran = Kehadiran::where('karyawan_id', $karyawan->id)
            ->whereMonth('tgl_kehadiran', $bulan)
            ->whereYear('tgl_kehadiran', $tahun)
            ->get();

        $jmlHadir   = 0;
        $telatMenit = 0;

        foreach ($kehadiran as $absen) {
            if ($absen->waktu_masuk) {
                $jmlHadir++;
                $jadwalMasuk = ($karyawan->shift == 2) ? '14:00:00' : '08:00:00';
                $jamMasuk  = Carbon::parse($absen->tgl_kehadiran . ' ' . $absen->waktu_masuk);
                $jamJadwal = Carbon::parse($absen->tgl_kehadiran . ' ' . $jadwalMasuk);

                if ($jamMasuk->gt($jamJadwal)) {
                    $telatMenit += $jamMasuk->diffInMinutes($jamJadwal);
                }
            }
        }

        $gajiDb = Gaji::where('karyawan_id', $karyawan->id)
            ->whereYear('periode', $tahun)  // Cari tahun 2026
            ->whereMonth('periode', $bulan) // Cari bulan 01
            ->first();
            
        $bonus = $gajiDb ? $gajiDb->bonus : 0;

        $potongan   = ceil($telatMenit / 10) * $jabatan->potongan_terlambat;
        $totalGapok = $jmlHadir * $jabatan->gaji_pokok;
        $totalTunj  = $jmlHadir * $jabatan->tunjangan_jabatan;
        
        $totalBersih = $totalGapok + $totalTunj + $bonus - $potongan;

        return [
            'hadir'    => $jmlHadir,
            'potongan' => $potongan,
            'bonus'    => $bonus, 
            'total'    => $totalBersih
        ];
    }
}