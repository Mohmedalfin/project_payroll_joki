<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Gaji Karyawan - {{ $periodeText }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #222; }
        .header { text-align: center; margin-bottom: 20px; }
        .nama-perusahaan { font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .alamat { font-size: 10px; color: #555; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 5px; text-decoration: underline; }
        .sub-info { text-align: center; margin-bottom: 15px; font-size: 11px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { border: 1px solid #333; padding: 8px; background: #e5e7eb; font-weight: bold; text-align: center; font-size: 10px; }
        td { border: 1px solid #333; padding: 6px 8px; vertical-align: middle; }
        
        /* Zebra Striping */
        tbody tr:nth-child(even) { background: #f9fafb; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        
        .footer { margin-top: 40px; display: flex; justify-content: flex-end; }
        .ttd-box { text-align: center; width: 200px; float: right; }
    </style>
</head>
<body>

    <div class="header">
        <div class="nama-perusahaan">Namira Mart</div>
        <div class="alamat">Jl. Palagan Tentara Pelajar Km. 13, Sleman, Yogyakarta</div>
    </div>

    <div class="title">REKAPITULASI GAJI KARYAWAN</div>

    <div class="sub-info">
        Periode: <strong>{{ $periodeText }}</strong> | Dicetak: {{ now()->translatedFormat('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20px;">No</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th style="width: 40px;">Shift</th>
                <th>Gaji Pokok<br><span style="font-weight:normal; font-size:9px">(Jml Shift x Gapok)</span></th>
                <th>Tunjangan<br><span style="font-weight:normal; font-size:9px">(Jml Shift x Tunj)</span></th>
                <th>Bonus</th>
                <th>Potongan</th>
                <th>Total Diterima</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($laporanSemua as $index => $row)
                @php
                    // Hitung nominal berdasarkan kehadiran
                    $totalGapok = $row->gaji_per_shift * $row->shift;
                    $totalTunj  = $row->tunjangan_per_shift * $row->shift;
                    
                    // Tambahkan ke Grand Total Bawah
                    $grandTotal += $row->total;
                @endphp

                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <span class="bold">{{ $row->nama }}</span>
                    </td>
                    <td>{{ $row->jabatan }}</td>
                    <td class="text-center">{{ $row->shift }}</td>
                    
                    {{-- Kolom Angka --}}
                    <td class="text-right">Rp {{ number_format($totalGapok, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalTunj, 0, ',', '.') }}</td>
                    
                    {{-- Bonus --}}
                    <td class="text-right" style="{{ $row->bonus > 0 ? 'color:green; font-weight:bold;' : 'color:#ccc;' }}">
                        {{ number_format($row->bonus, 0, ',', '.') }}
                    </td>
                    
                    {{-- Potongan --}}
                    <td class="text-right" style="{{ $row->potongan > 0 ? 'color:red;' : 'color:#ccc;' }}">
                        {{ number_format($row->potongan, 0, ',', '.') }}
                    </td>
                    
                    {{-- Total --}}
                    <td class="text-right bold" style="background-color: #f3f4f6;">
                        Rp {{ number_format($row->total, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            
            {{-- Baris Grand Total --}}
            <tr style="background-color: #333; color: #fff;">
                <td colspan="8" class="text-right bold" style="border: 1px solid #333; padding: 8px;">TOTAL PENGELUARAN GAJI</td>
                <td class="text-right bold" style="border: 1px solid #333; padding: 8px;">
                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd-box">
            <div>Yogyakarta, {{ now()->translatedFormat('d F Y') }}</div>
            <div style="margin-top: 5px; font-weight:bold;">Manager Keuangan</div>
            <div style="margin-top: 50px; border-bottom: 1px solid #000;"></div>
            <div style="margin-top: 5px;">( _________________ )</div>
        </div>
    </div>

</body>
</html>