@extends('admin.layout.app')
@section('title', 'Laporan Gaji Karyawan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden w-full">
    
    {{-- Header --}}
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Laporan Gaji</h2>
            <p class="text-sm text-gray-500">Rekap gaji otomatis (Bonus Aktif, Potongan Non-Aktif)</p>
        </div>
        
        <form action="{{ route('admin.laporan.gaji') }}" method="GET" class="flex gap-2">
            <input type="month" name="periode" value="{{ $periode }}" required
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Filter</button>
            
            @if(Route::has('admin.laporan.cetak-semua'))
            <a href="{{ route('admin.laporan.cetak-semua', ['periode' => $periode]) }}" target="_blank"
               class="px-4 py-2 bg-gray-100 text-gray-700 border rounded-lg text-sm">Cetak Semua</a>
            @endif
        </form>
    </div>

    {{-- Info Periode --}}
    <div class="px-6 py-3 bg-blue-50 border-b border-blue-100">
        <p class="text-blue-800 text-sm font-medium">
            Periode: <span class="font-bold">{{ \Carbon\Carbon::parse($periode)->translatedFormat('F Y') }}</span>
        </p>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase font-semibold text-xs border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Karyawan</th>
                    <th class="px-6 py-4">Jabatan</th>
                    <th class="px-6 py-4 text-center">Kehadiran</th>
                    <th class="px-6 py-4">Rincian</th>
                    <th class="px-6 py-4">Lainnya</th>
                    <th class="px-6 py-4">Total Bersih</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($laporan as $row)
                    @php
                        // 1. Ambil Data Dasar
                        $nama      = $row->karyawan->nama_karyawan;
                        $jabatan   = $row->karyawan->jabatan->nama_jabatan;
                        $shift     = $row->shift; 
                        
                        // 2. Hitung Komponen Gaji (Gaji & Tunjangan)
                        $gapok     = $row->gaji_per_shift; 
                        $tunj      = $row->tunjangan_per_shift;
                        
                        $totalGapok = $gapok * $shift;
                        $totalTunj  = $tunj * $shift;
                        
                        // =======================================================
                        // SETTINGAN: BONUS ON, POTONGAN OFF (0)
                        // =======================================================
                        $bonus    = $row->bonus; // <-- Bonus diambil dari data
                        $potongan = 0;           // <-- Potongan dipaksa 0

                        // 3. Hitung Total Real di View
                        // Rumus: (Gaji + Tunjangan) + Bonus - 0
                        $totalReal = ($totalGapok + $totalTunj) + $bonus - $potongan;
                    @endphp

                    <tr class="hover:bg-gray-50 align-top">
                        <td class="px-6 py-4 font-bold text-gray-800">
                            {{ $nama }}
                            <div class="text-xs text-gray-400 font-normal mt-1">ID: {{ $row->karyawan->id }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $jabatan }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                {{ $shift }} Shift
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col text-xs">
                                <span>Gapok: Rp {{ number_format($totalGapok, 0, ',', '.') }}</span>
                                <span class="mt-1 pt-1 border-t">Tunj: Rp {{ number_format($totalTunj, 0, ',', '.') }}</span>
                            </div>
                        </td>
                        
                        {{-- Kolom Lainnya (Bonus & Potongan) --}}
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                {{-- Bonus: Muncul Hijau jika ada --}}
                                <div class="text-xs font-medium {{ $bonus > 0 ? 'text-green-600 font-bold' : 'text-gray-400' }}">
                                    @if($bonus > 0) Bonus: +{{ number_format($bonus, 0, ',', '.') }} @else Bonus: 0 @endif
                                </div>
                                
                                {{-- Potongan: Selalu 0 (Abu-abu) --}}
                                <div class="text-xs font-medium text-gray-400">
                                    Potongan: 0 (Non-Aktif)
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 font-bold text-lg text-green-600">
                            Rp {{ number_format($totalReal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.laporan.cetak', ['karyawan' => $row->karyawan->id, 'periode' => $periode]) }}"
                               target="_blank" class="text-blue-600 hover:text-blue-800 border px-3 py-1 rounded text-xs">
                                PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">
                            Tidak ada data gaji.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection