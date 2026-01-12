<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. KPI Dasar
        $totalKaryawan = Karyawan::count();
        $hadirHariIni = Kehadiran::whereDate('tgl_kehadiran', $today)->whereNotNull('waktu_masuk')->count();

        // 2. Logic Terlambat (Shift 1 & 2)
        $terlambatHariIni = Kehadiran::whereDate('tgl_kehadiran', $today)
            ->where(function ($query) {
                // Shift 1: Lewat 08:00
                $query->whereBetween('waktu_masuk', ['08:01:00', '12:00:00'])
                // Shift 2: Lewat 14:00
                      ->orWhere(function ($q2) {
                          $q2->where('waktu_masuk', '>', '14:00:00');
                      });
            })
            ->count();
        
        // 3. HITUNG PENGELUARAN REALTIME (FIX LOGIC)
        // Ambil semua absen bulan ini, join ke karyawan & jabatan untuk dapat harga/rate
        $kehadiranBulanIni = Kehadiran::with('karyawan.jabatan')
            ->whereMonth('tgl_kehadiran', $currentMonth)
            ->whereYear('tgl_kehadiran', $currentYear)
            ->whereNotNull('waktu_masuk')
            ->get();

        $totalGajiRealtime = 0;

        foreach ($kehadiranBulanIni as $absen) {
            // Pastikan data karyawan & jabatan ada (untuk hindari error)
            if ($absen->karyawan && $absen->karyawan->jabatan) {
                // Rumus: (Gapok + Tunjangan) per kedatangan
                $ratePerShift = $absen->karyawan->jabatan->gaji_pokok + 
                                $absen->karyawan->jabatan->tunjangan_jabatan;
                
                $totalGajiRealtime += $ratePerShift;
            }
        }

        // 4. Tambahkan Bonus Bulan Ini (Jika ada)
        $totalBonusBulanIni = Gaji::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('bonus');

        // Total Akhir = Gaji Berdasarkan Absen + Bonus
        $totalPengeluaran = $totalGajiRealtime + $totalBonusBulanIni;
        
        // 5. Data untuk Grafik (Update angka terakhir dengan realita)
        $trendValues = [72000000, 73500000, 76000000, 81000000, 85250000]; // Data dummy 5 bulan lalu
        $trendValues[] = $totalPengeluaran; // Masukkan data bulan ini ke urutan terakhir

        $data = [
            'kpiTotalKaryawan' => $totalKaryawan,
            'kpiHadirHariIni' => $hadirHariIni,
            'kpiTerlambatHariIni' => $terlambatHariIni,
            
            // Format Rupiah untuk Tampilan
            'kpiTotalPengeluaranBulanIni' => 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'),
            
            // Data Chart
            'payrollTrendLabels' => ['Sep', 'Okt', 'Nov', 'Des', 'Jan', 'Feb'], // Sesuaikan nama bulan
            'payrollTrendValues' => $trendValues,
            'currentPeriodLabel' => Carbon::now()->subMonths(5)->format('M Y') . ' - ' . Carbon::now()->format('M Y'),
        ];

        return view('admin.dashboard', $data);
    }
}