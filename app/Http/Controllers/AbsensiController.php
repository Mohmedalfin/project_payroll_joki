<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        // --- DASHBOARD ADMIN ---
        if ($role === 'admin') {
            $dataAbsensi = Kehadiran::with('karyawan')->latest()->get(); 
            return view('admin.absensi.index', compact('dataAbsensi'));
        }

        // --- DASHBOARD USER ---
        if ($role === 'user') {

            $karyawan   = $user->karyawan;
            $karyawanId = $user->karyawan_id;
            $hariIni    = Carbon::today()->toDateString();
            $bulanIni   = Carbon::now()->month;

            // 1. CEK SHIFT KARYAWAN
            $shift = $karyawan->shift ?? 1; // Default shift 1

            // 2. TENTUKAN JADWAL BERDASARKAN SHIFT
            if ($shift == 2) {
                // Shift Siang
                $jadwalMasuk  = '14:00:00';
                $jadwalPulang = '21:00:00';
            } else {
                // Shift Pagi (Default)
                $jadwalMasuk  = '08:00:00';
                $jadwalPulang = '15:00:00';
            }

            $jamSekarang = Carbon::now()->format('H:i:s');

            // Ambil absensi hari ini
            $absenHariIni = Kehadiran::where('karyawan_id', $karyawanId)
                ->whereDate('tgl_kehadiran', $hariIni)
                ->first();

            $sudahMasuk  = $absenHariIni !== null;
            // Gunakan 'waktu_pulang' sesuai database sebelumnya
            $sudahPulang = $absenHariIni && $absenHariIni->waktu_pulang !== null; 

            $btnMasukDisabled  = $sudahMasuk;
            $btnPulangDisabled = (!$sudahMasuk || $sudahPulang || ($jamSekarang < $jadwalPulang));

            // Riwayat 7 hari terakhir
            $riwayat = Kehadiran::where('karyawan_id', $karyawanId)
                ->latest('tgl_kehadiran')
                ->limit(7)
                ->get();

            // Statistik
            $stats = [
                'hadir' => Kehadiran::where('karyawan_id', $karyawanId)
                    ->whereMonth('tgl_kehadiran', $bulanIni)
                    ->count(),

                // Hitung terlambat berdasarkan jadwal masuk shift user saat ini
                'terlambat' => Kehadiran::where('karyawan_id', $karyawanId)
                    ->whereMonth('tgl_kehadiran', $bulanIni)
                    ->where('waktu_masuk', '>', $jadwalMasuk)
                    ->count(),
            ];

            return view('user.absensi', compact(
                'absenHariIni', 'btnMasukDisabled', 'btnPulangDisabled',
                'jadwalMasuk', 'jadwalPulang', 'riwayat', 'stats'
            ));
        }

        return abort(403, 'Role tidak dikenal');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        if ($role !== 'user') return back();

        $karyawan    = $user->karyawan;
        $hariIni     = Carbon::today()->toDateString();
        $jamSekarang = Carbon::now()->format('H:i:s');

        // 1. CEK SHIFT LAGI (Untuk Validasi)
        $shift = $karyawan->shift ?? 1;

        if ($shift == 2) {
            $jadwalMasuk  = '14:00:00';
            $jadwalPulang = '21:00:00';
        } else {
            $jadwalMasuk  = '08:00:00';
            $jadwalPulang = '15:00:00';
        }

        $cek = Kehadiran::where('karyawan_id', $user->karyawan_id)
                    ->whereDate('tgl_kehadiran', $hariIni)
                    ->first();

        // 2. LOGIKA ABSEN MASUK
        // Perhatikan: $request->tipe (sesuai name di view), bukan action
        if (!$cek && $request->tipe == 'masuk') {

            Kehadiran::create([
                'karyawan_id'   => $user->karyawan_id,
                'tgl_kehadiran' => $hariIni,
                'waktu_masuk'   => $jamSekarang,
            ]);

            return back()->with('success', 'Absen masuk berhasil.');
        }

        // 3. LOGIKA ABSEN PULANG
        if ($cek && $request->tipe == 'pulang') {

            // Cek apakah sudah waktunya pulang?
            if ($jamSekarang < $jadwalPulang) {
                return back()->with('error', 'Belum waktunya pulang! Jadwal pulang Anda: ' . $jadwalPulang);
            }

            $cek->update([
                'waktu_pulang' => $jamSekarang, // Pastikan kolom database 'waktu_pulang'
            ]);

            return back()->with('success', 'Hati-hati di jalan!');
        }

        return back();
    } 
}