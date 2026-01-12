@extends('user.layout.app')
@section('title', 'Absensi Karyawan')

@section('content')

@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;

    // 1. Ambil Data Karyawan & Shift
    $user = Auth::user();
    $karyawan = $user->karyawan;
    $shift = $karyawan->shift ?? 1; // Default Shift 1 jika null

    // 2. Tentukan Jadwal Berdasarkan Shift
    if ($shift == 2) {
        // SHIFT 2 (Siang)
        $jadwalMasuk  = '14:00:00';
        $jadwalPulang = '21:00:00'; 
        $namaShift    = 'Shift 2 (Siang)';
    } else {
        // SHIFT 1 (Pagi) - Default
        $jadwalMasuk  = '08:00:00';
        $jadwalPulang = '15:00:00'; 
        $namaShift    = 'Shift 1 (Pagi)';
    }

    // 3. Waktu Sekarang
    $hariIni       = Carbon::today();
    $waktuSekarang = Carbon::now();
    $jamSekarang   = $waktuSekarang->format('H:i:s');

    // 4. Logic Tombol Absensi
    $sudahMasuk  = isset($absenHariIni); 
    $sudahPulang = isset($absenHariIni) && $absenHariIni->waktu_pulang !== null;

    $btnMasukDisabled  = $sudahMasuk; 
    $btnPulangDisabled = (!$sudahMasuk || $sudahPulang || ($jamSekarang < $jadwalPulang));

    $riwayat = $riwayat ?? [];
    $stats   = $stats   ?? ['hadir' => 0, 'terlambat' => 0];
@endphp

