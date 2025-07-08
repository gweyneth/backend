<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            margin: 20px;
            color: #333;
        }
        .header-company {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-company img {
            max-width: 80px;
            height: auto;
            margin-bottom: 5px;
        }
        .header-company h3 {
            margin: 0;
            font-size: 16px;
            color: #0056b3;
        }
        .header-company p {
            margin: 0;
            font-size: 9px;
            line-height: 1.2;
        }
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title h2 {
            margin: 0;
            font-size: 18px;
        }
        .filter-info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        .filter-info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #e9e9e9;
        }
        .footer-date {
            text-align: right;
            margin-top: 30px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header-company">
        @if ($perusahaan && $perusahaan->logo)
            <img src="{{ public_path('storage/' . $perusahaan->logo) }}" alt="Logo Perusahaan">
        @endif
        <h3>{{ $perusahaan->nama_perusahaan ?? 'Nama Perusahaan Anda' }}</h3>
        <p>{{ $perusahaan->alamat ?? 'Alamat Perusahaan Anda' }}</p>
        <p>Telp: {{ $perusahaan->no_handphone ?? '-' }} | Email: {{ $perusahaan->email ?? '-' }}</p>
    </div>

    <div class="report-title">
        <h2>LAPORAN PENDAPATAN TRANSAKSI</h2>
    </div>

    <div class="filter-info">
        <p><strong>Filter:</strong></p>
        <p>Tanggal Order: {{ $startDate ?? 'Semua' }} s/d {{ $endDate ?? 'Semua' }}</p>
        <p>Tanggal Bayar: {{ $tanggalBayarStart ?? 'Semua' }} s/d {{ $tanggalBayarEnd ?? 'Semua' }}</p>
        <p>Jenis Pembayaran: {{ ucwords(str_replace('_', ' ', $metodePembayaran ?? 'Semua')) }}</p>
        <p>Bank: {{ \App\Models\Rekening::find($rekeningId)->bank ?? 'Semua' }}</p>
        <p>Pencarian: {{ $searchQuery ?? 'Tidak Ada' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">No. Transaksi</th>
                <th style="width: 15%;">Tgl Order</th>
                <th style="width: 20%;">Nama Pelanggan</th>
                <th style="width: 15%;">Tgl Bayar</th>
                <th style="width: 10%;">Jenis Bayar</th>
                <th style="width: 10%;">Bank</th>
                <th style="width: 10%;">Jumlah Uang Muka</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pendapatanTransaksi as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->no_transaksi }}</td>
                <td>{{ $item->tanggal_order->format('d/m/Y') }}</td>
                <td>{{ $item->pelanggan->nama ?? 'Umum' }}</td>
                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $item->metode_pembayaran ?? '-')) }}</td>
                <td>{{ $item->rekening->bank ?? '-' }}</td>
                <td class="text-right">Rp{{ number_format($item->uang_muka, 2, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data pendapatan sesuai filter.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="7" class="text-right">Total Keseluruhan Uang Muka (Pendapatan):</th>
                <th class="text-right">Rp{{ number_format($totalPendapatan, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer-date">
        <p>{{ $perusahaan->alamat_tanggal ?? 'Kota Anda' }}, {{ now()->format('d F Y') }}</p>
        <br><br>
        <p>Owner</p>
    </div>
</body>
</html>
