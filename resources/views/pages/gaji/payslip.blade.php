<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $gajiKaryawan->karyawan->nama_karyawan ?? 'Karyawan' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }
        .container-slip {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden; /* Penting untuk watermark */
        }
        .slip-header {
            background-color: #007bff;
            color: #fff;
            padding: 20px 30px;
            display: flex;
            align-items: center;
        }
        .slip-header img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 20px;
            background: #fff;
            padding: 5px;
        }
        .slip-header .company-details h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }
        .slip-header .company-details p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .slip-body {
            padding: 30px;
        }
        .payslip-title {
            text-align: center;
            margin-bottom: 25px;
        }
        .payslip-title h2 {
            font-weight: 700;
            color: #333;
        }
        .payslip-title p {
            color: #6c757d;
        }
        .employee-info {
            margin-bottom: 25px;
        }
        .employee-info table td {
            border: none;
            padding: 4px 0;
        }
        .employee-info td:first-child {
            font-weight: 600;
            width: 120px;
        }
        .salary-details h5 {
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #007bff;
            display: inline-block;
        }
        .salary-details table {
            width: 100%;
        }
        .salary-details td {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .salary-details td:last-child {
            text-align: right;
            font-weight: 500;
        }
        .text-success { color: #28a745 !important; }
        .text-danger { color: #dc3545 !important; }
        .summary-section {
            margin-top: 25px;
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
        }
        .summary-section .total-net {
            font-size: 1.2rem;
            font-weight: 700;
        }
        .summary-section .terbilang {
            font-style: italic;
            color: #6c757d;
        }
        .signature-section {
            margin-top: 50px;
            padding: 0 15px;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 10rem;
            font-weight: 800;
            color: rgba(0, 0, 0, 0.05);
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
            .container-slip { margin: 0; box-shadow: none; border-radius: 0; }
            .print-button-container { display: none; }
        }
    </style>
</head>
<body>
    <div class="container-slip">
        {{-- Watermark Status Pembayaran --}}
        @if($gajiKaryawan->status_pembayaran == 'lunas')
            <div class="watermark">LUNAS</div>
        @else
            <div class="watermark">BELUM LUNAS</div>
        @endif

        <div class="slip-header">
            <img src="{{ $perusahaan && $perusahaan->logo ? asset('storage/' . $perusahaan->logo) : 'https://placehold.co/100x100/ffffff/007bff?text=Logo' }}" alt="Logo Perusahaan">
            <div class="company-details">
                <h3>{{ $perusahaan->nama_perusahaan ?? 'Nama Perusahaan Anda' }}</h3>
                <p>{{ $perusahaan->alamat ?? 'Alamat Perusahaan Anda' }}</p>
            </div>
        </div>

        <div class="slip-body" style="position: relative; z-index: 1;">
            <div class="payslip-title">
                <h2>SLIP GAJI KARYAWAN</h2>
                <p>Periode: {{ $gajiKaryawan->created_at->format('F Y') }}</p>
            </div>

            <div class="employee-info">
                <div class="row">
                    <div class="col-md-6">
                        <table>
                            <tr><td>Nama</td><td>: {{ $gajiKaryawan->karyawan->nama_karyawan ?? 'N/A' }}</td></tr>
                            <tr><td>NIK</td><td>: {{ $gajiKaryawan->karyawan->nik ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table>
                            <tr><td>Jabatan</td><td>: {{ $gajiKaryawan->karyawan->jabatan ?? '-' }}</td></tr>
                            <tr><td>Status</td><td>: {{ $gajiKaryawan->karyawan->status ?? '-' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="salary-details">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-plus-circle text-success mr-2"></i>Pendapatan</h5>
                        <table>
                            <tr>
                                <td>Gaji Pokok</td>
                                <td>Rp{{ number_format($gajiKaryawan->jumlah_gaji, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Bonus ({{ number_format($gajiKaryawan->bonus_persen, 1, ',', '.') }}%)</td>
                                <td>Rp{{ number_format($gajiKaryawan->jumlah_bonus, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="font-weight-bold">
                                <td>Total Pendapatan</td>
                                <td>Rp{{ number_format($gajiKaryawan->jumlah_gaji + $gajiKaryawan->jumlah_bonus, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-minus-circle text-danger mr-2"></i>Potongan</h5>
                        <table>
                            <tr>
                                <td>Pinjaman (Kasbon)</td>
                                <td>Rp{{ number_format($gajiKaryawan->kasbon->total ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="font-weight-bold">
                                <td>Total Potongan</td>
                                <td>Rp{{ number_format($gajiKaryawan->kasbon->total ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="summary-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">Penerimaan Bersih (Take Home Pay)</p>
                        <p class="total-net">Rp{{ number_format($gajiKaryawan->sisa_gaji, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <p class="terbilang mb-0" id="terbilang-sisa-gaji"></p>
                    </div>
                </div>
            </div>

            <div class="signature-section">
                <div class="row">
                    <div class="col-6 text-center">
                        <p>Penerima,</p>
                        <br><br><br>
                        <p><strong>({{ $gajiKaryawan->karyawan->nama_karyawan ?? ' ' }})</strong></p>
                    </div>
                    <div class="col-6 text-center">
                        <p>{{ $perusahaan->alamat_tanggal ?? 'Kota Anda' }}, {{ now()->format('d F Y') }}</p>
                        <p>Hormat Kami,</p>
                        <br><br><br>
                        <p><strong>( Pimpinan )</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="print-button-container">
        <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print mr-2"></i>Cetak Slip Ini</button>
        <a href="{{ route('gaji.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <script>
        function terbilang(n) {
            if (n < 0) return "minus " + terbilang(-n);
            const arr = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
            let temp = "";
            if (n < 12) {
                temp = " " + arr[n];
            } else if (n < 20) {
                temp = terbilang(n - 10) + " belas";
            } else if (n < 100) {
                temp = terbilang(Math.floor(n / 10)) + " puluh" + terbilang(n % 10);
            } else if (n < 200) {
                temp = " seratus" + terbilang(n - 100);
            } else if (n < 1000) {
                temp = terbilang(Math.floor(n / 100)) + " ratus" + terbilang(n % 100);
            } else if (n < 2000) {
                temp = " seribu" + terbilang(n - 1000);
            } else if (n < 1000000) {
                temp = terbilang(Math.floor(n / 1000)) + " ribu" + terbilang(n % 1000);
            } else if (n < 1000000000) {
                temp = terbilang(Math.floor(n / 1000000)) + " juta" + terbilang(n % 1000000);
            } else if (n < 1000000000000) {
                temp = terbilang(Math.floor(n / 1000000000)) + " miliar" + terbilang(n % 1000000000);
            } else if (n < 1000000000000000) {
                temp = terbilang(Math.floor(n / 1000000000000)) + " triliun" + terbilang(n % 1000000000000);
            }
            return temp;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sisaGajiValue = parseFloat({{ $gajiKaryawan->sisa_gaji }});
            const terbilangText = terbilang(sisaGajiValue).replace(/\s+/g, ' ').trim();
            document.getElementById('terbilang-sisa-gaji').innerText = `Terbilang: ${terbilangText.charAt(0).toUpperCase() + terbilangText.slice(1)} Rupiah`;
        });
    </script>
</body>
</html>