<div class="space-y-6">

    {{-- Waktu + tombol --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
            <div class="mb-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ $namaShift }}
                </span>
            </div>
            
            <h3 class="text-gray-500 font-medium mb-2">Waktu Saat Ini</h3>
            <div class="text-4xl font-bold text-gray-800 mb-1" id="digital-clock">00:00:00</div>
            <p class="text-gray-400 text-sm mb-6">{{ Carbon::now()->translatedFormat('l, d F Y') }}</p>

            <form action="{{ route('user.absensi.store') }}" method="POST" class="w-full">
                @csrf
                <div class="flex gap-4 w-full">
                    {{-- Tombol Masuk --}}
                    <button type="submit" name="tipe" value="masuk"
                        class="flex-1 py-3 rounded-lg font-semibold transition transform
                        {{ $btnMasukDisabled
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            : 'bg-blue-600 hover:bg-blue-700 text-white hover:-translate-y-1 shadow-lg shadow-blue-200'
                        }}"
                        {{ $btnMasukDisabled ? 'disabled' : '' }}>
                        Masuk
                    </button>

                    {{-- Tombol Pulang --}}
                    <button type="submit" name="tipe" value="pulang"
                        class="flex-1 py-3 rounded-lg font-semibold transition transform
                        {{ $btnPulangDisabled
                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            : 'bg-red-600 hover:bg-red-700 text-white hover:-translate-y-1 shadow-lg shadow-red-200'
                        }}"
                        {{ $btnPulangDisabled ? 'disabled' : '' }}>
                        Pulang
                    </button>
                </div>
            </form>

            {{-- Info Jadwal --}}
            <p class="text-xs text-gray-400 mt-4">
                Jadwal Masuk: {{ substr($jadwalMasuk, 0, 5) }} WIB |
                Jadwal Pulang: {{ substr($jadwalPulang, 0, 5) }} WIB
            </p>

            @if($sudahMasuk && !$sudahPulang && $btnPulangDisabled)
                <p class="text-xs text-orange-500 mt-1 font-medium">
                    Tombol pulang aktif pukul {{ substr($jadwalPulang, 0, 5) }}
                </p>
            @endif

            {{-- Hint Status Hari Ini --}}
            @if($sudahMasuk)
                @php
                    $jamMasukLog = $absenHariIni->waktu_masuk ?? null;
                    $statusHarian = '-';
                    
                    if ($jamMasukLog) {
                         // VALIDASI STRICT BERDASARKAN SHIFT USER
                        if ($shift == 2) {
                            $target = '14:00:00';
                            $windowStart = '12:00:00';
                        } else {
                            $target = '08:00:00';
                            $windowStart = '06:00:00';
                        }

                        if ($jamMasukLog < $windowStart) {
                             $statusHarian = 'Diluar Jadwal'; // Absen terlalu pagi
                        } else {
                             $statusHarian = ($jamMasukLog <= $target) ? 'Tepat Waktu' : 'Terlambat';
                        }
                    }
                @endphp

                @if($statusHarian == 'Terlambat')
                    <p class="text-xs text-yellow-600 mt-1 font-semibold">Status: Terlambat</p>
                @elseif($statusHarian == 'Tepat Waktu')
                    <p class="text-xs text-green-600 mt-1 font-semibold">Status: Tepat Waktu</p>
                @elseif($statusHarian == 'Diluar Jadwal')
                    <p class="text-xs text-red-500 mt-1 font-semibold">Status: Diluar Jadwal Shift</p>
                @endif
            @endif

        </div>

        {{-- STATISTIK --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Statistik Bulan Ini</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                    <div class="text-green-600 text-xs font-bold uppercase">Hadir</div>
                    <div class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['hadir'] }}</div>
                    <div class="text-xs text-gray-500">Hari</div>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                    <div class="text-yellow-600 text-xs font-bold uppercase">Terlambat</div>
                    <div class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['terlambat'] }}</div>
                    <div class="text-xs text-gray-500">Kali</div>
                </div>
                <div class="p-4 bg-red-50 rounded-lg border border-red-100">
                    <div class="text-red-600 text-xs font-bold uppercase">Alpha</div>
                    <div class="text-2xl font-bold text-gray-800 mt-1">0</div>
                    <div class="text-xs text-gray-500">Hari</div>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="text-blue-600 text-xs font-bold uppercase">Izin/Sakit</div>
                    <div class="text-2xl font-bold text-gray-800 mt-1">0</div>
                    <div class="text-xs text-gray-500">Hari</div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIWAYAT --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Riwayat 7 Hari Terakhir</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Jam Masuk</th>
                        <th class="px-6 py-3">Jam Pulang</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">

                    @forelse($riwayat as $item)
                        @php
                            $jam = $item->waktu_masuk;
                            $status = '-';

                            if ($jam) {
                                $jamObj = Carbon::parse($jam);

                                // --- LOGIC BARU: Validasi Sesuai Shift User ---
                                if ($shift == 2) {
                                    // Rule Shift 2
                                    $target = '14:00:00';
                                    $batasAwal = '12:00:00'; // Hanya boleh absen mulai jam 12 siang
                                    $batasAkhir = '18:00:00';
                                } else {
                                    // Rule Shift 1
                                    $target = '08:00:00';
                                    $batasAwal = '06:00:00';
                                    $batasAkhir = '11:30:00';
                                }

                                // Cek Validasi
                                if ($jamObj->between($batasAwal, $batasAkhir)) {
                                    $status = ($jam <= $target) ? 'Tepat Waktu' : 'Terlambat';
                                } else {
                                    // Jika Shift 2 tapi absen jam 07:45, masuk kesini
                                    $status = 'Diluar Jadwal';
                                }
                            }
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-700">
                                {{ Carbon::parse($item->tgl_kehadiran)->translatedFormat('l, d M Y') }}
                            </td>

                            <td class="px-6 py-3 font-semibold {{ $status === 'Terlambat' ? 'text-red-500' : ($status === 'Tepat Waktu' ? 'text-green-600' : 'text-gray-500') }}">
                                {{ $item->waktu_masuk ?? '-' }}
                            </td>

                            <td class="px-6 py-3 text-gray-600">
                                {{ $item->waktu_keluar ?? '-' }}
                            </td>

                            <td class="px-6 py-3">
                                @if($status == 'Tepat Waktu')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Tepat Waktu</span>
                                @elseif($status == 'Terlambat')
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">Terlambat</span>
                                @elseif($status == 'Diluar Jadwal')
                                    <span class="bg-red-50 text-red-600 px-2 py-1 rounded text-xs font-bold border border-red-100">Salah Jadwal</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold">-</span>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-400 italic">
                                Belum ada riwayat absensi.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
        document.getElementById('digital-clock').innerText = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

@endsection