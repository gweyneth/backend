<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaksi->no_transaksi }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            font-size: 14px;
            color: #495057;
        }
        .invoice-container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        .invoice-header {
            padding: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .invoice-header .company-logo img {
            max-width: 150px;
        }
        .invoice-header .invoice-title h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #007bff;
            margin: 0;
        }
        .invoice-header .invoice-title p {
            margin: 0;
            text-align: right;
        }
        .invoice-details {
            padding: 40px;
            padding-top: 0;
            display: flex;
            justify-content: space-between;
        }
        .invoice-details h6 {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 10px;
        }
        .invoice-details p {
            margin-bottom: 3px;
            font-size: 13px;
        }
        .invoice-table-container {
            padding: 0 40px;
            
        }
        .table-products {
            font-size: 13px;
        }
        .table-products thead {
            background-color: #f8f9fa;
            color: #343a40;
            font-weight: 600;
        }
        .table-products th, .table-products td {
            vertical-align: middle !important;
        }
        .summary-section {
            padding: 40px;
            display: flex;
            justify-content: flex-end;
        }
        .summary-table {
            width: 50%;
        }
        .summary-table td {
            padding: 8px;
        }
        .summary-table .total-row td {
            font-weight: 700;
            font-size: 1.1rem;
            border-top: 2px solid #343a40;
        }
        .notes-section {
            padding: 0 40px 40px 40px;
        }
        .notes-section p {
            font-style: italic;
            font-size: 12px;
            color: #6c757d;
        }
        .signature-section {
            padding: 40px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #343a40;
            margin-top: 60px;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 8rem;
            font-weight: 800;
            color: rgba(0, 0, 0, 0.04);
            z-index: 0;
            pointer-events: none;
            text-transform: uppercase;
        }
        .print-button-container {
            text-align: center;
            margin: 20px auto 40px;
        }
        @media print {
            body { background-color: #fff; }
            .invoice-container { margin: 0; box-shadow: none; border-radius: 0; }
            .print-button-container { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice-container container-fluid" style="margin:40px auto">
        @if($transaksi->sisa <= 0)
            <div class="watermark" style="color: rgba(40, 167, 69, 0.1);">LUNAS</div>
        @else
            <div class="watermark" style="color: rgba(220, 53, 69, 0.08);">BELUM LUNAS</div>
        @endif
        <div class="invoice-header">
            <div class="company-logo">
                @if ($perusahaan && $perusahaan->logo)
                    <img src="{{ asset('storage/' . $perusahaan->logo) }}" alt="Logo Perusahaan">
                @endif
                {{-- <h5 class="mt-2">{{ $perusahaan->nama_perusahaan ?? 'Nama Perusahaan' }}</h5> --}}
                <p class="mb-0">{{ $perusahaan->alamat ?? 'Alamat Perusahaan' }}</p>
                <p class="mb-0">Telp: {{ $perusahaan->no_handphone ?? '-' }}</p>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p><strong>No. Invoice:</strong> {{ $transaksi->no_transaksi }}</p>
                <p><strong>Tanggal:</strong> {{ $transaksi->tanggal_order->format('d F Y') }}</p>
            </div>
        </div>

        <div class="invoice-details">
            <div class="bill-to">
                <h6>Ditagihkan Kepada:</h6>
                <p><strong>{{ $transaksi->pelanggan->nama ?? 'Pelanggan Umum' }}</strong></p>
                <p>{{ $transaksi->pelanggan->alamat ?? '-' }}</p>
                <p>Telp: {{ $transaksi->pelanggan->no_hp ?? '-' }}</p>
            </div>
            <div class="ship-to text-right">
                <h6>Status Order:</h6>
                <p><strong>{{ ucwords(str_replace('_', ' ', $transaksi->status_pengerjaan)) }}</strong></p>
                <p>Tanggal Selesai: {{ $transaksi->tanggal_selesai ? $transaksi->tanggal_selesai->format('d F Y') : '-' }}</p>
            </div>
        </div>

        <div class="invoice-table-container">
            <table class="table table-products" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produk / Layanan</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi->transaksiDetails as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $detail->nama_produk }}</strong>
                            @if($detail->keterangan)<small class="d-block text-muted">{{ $detail->keterangan }}</small>@endif
                        </td>
                        <td class="text-center">{{ $detail->qty }} {{ $detail->satuan }}</td>
                        <td class="text-right">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-right">Rp{{ number_format($detail->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">Rp{{ number_format($transaksi->total + $transaksi->diskon, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Diskon</td>
                    <td class="text-right">Rp{{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total</td>
                    <td class="text-right">Rp{{ number_format($transaksi->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Uang Muka/Bayar</td>
                    <td class="text-right">Rp{{ number_format($transaksi->uang_muka, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td>Sisa Tagihan</td>
                    <td class="text-right">Rp{{ number_format($transaksi->sisa, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="notes-section">
            <p id="terbilang-text" class="font-weight-bold"></p>
            <p>Catatan: Pembayaran dapat dilakukan melalui transfer ke rekening perusahaan.</p>
        </div>

        <div class="signature-section">
            <div class="signature-column">
                <p>Hormat Kami,</p>
                <div class="signature-line"></div>
                <p><strong>({{ Auth::user()->username ?? 'Kasir/Admin' }})</strong></p>
            </div>
            <div class="signature-column">
                <p>Diterima Oleh,</p>
                <div class="signature-line"></div>
                <p><strong>({{ $transaksi->pelanggan->nama ?? 'Pelanggan' }})</strong></p>
            </div>
        </div>
    </div>

    <div class="print-button-container">
        <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print mr-2"></i>Cetak Ulang Invoice</button>
        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <script>
        // function terbilang(n) {
        //     if (n < 0) return "minus " + terbilang(-n);
        //     const arr = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        //     let temp = "";
        //     if (n < 12) {
        //         temp = " " + arr[n];
        //     } else if (n < 20) {
        //         temp = terbilang(n - 10) + " belas";
        //     } else if (n < 100) {
        //         temp = terbilang(Math.floor(n / 10)) + " puluh" + terbilang(n % 10);
        //     } else if (n < 200) {
        //         temp = " seratus" + terbilang(n - 100);
        //     } else if (n < 1000) {
        //         temp = terbilang(Math.floor(n / 100)) + " ratus" + terbilang(n % 100);
        //     } else if (n < 2000) {
        //         temp = " seribu" + terbilang(n - 1000);
        //     } else if (n < 1000000) {
        //         temp = terbilang(Math.floor(n / 1000)) + " ribu" + terbilang(n % 1000);
        //     } else if (n < 1000000000) {
        //         temp = terbilang(Math.floor(n / 1000000)) + " juta" + terbilang(n % 1000000);
        //     } else if (n < 1000000000000) {
        //         temp = terbilang(Math.floor(n / 1000000000)) + " miliar" + terbilang(n % 1000000000);
        //     } else if (n < 1000000000000000) {
        //         temp = terbilang(Math.floor(n / 1000000000000)) + " triliun" + terbilang(n % 1000000000000);
        //     }
        //     return temp;
        // }

        document.addEventListener('DOMContentLoaded', function() {
            const totalValue = parseFloat({{ $transaksi->total }});
            const terbilangElement = document.getElementById('terbilang-text');
            if (terbilangElement) {
                const terbilangText = terbilang(totalValue).replace(/\s+/g, ' ').trim();
                terbilangElement.innerText = `Terbilang: ${terbilangText.charAt(0).toUpperCase() + terbilangText.slice(1)} Rupiah`;
            }
        });
    </scriptA>
</body>
</html>
