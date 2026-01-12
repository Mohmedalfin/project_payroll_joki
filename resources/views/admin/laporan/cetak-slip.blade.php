<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji Karyawan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color:#333; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 4px 0; }
        .text-right { text-align:right; }
        .text-center { text-align:center; }
        .bold { font-weight:bold; }
        .header { margin-bottom:10px; }
        .divider { border-bottom: 1px solid #444; margin:5px 0 10px 0; }
        .table-detail td { padding: 3px 0; }
        .red { color: #cc0000; }
        .green { color: #008800; font-weight:bold; }
        .footer { margin-top: 35px; font-size: 12px; display:flex; justify-content:space-between; }
    </style>
</head>
<body>

    <div class="text-center header">
        <div class="bold" style="font-size:15px;">Namira Mart</div>
        <div style="font-size:11px;">Jl. Palagan Tentara Pelajar Km. 13. Sleman. Yogyakarta</div>
    </div>

    <div class="divider"></div>

    <div class="text-center bold" style="margin-top:5px;">
        SLIP GAJI KARYAWAN
    </div>

    <div class="text-center" style="margin-bottom:10px;">
        Periode: {{ $periodeText }}
    </div>

    <table class="table-detail">
        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{ $karyawan->nama_karyawan }}</td>
            <td>Total Shift</td>
            <td>:</td>
            <td>{{ $shift }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $karyawan->jabatan->nama_jabatan }}</td>
            <td>Lembur (Jam)</td>
            <td>:</td>
            <td>{{ $lembur }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table style="margin-top:10px;">
        <tr class="bold">
            <td>DESKRIPSI</td>
            <td class="text-right">JUMLAH (Rp)</td>
        </tr>

        <tr>
            <td>Gaji Pokok</td>
            <td class="text-right">Rp {{ number_format($gajiPokok, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td>Tunjangan Jabatan</td>
            <td class="text-right">Rp {{ number_format($tunjangan, 0, ',', '.') }}</td>
        </tr>

        @if($lembur > 0)
        <tr>
            <td>Lembur</td>
            <td class="text-right">Rp {{ number_format($lembur * 10000, 0, ',', '.') }}</td>
        </tr>
        @endif

        @if($bonus > 0)
        <tr>
            <td>Bonus</td>
            <td class="text-right">Rp {{ number_format($bonus, 0, ',', '.') }}</td>
        </tr>
        @endif

        @if($potongan > 0)
        <tr class="red">
            <td>Potongan</td>
            <td class="text-right">- Rp {{ number_format($potongan, 0, ',', '.') }}</td>
        </tr>
        @endif

        <tr class="bold green">
            <td>TOTAL GAJI BERSIH</td>
            <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        <div>Diterima oleh,</div>
        <div>Manajemen</div>
    </div>

</body>
</html>
