@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Gaji Karyawan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gaji.index') }}">Data Gaji Karyawan</a></li>
            <li class="breadcrumb-item active">Edit Gaji</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Edit Gaji Karyawan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('gaji.update', $gajiKaryawan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="karyawan_id">Nama Pegawai</label>
                        <select name="karyawan_id" id="karyawan_id" class="form-control @error('karyawan_id') is-invalid @enderror" required>
                            <option value="">Pilih Karyawan</option>
                            @foreach ($karyawan as $item)
                                <option value="{{ $item->id }}" data-gaji-pokok="{{ $item->gaji_pokok }}" {{ old('karyawan_id', $gajiKaryawan->karyawan_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_karyawan }} (NIK: {{ $item->nik ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('karyawan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jumlah_gaji">Jumlah Gaji</label>
                        <input type="number" name="jumlah_gaji" id="jumlah_gaji" class="form-control @error('jumlah_gaji') is-invalid @enderror" value="{{ old('jumlah_gaji', $gajiKaryawan->jumlah_gaji) }}" min="0" step="0.01" required>
                        @error('jumlah_gaji')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bonus_persen">Bonus (%)</label>
                        <input type="number" name="bonus_persen" id="bonus_persen" class="form-control @error('bonus_persen') is-invalid @enderror" value="{{ old('bonus_persen', $gajiKaryawan->bonus_persen) }}" min="0" max="100" step="0.01">
                        @error('bonus_persen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jumlah_bonus">Jumlah Bonus</label>
                        <input type="text" name="jumlah_bonus" id="jumlah_bonus" class="form-control" value="{{ old('jumlah_bonus', $gajiKaryawan->jumlah_bonus) }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="status_pembayaran">Status Pembayaran</label>
                        <select name="status_pembayaran" id="status_pembayaran" class="form-control @error('status_pembayaran') is-invalid @enderror" required>
                            <option value="belum dibayar" {{ old('status_pembayaran', $gajiKaryawan->status_pembayaran) == 'belum dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="bayar sebagian" {{ old('status_pembayaran', $gajiKaryawan->status_pembayaran) == 'bayar sebagian' ? 'selected' : '' }}>Bayar Sebagian</option>
                            <option value="bayar setengah" {{ old('status_pembayaran', $gajiKaryawan->status_pembayaran) == 'bayar setengah' ? 'selected' : '' }}>Bayar Setengah</option>
                            <option value="lunas" {{ old('status_pembayaran', $gajiKaryawan->status_pembayaran) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                        @error('status_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="pengeluaran_kasbon_id">Kasbon (Pengeluaran)</label>
                        <select name="pengeluaran_kasbon_id" id="pengeluaran_kasbon_id" class="form-control @error('pengeluaran_kasbon_id') is-invalid @enderror">
                            <option value="">Tidak Ada Kasbon</option>
                            @foreach ($kasbonPengeluaran as $kasbon)
                                <option value="{{ $kasbon->id }}" data-kasbon-amount="{{ $kasbon->total }}" {{ old('pengeluaran_kasbon_id', $gajiKaryawan->pengeluaran_kasbon_id) == $kasbon->id ? 'selected' : '' }}>
                                    {{ $kasbon->keterangan }} (Rp{{ number_format($kasbon->total, 2, ',', '.') }}) - Sisa: Rp{{ number_format($kasbon->sisa_kasbon, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('pengeluaran_kasbon_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sisa_gaji">Sisa Gaji</label>
                        <input type="text" name="sisa_gaji" id="sisa_gaji" class="form-control" value="{{ old('sisa_gaji', $gajiKaryawan->sisa_gaji) }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Gaji</button>
                    <a href="{{ route('gaji.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const karyawanSelect = document.getElementById('karyawan_id');
        const jumlahGajiInput = document.getElementById('jumlah_gaji');
        const bonusPersenInput = document.getElementById('bonus_persen');
        const jumlahBonusInput = document.getElementById('jumlah_bonus');
        const kasbonSelect = document.getElementById('pengeluaran_kasbon_id');
        const sisaGajiInput = document.getElementById('sisa_gaji');

        function formatRupiah(angka) {
            if (angka === null || angka === undefined || isNaN(angka)) {
                return 'Rp 0';
            }
            var reverse = angka.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + ribuan;
        }

        function calculateGaji() {
            const gajiPokok = parseFloat(jumlahGajiInput.value) || 0;
            const bonusPersen = parseFloat(bonusPersenInput.value) || 0;
            const jumlahBonus = gajiPokok * (bonusPersen / 100);
            jumlahBonusInput.value = formatRupiah(jumlahBonus);

            let kasbonAmount = 0;
            const selectedKasbonOption = kasbonSelect.options[kasbonSelect.selectedIndex];
            if (selectedKasbonOption && selectedKasbonOption.dataset.kasbonAmount) {
                kasbonAmount = parseFloat(selectedKasbonOption.dataset.kasbonAmount);
            }

            const sisaGaji = (gajiPokok + jumlahBonus) - kasbonAmount;
            sisaGajiInput.value = formatRupiah(sisaGaji);
        }
        karyawanSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const gajiPokok = selectedOption.dataset.gajiPokok;
            if (gajiPokok) {
                jumlahGajiInput.value = parseFloat(gajiPokok);
            } else {
                jumlahGajiInput.value = 0;
            }
            calculateGaji(); /
        });
        jumlahGajiInput.addEventListener('input', calculateGaji);
        bonusPersenInput.addEventListener('input', calculateGaji);
        kasbonSelect.addEventListener('change', calculateGaji);

       
        calculateGaji();

       
        if (karyawanSelect.value) {
            karyawanSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
