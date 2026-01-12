<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanUserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 1. Ambil Data dari Database
        $periodeInput = $request->input('periode', date('Y-m'));
        $periodeDb = $periodeInput . '-01';

        $slipsRaw = Gaji::with(['karyawan.jabatan'])
            ->where('karyawan_id', $user->karyawan_id)
            ->where('periode', $periodeDb)
            ->get();

        // 2. TIMPA DATA DATABASE DENGAN HASIL HITUNGAN BARU
        foreach ($slipsRaw as $slip) {
            // Panggil fungsi hitung (yang hasilnya 85.000 tadi)
            $hasil = $this->hitungGajiRuntime($slip);
            
            // Masukkan hasil hitungan ke variabel object slip
            // AGAR VIEW MEMBACA INI, BUKAN DATA DATABASE LAMA
            $slip->rt_hadir    = $hasil['hadir'];
            $slip->rt_total    = $hasil['total']; // Ini isinya 85.000
            $slip->rt_potongan = $hasil['potongan'];
            $slip->rt_rincian  = $hasil['rincian'];
        }

        return view('user.laporan', [
            'slip' => $slipsRaw,
            'currentPeriode' => $periodeInput
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Filter
        $request->validate(['periode' => 'required']); 
        
        $periodeInput = $request->periode; 
        $periodeDb = $periodeInput . '-01'; 
        
        $user = Auth::user();

        // 2. Simpan/Update Record Slip
        Gaji::updateOrCreate(
            [
                'karyawan_id' => $user->karyawan_id,
                'periode' => $periodeDb
            ],
            [
                // Kolom lain opsional
            ]
        );

        // Redirect back to index with the period parameter so the filter persists
        return redirect()->route('user.gaji.index', ['periode' => $periodeInput])
            ->with('success', 'Perhitungan gaji berhasil diperbarui.');
    }

    public function download($id)
    {
        $slip = Gaji::with('karyawan.jabatan')->findOrFail($id);
        
        $hasil = $this->hitungGajiRuntime($slip);
        
        $dataGaji = (object) [
            'shift'               => $hasil['hadir'],
            'gaji_per_shift'      => $slip->karyawan->jabatan->gaji_pokok,
            'tunjangan_per_shift' => $slip->karyawan->jabatan->tunjangan_jabatan,
            'lembur'              => 0,
            'bonus'               => $slip->bonus ?? 0,
            'potongan'            => $hasil['potongan'],
            'total'               => $hasil['total']
        ];

        $karyawan = $slip->karyawan;
        
        $periodeText = \Carbon\Carbon::parse($slip->periode)->translatedFormat('F Y');

        $pdf = Pdf::loadView('user.cetak-slip', compact('karyawan', 'dataGaji', 'periodeText'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream("Slip-Gaji-{$karyawan->nama_karyawan}-{$periodeText}.pdf");
    }

    

    // --- LOGIC PERHITUNGAN REALTIME ---
    private function hitungGajiRuntime($slip)
    {
        $karyawan = $slip->karyawan;
        $jabatan  = $karyawan->jabatan;

        // ... (kode parsing tanggal dan query absensi biarkan saja) ...
        $tglSlip = Carbon::parse($slip->periode);
        $bulan   = $tglSlip->month;
        $tahun   = $tglSlip->year;

        $kehadiran = Kehadiran::where('karyawan_id', $karyawan->id)
            ->whereMonth('tgl_kehadiran', $bulan)
            ->whereYear('tgl_kehadiran', $tahun)
            ->get();

        $jmlHadir   = 0;
        $telatMenit = 0;
        
        // ... (looping kehadiran biarkan saja) ...
        foreach ($kehadiran as $absen) {
             if ($absen->waktu_masuk) {
                $jmlHadir++;
                // ... logic telat biarkan ...
             }
        }

        // --- BAGIAN INI YANG KITA CEK ---
        $totalGapok = $jmlHadir * $jabatan->gaji_pokok;
        $totalTunj  = $jmlHadir * $jabatan->tunjangan_jabatan;
        $potongan   = 0; // Sementara kita nol-kan dulu logic potongan biar ketahuan
        $bonus      = $slip->bonus ?? 0;

        $totalBersih = $totalGapok + $totalTunj + $bonus - $potongan;

        return [
            'hadir'    => $jmlHadir,
            'potongan' => $potongan,
            'total'    => $totalBersih,
            'rincian'  => [
                'gapok_total' => $totalGapok,
                'tunj_total'  => $totalTunj
            ]
        ];
    }
    
}