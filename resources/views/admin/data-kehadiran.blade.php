@extends('admin.layout.app')

@section('title', 'Data Kehadiran')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden w-full">

    {{-- HEADER --}}
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="text-center sm:text-left">
            <h2 class="text-xl font-bold text-gray-800">Log Kehadiran</h2>
            <p class="text-sm text-gray-500">Monitoring absensi harian karyawan</p>
        </div>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('admin.data-kehadiran') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
            <input type="date" name="tanggal"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 w-full sm:w-auto"
                value="{{ $tanggal }}">
            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto">
                Filter
            </button>
        </form>
    </div>

    {{-- MOBILE LIST --}}
    {{-- MOBILE LIST --}}
    <div class="sm:hidden divide-y divide-gray-100">
        @forelse($data as $row)
            @php
                // SAMAKAN LOGIKA DENGAN TABEL DESKTOP
                // Ubah array wrapper menjadi object agar bisa pakai tanda panah (->)
                $itemRaw = $row['item'];
                $item    = (object) $itemRaw; 
                
                // Handle Karyawan & Shift
                $karyawanRaw = $itemRaw['karyawan'] ?? [];
                $karyawan    = (object) $karyawanRaw;
                $shift       = $karyawan->shift ?? '-';

                // Ambil Status & Lembur
                $status = $row['status'];
                $lembur = $row['lembur'];
            @endphp

            <div class="p-4 bg-white space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="font-bold text-gray-800">{{ $karyawan->nama_karyawan ?? '-' }}</div>
                        <div class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($item->tgl_kehadiran)->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                    
                    {{-- Badge Status --}}
                    @if($status == 'Tepat Waktu')
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">Hadir</span>
                    @elseif($status == 'Terlambat')
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-bold">Telat</span>
                    @elseif($status == 'Invalid')
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold">Invalid</span>
                    @else
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold">-</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400 text-xs block">Jam Masuk</span>
                        <span class="font-medium text-green-600">{{ $item->waktu_masuk ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs block">Jam Pulang</span>
                        <span class="font-medium text-gray-700">{{ $item->waktu_pulang ?? '-' }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-2 border-t border-gray-50 mt-2">
                    <div class="text-xs">
                        @if($shift == 1)
                            <span class="text-blue-600 font-semibold">Shift 1 (Pagi)</span>
                        @elseif($shift == 2)
                            <span class="text-purple-600 font-semibold">Shift 2 (Siang)</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                    @if($lembur > 0)
                        <div class="text-xs font-bold text-gray-600">
                            Lembur: {{ $lembur }} Jam
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500 italic">
                Tidak ada data kehadiran.
            </div>
        @endforelse
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden sm:block overflow-x-auto w-full">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Karyawan</th>
                    <th class="px-6 py-4">Shift</th>
                    <th class="px-6 py-4">Jam Masuk</th>
                    <th class="px-6 py-4">Jam Keluar</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-100">
                @forelse($data as $row)
                    @php
                        // --- BAGIAN MAGIC (SOLUSI ANTI ERROR) ---
                        // Kita paksa Array diubah jadi Object.
                        // Jadi mau datanya array atau object, kodingan di bawah AMAN.
                        
                        $itemRaw = $row['item']; 
                        $item    = (object) $itemRaw; // UBAH JADI OBJECT
                        
                        // Handle Karyawan
                        $karyawanRaw = $itemRaw['karyawan'] ?? [];
                        $karyawan    = (object) $karyawanRaw; // UBAH JADI OBJECT

                        // Ambil Status & Lembur
                        $status = $row['status'];
                        $lembur = $row['lembur'];
                        
                        // Ambil Shift (Pastikan aman kalau null)
                        $shift = $karyawan->shift ?? '-'; 
                    @endphp

                    <tr class="hover:bg-gray-50 transition">
                        
                        {{-- TANGGAL --}}
                        <td class="px-6 py-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($item->tgl_kehadiran)->translatedFormat('d M Y') }}
                        </td>

                        {{-- NAMA KARYAWAN --}}
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $karyawan->nama_karyawan ?? '-' }}
                        </td>

                        {{-- SHIFT --}}
                        <td class="px-6 py-4 text-gray-700">
                            @if($shift == 1)
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">Shift 1</span>
                                <div class="text-xs text-gray-500 mt-1">08:00 - 15:00</div>
                            @elseif($shift == 2)
                                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2 py-1 rounded">Shift 2</span>
                                <div class="text-xs text-gray-500 mt-1">14:00 - 21:00</div>
                            @else
                                -
                            @endif
                        </td>

                        {{-- WAKTU MASUK (Sekarang BISA pakai tanda panah -> karena sudah diobject-kan) --}}
                        <td class="px-6 py-4 text-green-600 font-medium">
                            {{ $item->waktu_masuk ?? '-' }}
                        </td>

                        {{-- WAKTU PULANG --}}
                        <td class="px-6 py-4 text-gray-700">
                            {{ $item->waktu_keluar ?? '-' }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4">
                            @if($status == 'Tepat Waktu')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">Tepat Waktu</span>
                            @elseif($status == 'Terlambat')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-medium">Terlambat</span>
                            @elseif($status == 'Invalid')
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">Invalid</span>
                            @else
                                -
                            @endif
                        </td>

                        {{-- LEMBUR --}}
                        {{-- <td class="px-6 py-4 text-gray-700 font-bold">
                            {{ $lembur > 0 ? '+ '.$lembur.' Jam' : '-' }}
                        </td> --}}

                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500 italic">
                            Tidak ada data kehadiran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
