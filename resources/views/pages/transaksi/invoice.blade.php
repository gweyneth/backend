<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Transaksi #{{ $transaksi->no_transaksi }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" xintegrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #eee;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }
        .header-company {
            text-align: left;
        }
        .header-company img {
            max-width: 120px;
            height: auto;
            margin-bottom: 10px;
        }
        .header-company h3 {
            margin: 0;
            font-size: 18px;
            color: #0056b3;
        }
        .header-company p {
            margin: 0;
            font-size: 11px;
            line-height: 1.4;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            font-size: 32px;
            color: #007bff;
            margin: 0;
        }
        .invoice-title p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .client-info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .client-details, .invoice-details {
            width: 48%;
        }
        .client-details h6, .invoice-details h6 {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        .client-details p, .invoice-details p {
            margin: 2px 0;
            font-size: 11px;
        }
        .table-products {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table-products th, .table-products td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        .table-products th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .table-products td:nth-child(4),
        .table-products td:nth-child(5) {
            text-align: right;
        }
        .summary-section {
            width: 100%;
            margin-top: 20px;
        }
        .summary-section table {
            width: 50%; /* Hanya setengah lebar */
            margin-left: auto; /* Pindahkan ke kanan */
            border-collapse: collapse;
        }
        .summary-section td {
            padding: 5px 8px;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        .summary-section td:first-child {
            font-weight: bold;
        }
        .summary-section td:last-child {
            text-align: right;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            font-size: 12px;
        }
        .signature-column {
            width: 45%;
            text-align: center;
        }
        .signature-column p {
            margin: 0;
        }
        .signature-line {
            margin-top: 60px; /* Ruang untuk tanda tangan */
            border-bottom: 1px solid #000;
            width: 150px;
            display: inline-block;
        }
        .print-button-container {
            text-align: center;
            margin-top: 30px;
        }
        @media print {
            .print-button-container {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .invoice-container {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice-container">
        <div class="header-section">
            <div class="header-company">
                @if ($perusahaan && $perusahaan->logo)
                    <img src="{{ asset('storage/' . $perusahaan->logo) }}" alt="Logo Perusahaan">
                @else
                    <img src="https://placehold.co/120x60/cccccc/333333?text=Logo" alt="Logo Placeholder">
                @endif
                <h3>{{ $perusahaan->nama_perusahaan ?? 'Nama Perusahaan Anda' }}</h3>
                <p>{{ $perusahaan->alamat ?? 'Alamat Perusahaan Anda' }}</p>
                <p>Telp: {{ $perusahaan->no_handphone ?? '-' }}</p>
                <p>Email: {{ $perusahaan->email ?? '-' }}</p>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p>No. Invoice: {{ $transaksi->no_transaksi }}</p>
                <p>Tanggal: {{ $transaksi->tanggal_order->format('d F Y') }}</p>
            </div>
        </div>

        <div class="client-info-section">
            <div class="client-details">
                <h6>Kepada:</h6>
                <p><strong>{{ $transaksi->pelanggan->nama ?? 'Pelanggan Umum' }}</strong></p>
                <p>{{ $transaksi->pelanggan->alamat ?? '-' }}</p>
                <p>Telp: {{ $transaksi->pelanggan->no_hp ?? '-' }}</p>
            </div>
            <div class="invoice-details">
                <h6>Detail Order:</h6>
                <p>Tanggal Selesai: {{ $transaksi->tanggal_selesai ? $transaksi->tanggal_selesai->format('d F Y') : '-' }}</p>
                <p>Status Pengerjaan: {{ ucwords(str_replace('_', ' ', $transaksi->status_pengerjaan)) }}</p>
                <p>ID Pelunasan: {{ $transaksi->id_pelunasan ?? '-' }}</p>
            </div>
        </div>

        <table class="table-products">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Produk</th>
                    <th>Keterangan</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->transaksiDetails as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $detail->nama_produk }}
                        @if ($detail->bahan)
                            <br><small>Bahan: {{ $detail->bahan }}</small>
                        @endif
                        @if ($detail->ukuran)
                            <br><small>Ukuran: {{ $detail->ukuran }}</small>
                        @endif
                        @if ($detail->satuan)
                            <br><small>Satuan: {{ $detail->satuan }}</small>
                        @endif
                    </td>
                    <td>{{ $detail->keterangan ?? '-' }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td>Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($detail->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-section">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td>Rp{{ number_format($transaksi->total + $transaksi->diskon, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Diskon</td>
                    <td>Rp{{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Pembayaran</td>
                    <td>Rp{{ number_format($transaksi->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Uang Muka</td>
                    <td>Rp{{ number_format($transaksi->uang_muka, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Sisa Pembayaran</td>
                    <td>Rp{{ number_format($transaksi->sisa, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="signature-section">
            <div class="signature-column">
                <p>{{ $perusahaan->alamat_tanggal ?? 'Kota Anda' }}, {{ now()->format('d F Y') }}</p>
                <p>Diterima Oleh,</p>
                <br><br><br>
                <span class="signature-line"></span>
                <p>(Nama Pelanggan)</p>
            </div>
            <div class="signature-column">
                <p>{{ $perusahaan->alamat_tanggal ?? 'Kota Anda' }}, {{ now()->format('d F Y') }}</p>
                <p>Hormat Kami,</p>
                <br><br><br>
                <span class="signature-line"></span>
                <p>({{ Auth::user()->name ?? 'Kasir/Admin' }})</p>
            </div>
        </div>
    </div>

    <div class="print-button-container">
        <button class="btn btn-primary" onclick="window.print()">Cetak Ulang Invoice</button>
        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali ke Daftar Transaksi</a>
    </div>
</body>
</html>
