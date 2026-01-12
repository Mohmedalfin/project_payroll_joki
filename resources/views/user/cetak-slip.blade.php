<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $periodeText }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color:#222; }
        .header { text-align:center; margin-bottom: 20px; }
        .nama-perusahaan { font-size:18px; font-weight:bold; text-transform: uppercase; }
        .alamat { font-size:11px; color:#555; margin-top: 5px; }
        
        .title { font-size:14px; font-weight:bold; margin: 15px 0; text-align:center; text-decoration: underline; }
        
        table { width:100%; border-collapse:collapse; }
        td { padding: 5px; vertical-align: top; }
        
        .info-table td { padding: 2px; }
        .rincian-table { margin-top: 15px; border: 1px solid #ddd; }
        .rincian-table td { border-bottom: 1px solid #eee; padding: 8px; }
        
        .divider { border-bottom:2px solid #000; margin: 10px 0; }
        .label { width:120px; font-weight: bold; color: #444; }
        .text-right { text-align:right; }
        .bold { font-weight:bold; }
        .green { color:#166534; font-weight:bold; } /* Hijau tua */
        .red { color:#991b1b; } /* Merah tua */
        
        .footer { margin-top:40px; width: 100%; }
        .ttd-box { width: 200px; text-align: center; float: right; }
        .ttd-line { border-bottom: 1px solid #000; margin-top: 60px; }
    </style>
</head>
<body>

    {{-- HEADER PERUSAHAAN --}}
    <div class="header">
        <div class="nama-perusahaan">Namira Mart</div>
        <div class="alamat">Jl. Palagan Tentara Pelajar Km. 13, Sleman, Yogyakarta</div>
        <div class="divider"></div>
    </div>

    <div class="title">SLIP GAJI KARYAWAN</div>

    {{-- INFO PERIODE --}}
    <div style="text-align:center; font-size:11px; margin-bottom:15px; color: #555;">
        Periode: <strong>{{ $periodeText }}</strong> | 
        Dicetak: {{ now()->translatedFormat('d F Y') }}
    </div>

    {{-- DATA KARYAWAN --}}
    <table class="info-table">
        <tr>
            <td class="label">Nama Karyawan</td>
            <td width="10">:</td>
            <td>{{ $karyawan->nama_karyawan }}</td>
            
            <td class="label">Kehadiran</td>
            <td width="10">:</td>
            <td>{{ $dataGaji->shift }} Shift</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td>:</td>
            <td>{{ $karyawan->jabatan->nama_jabatan }}</td>
            
            <td class="label">ID Karyawan</td>
            <td>:</td>
            <td>{{ $karyawan->id }}</td>
        </tr>
    </table>

    {{-- HITUNGAN RINCIAN --}}
    @php
        $totalGapok = $dataGaji->gaji_per_shift * $dataGaji->shift;
        $totalTunj  = $dataGaji->tunjangan_per_shift * $dataGaji->shift;
    @endphp

    <table class="rincian-table">
        {{-- HEADERS --}}
        <tr style="background-color: #f9fafb;">
            <td class="bold">KETERANGAN</td>
            <td class="text-right bold">JUMLAH (Rp)</td>
        </tr>

        {{-- PENERIMAAN --}}
        <tr>
            <td>
                Gaji Pokok <br>
                <span style="font-size: 10px; color: #666;">
                    (Rp {{ number_format($dataGaji->gaji_per_shift, 0, ',', '.') }} x {{ $dataGaji->shift }} hari)
                </span>
            </td>
            <td class="text-right">
                Rp {{ number_format($totalGapok, 0, ',', '.') }}
            </td>
        </tr>

        <tr>
            <td>
                Tunjangan Jabatan <br>
                <span style="font-size: 10px; color: #666;">
                    (Rp {{ number_format($dataGaji->tunjangan_per_shift, 0, ',', '.') }} x {{ $dataGaji->shift }} hari)
                </span>
            </td>
            <td class="text-right">
                Rp {{ number_format($totalTunj, 0, ',', '.') }}
            </td>
        </tr>

        @if($dataGaji->bonus > 0)
        <tr>
            <td>Bonus Kinerja / Lainnya</td>
            <td class="text-right">
                Rp {{ number_format($dataGaji->bonus, 0, ',', '.') }}
            </td>
        </tr>
        @endif

        {{-- POTONGAN --}}
        @if($dataGaji->potongan > 0)
        <tr>
            <td class="red">Potongan (Keterlambatan/Lainnya)</td>
            <td class="text-right red">
                - Rp {{ number_format($dataGaji->potongan, 0, ',', '.') }}
            </td>
        </tr>
        @endif

        {{-- TOTAL --}}
        <tr style="background-color: #f0fdf4;">
            <td class="bold" style="font-size: 14px;">TOTAL DITERIMA</td>
            <td class="text-right green" style="font-size: 14px;">
                Rp {{ number_format($dataGaji->total, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div style="margin-top: 10px; font-style: italic; font-size: 10px; color: #666;">
        * Slip gaji ini digenerate otomatis oleh sistem dan sah tanpa tanda tangan basah.
    </div>

    {{-- FOOTER TTD --}}
    <div class="footer">
        <div class="ttd-box">
            <div>Sleman, {{ now()->translatedFormat('d F Y') }}</div>
            <div style="margin-top: 5px;">Manajer Keuangan</div>
            
            <div class="ttd-line"></div>
            <div>( _________________ )</div>
        </div>
    </div>

</body>
</html>