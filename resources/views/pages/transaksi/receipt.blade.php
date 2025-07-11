<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #{{ $transaksi->no_transaksi }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" xintegrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 15px;
            color: #333;
        }
        .receipt-container {
            max-width: 300px; /* Ukuran struk kasir standar */
            margin: 0 auto;
            border: 1px solid #eee; /* Border tipis, bisa dihilangkan saat print */
            padding: 15px;
            box-shadow: 0 0 5px rgba(0,0,0,0.05); /* Bayangan tipis, bisa dihilangkan saat print */
        }
        .header-company {
            text-align: center;
            margin-bottom: 15px;
        }
        .header-company img {
            max-width: 80px;
            height: auto;
            margin-bottom: 5px;
        }
        .header-company h5 {
            margin: 0;
            font-size: 16px;
            color: #000;
        }
        .header-company p {
            margin: 0;
            font-size: 10px;
        }
        .divider {
            border-bottom: 1px dashed #999;
            margin: 10px 0;
        }
        .transaction-info p {
            margin: 2px 0;
            font-size: 11px;
        }
        .table-products {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .table-products th, .table-products td {
            padding: 3px 0;
            text-align: left;
            vertical-align: top;
            font-size: 11px;
        }
        .table-products th {
            border-bottom: 1px dashed #999;
        }
        /* Mengatur perataan teks dan menambahkan padding kanan untuk kolom Qty, Harga, dan Total */
        .table-products td:nth-child(2), /* Qty */
        .table-products td:nth-child(3), /* Harga */
        .table-products td:nth-child(4) { /* Total */
            text-align: right;
            padding-right: 5px; /* Menambahkan jarak di sebelah kanan */
        }
        .table-products tfoot td {
            border-top: 1px dashed #999;
            font-weight: bold;
        }
        .footer-text {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }
        .print-button-container {
            text-align: center;
            margin-top: 20px;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .receipt-container {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: none; /* Biarkan browser yang mengatur lebar saat mencetak */
            }
            .print-button-container {
                display: none; /* Sembunyikan tombol cetak saat dicetak */
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header-company">
            @if ($perusahaan && $perusahaan->logo)
                <img src="{{ asset('storage/' . $perusahaan->logo) }}" alt="Logo Perusahaan">
            @else
                <img src="https://placehold.co/80x80/cccccc/333333?text=Logo" alt="Logo Placeholder">
            @endif
            <h5>{{ $perusahaan->nama_perusahaan ?? 'Nama Perusahaan Anda' }}</h5>
            <p>{{ $perusahaan->alamat ?? 'Alamat Perusahaan Anda' }}</p>
            <p>
                @if ($perusahaan->no_handphone)
                    Telp: {{ $perusahaan->no_handphone }}
                @endif
            </p>
        </div>

        <div class="divider"></div>

        <div class="transaction-info">
            <p>No. Transaksi: {{ $transaksi->no_transaksi }}</p>
            <p>Tanggal: {{ $transaksi->tanggal_order->format('d/m/Y H:i') }}</p>
            <p>Pelanggan: {{ $transaksi->pelanggan->nama ?? 'Umum' }}</p>
            <p>Kasir: {{ Auth::user()->username ?? 'N/A' }}</p> {{-- Menampilkan nama kasir yang sedang login --}}
        </div>

        <div class="divider"></div>

        <table class="table-products">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->transaksiDetails as $detail)
                <tr>
                    <td>
                        {{ $detail->nama_produk }}
                        @if ($detail->keterangan)
                            <br><small>({{ $detail->keterangan }})</small>
                        @endif
                    </td>
                    <td>{{ $detail->qty }} {{ $detail->satuan }}</td>
                    <td>{{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td>{{ number_format($detail->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Subtotal</td>
                    <td>Rp{{ number_format($transaksi->total + $transaksi->diskon, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3">Diskon</td>
                    <td>Rp{{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3">Total Bayar</td>
                    <td>Rp{{ number_format($transaksi->total, 0, ',', '.') }}</td>
                </tr>
                {{-- Baris Uang Muka dihapus --}}
                <tr>
                    <td colspan="3">Sisa Pembayaran</td>
                    <td>Rp{{ number_format($transaksi->sisa, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="divider"></div>

        <div class="footer-text">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
        </div>
    </div>

    <div class="print-button-container">
        <button class="btn btn-primary" onclick="window.print()">Cetak Struk Ini</button>
        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali ke Log Transaksi</a>
    </div>
</body>
</html>
