<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $gajiKaryawan->karyawan->nama_karyawan ?? 'Karyawan' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" xintegrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            margin: 20px;
            color: #333;
        }
        .container-slip {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header-company {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-company img {
            max-width: 100px;
            height: auto;
            margin-bottom: 10px;
        }
        .header-company h3 {
            margin: 0;
            font-size: 20px;
            color: #0056b3;
        }
        .header-company p {
            margin: 0;
            font-size: 12px;
        }
        .dotted-line {
            border-bottom: 1px dotted #999;
            margin: 20px 0;
        }
        .payslip-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .payslip-title h2 {
            margin: 0;
            font-size: 24px;
        }
        .payslip-title p {
            margin: 5px 0 0;
            font-size: 16px;
        }
        .employee-info table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .employee-info table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .employee-info table td:first-child {
            width: 15%;
            font-weight: bold;
        }
        .salary-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .salary-details th, .salary-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .salary-details th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .salary-details td:last-child {
            text-align: right;
        }
        .summary-terbilang {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .summary-terbilang p {
            margin: 5px 0;
        }
        .signature-section {
            margin-top: 50px;
            text-align: right;
        }
        .signature-section p {
            margin: 0;
        }
        .print-button-container {
            text-align: center;
            margin-top: 30px;
        }
        @media print {
            .print-button-container {
                display: none; /* Sembunyikan tombol cetak saat dicetak */
            }
            body {
                margin: 0;
                padding: 0;
            }
            .container-slip {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-slip">
        <div class="header-company">
            @if ($perusahaan && $perusahaan->logo)
                <img src="{{ asset('storage/' . $perusahaan->logo) }}" alt="Logo Perusahaan">
            @else
                <img src="https://placehold.co/100x100/cccccc/333333?text=Logo" alt="Logo Placeholder">
            @endif
            <h3>{{ $perusahaan->nama_perusahaan ?? 'Nama Perusahaan Anda' }}</h3>
            <p>{{ $perusahaan->alamat ?? 'Alamat Perusahaan Anda' }}</p>
            <p>
                @if ($perusahaan->no_handphone)
                    <i class="fa-solid fa-phone"></i></i> {{ $perusahaan->no_handphone }}
                @endif
                @if ($perusahaan->email)
                    <i class="fas fa-envelope"></i> {{ $perusahaan->email }}
                @endif
                @if ($perusahaan->instagram)
                    <i class="fab fa-instagram"></i> {{ $perusahaan->instagram }}
                @endif
            </p>
        </div>

        <div class="dotted-line"></div>

        <div class="payslip-title">
            <h2>SLIP GAJI KARYAWAN</h2>
            <p>Periode: {{ $gajiKaryawan->created_at->format('F Y') }}</p>
        </div>

        <div class="employee-info">
            <table>
                <tr>
                    <td>NIK</td>
                    <td>: {{ $gajiKaryawan->karyawan->nik ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>: {{ $gajiKaryawan->karyawan->nama_karyawan ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>: {{ $gajiKaryawan->karyawan->jabatan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>: {{ $gajiKaryawan->karyawan->status ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="salary-details">
            <table>
                <tbody>
                    <tr>
                        <td>Gaji Pokok</td>
                        <td>Rp{{ number_format($gajiKaryawan->jumlah_gaji, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>% Bonus</td>
                        <td>{{ number_format($gajiKaryawan->bonus_persen, 0, ',', '.') }} %</td>
                    </tr>
                    <tr>
                        <td>Bonus</td>
                        <td>Rp{{ number_format($gajiKaryawan->jumlah_bonus, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Pinjaman (Kasbon)</td>
                        <td>Rp{{ number_format($gajiKaryawan->kasbon->total ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Sisa Gaji</td>
                        <td>Rp{{ number_format($gajiKaryawan->sisa_gaji, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="summary-terbilang">
            <div class="row">
                <div class="col-6">
                    <p>Penerimaan Bersih: Rp{{ number_format($gajiKaryawan->sisa_gaji, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="signature-section">
            <p>{{ $perusahaan->alamat_tanggal ?? 'Kota Anda' }}, {{ now()->format('d F Y') }}</p>
            <br><br>
            <p>Owner</p>
        </div>
    </div>

    <div class="print-button-container">
        <button class="btn btn-primary" onclick="window.print()">Cetak Slip Ini</button>
        <a href="{{ route('gaji.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <script>
        // Fungsi untuk mengonversi angka menjadi format Rupiah
        function formatRupiah(angka) {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        // Fungsi untuk mengonversi angka menjadi teks (terbilang) dalam Bahasa Indonesia
        function terbilang(angka) {
            var bilangan = String(angka).split('');
            var kalimat = '';
            var angka_str = String(angka);
            var i = 0;
            var j = 0;
            var arr = [
                "", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"
            ];
            var satuan = ["", "ribu", "juta", "miliar", "triliun"];

            // Handle nol
            if (angka == 0) {
                return "nol";
            }

            // Pisahkan angka menjadi blok ribuan
            var groups = [];
            while (angka_str.length > 0) {
                groups.push(angka_str.slice(-3));
                angka_str = angka_str.slice(0, -3);
            }

            for (var k = groups.length - 1; k >= 0; k--) {
                var group = groups[k];
                var s = parseInt(group);

                if (s === 0) continue; // Skip if group is zero

                var temp = '';
                var ratusan = Math.floor(s / 100);
                var puluhan = s % 100;

                if (ratusan > 0) {
                    if (ratusan === 1) {
                        temp += "seratus ";
                    } else {
                        temp += arr[ratusan] + " ratus ";
                    }
                }

                if (puluhan > 0) {
                    if (puluhan < 12) {
                        temp += arr[puluhan] + " ";
                    } else if (puluhan >= 12 && puluhan < 20) {
                        temp += arr[puluhan % 10] + " belas ";
                    } else {
                        temp += arr[Math.floor(puluhan / 10)] + " puluh ";
                        if (puluhan % 10 > 0) {
                            temp += arr[puluhan % 10] + " ";
                        }
                    }
                }

                kalimat += temp.trim() + " " + satuan[k] + " ";
            }

            return kalimat.trim().replace(/\s+/g, ' ') + " Rupiah";
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sisaGajiValue = parseFloat({{ $gajiKaryawan->sisa_gaji }});
            document.getElementById('terbilang-sisa-gaji').innerText = terbilang(sisaGajiValue);
        });
    </script>
</body>
</html>
