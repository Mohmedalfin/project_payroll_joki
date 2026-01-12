@extends('user.layout.app')
@section('title', 'Laporan Gaji Saya')

@section('content')

<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden w-full">

        {{-- HEADER --}}
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Riwayat Gaji</h2>
                <p class="text-sm text-gray-500">Hitungan gaji per shift kehadiran.</p>
            </div>

            {{-- FORM FILTER --}}
            <form action="{{ route('user.gaji.store') }}" method="POST" class="flex items-center gap-2">
                @csrf
                {{-- Input month menghasilkan "YYYY-MM" --}}
                <input type="month" name="periode" required value="{{ $currentPeriode }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <button type="submit" class="whitespace-nowrap px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    Hitung / Refresh
                </button>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4 text-center">Kehadiran</th>
                        <th class="px-6 py-4">Pendapatan</th>
                        <th class="px-6 py-4">Lainnya</th>
                        <th class="px-6 py-4">Total Bersih</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($slip as $item)
                        @php
                            // KUNCI: Ambil dari rt_total (hasil hitungan controller)
                            // JANGAN ambil dari database langsung
                            $hadir     = $item->rt_hadir ?? 0;
                            $total     = $item->rt_total ?? 0; // <--- INI PASTI 85.000
                            $pot       = $item->rt_potongan ?? 0; 
                            $rinc      = $item->rt_rincian ?? ['gapok_total' => 0, 'tunj_total' => 0];
                            
                            $bonus     = $item->bonus ?? 0;
                        @endphp

                    <tr class="hover:bg-gray-50 transition align-top">
                        
                        {{-- Periode --}}
                        <td class="px-6 py-4 font-bold text-gray-800 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->periode)->translatedFormat('F Y') }}
                        </td>

                        {{-- Shift --}}
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 font-bold rounded-full text-xs">
                                {{ $hadir }} Shift
                            </span>
                        </td>

                        {{-- Rincian --}}
                        <td class="px-6 py-4">
                            <div class="space-y-2">
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-gray-400 uppercase">Gaji Pokok</span>
                                    <span class="font-medium text-gray-700">Rp {{ number_format($rinc['gapok_total'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex flex-col border-t border-gray-100 pt-1">
                                    <span class="text-[10px] text-gray-400 uppercase">Tunjangan</span>
                                    <span class="font-medium text-gray-700">Rp {{ number_format($rinc['tunj_total'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Lainnya --}}
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="text-xs font-medium {{ $bonus > 0 ? 'text-green-600 font-bold' : 'text-gray-400' }}">
                                    @if($bonus > 0) Bonus: +{{ number_format($bonus, 0, ',', '.') }} @else Bonus: 0 @endif
                                </div>
                                <div class="text-xs font-medium {{ $pot > 0 ? 'text-red-500 font-bold' : 'text-gray-400' }}">
                                    @if($pot > 0) Potongan: -{{ number_format($pot, 0, ',', '.') }} @else Potongan: 0 @endif
                                </div>
                            </div>
                        </td>

                        {{-- TOTAL BERSIH --}}
                        <td class="px-6 py-4 font-bold text-lg text-green-600">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('user.gaji.download', $item->id) }}" target="_blank"
                            class="text-blue-600 hover:text-blue-800 text-xs border border-blue-200 bg-blue-50 px-3 py-2 rounded-lg">
                                PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic bg-gray-50">
                            Data tidak ditemukan. Klik tombol Hitung.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection