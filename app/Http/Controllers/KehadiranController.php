<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kehadiran;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function adminIndex(Request $request)
    {
        $tanggal = $request->query('tanggal') ?? date('Y-m-d');

        $query = Kehadiran::with('karyawan')
            ->orderBy('tgl_kehadiran', 'desc');

        if ($request->filled('tanggal')) {
            $query->whereDate('tgl_kehadiran', $tanggal);
        }

        $kehadiran = $query->get();

        // ADD: Proses perhitungan status, terlambat, lembur
        $data = $kehadiran->map(function ($item) {

            $shift = $item->karyawan->shift ?? null;

            // Default
            $status = '-';
            $terlambat = 0;
            $lembur = 0;

            if (!$shift) {
                return [
                    'item' => $item,
                    'status' => $status,
                    'terlambat' => $terlambat,
                    'lembur' => $lembur
                ];
            }

            // SET SHIFT RULE
            if ($shift == 1) {
                $start = Carbon::parse('08:00:00');
                $end   = Carbon::parse('15:00:00');
                $limitMasukAwal = Carbon::parse('07:30:00');
            } else {
                $start = Carbon::parse('14:00:00');
                $end   = Carbon::parse('21:00:00');
                $limitMasukAwal = Carbon::parse('13:30:00');
            }

            // Hitung waktu masuk
            if ($item->waktu_masuk) {

                $masuk = Carbon::parse($item->waktu_masuk);

                // CASE: Absen sebelum jam masuk → tidak dihitung
                if ($masuk < $limitMasukAwal) {
                    $status = 'Invalid';
                }
                // Tepat waktu
                elseif ($masuk <= $start) {
                    $status = 'Tepat Waktu';
                }
                // Masuk sampai jam akhir shift → Terlambat
                elseif ($masuk < $end) {
                    $status = 'Terlambat';
                    $terlambat = $masuk->diffInMinutes($start);
                }
                // Kalau masuk setelah jam shift selesai?
                else {
                    $status = 'Invalid';
                }

                // Hitung lembur
                if ($item->waktu_pulang) {
                    $pulang = Carbon::parse($item->waktu_pulang);

                    if ($pulang > $end) {
                        $lembur = round($pulang->diffInMinutes($end) / 60, 1);
                    }
                }
            }

            return [
                'item' => $item,
                'status' => $status,
                'terlambat' => $terlambat,
                'lembur' => $lembur
            ];
        });

        return view('admin.data-kehadiran', compact('data', 'tanggal'));
    }
}
