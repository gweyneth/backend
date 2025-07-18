@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-plus-circle mr-2"></i>Tambah Gaji Karyawan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gaji.index') }}">Data Gaji Karyawan</a></li>
            <li class="breadcrumb-item active">Tambah Gaji</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('gaji.store') }}" method="POST">
            @csrf
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        {{-- Kolom Kiri: Informasi Gaji --}}
                        <div class="col-md-6">
                            <h4>Informasi Karyawan & Gaji</h4>
                            <hr>
                            <div class="form-group">
                                <label for="karyawan_id">Nama Pegawai</label>
                                <select name="karyawan_id" id="karyawan_id" class="form-control select2 @error('karyawan_id') is-invalid @enderror" required>
                                    <option value="">Pilih Karyawan</option>
                                    @foreach ($karyawan as $item)
                                        <option value="{{ $item->id }}" data-gaji-pokok="{{ $item->gaji_pokok }}" {{ old('karyawan_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_karyawan }} (NIK: {{ $item->nik ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('karyawan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="jumlah_gaji">Jumlah Gaji Pokok</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="number" name="jumlah_gaji" id="jumlah_gaji" class="form-control @error('jumlah_gaji') is-invalid @enderror" value="{{ old('jumlah_gaji', 0) }}" min="0" required>
                                    @error('jumlah_gaji')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bonus_persen">Bonus (%)</label>
                                <div class="input-group">
                                    <input type="number" name="bonus_persen" id="bonus_persen" class="form-control @error('bonus_persen') is-invalid @enderror" value="{{ old('bonus_persen', 0) }}" min="0" max="100" step="0.01" placeholder="Contoh: 10 untuk 10%">
                                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                                    @error('bonus_persen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Potongan & Rincian --}}
                        <div class="col-md-6">
                            <h4>Potongan & Rincian Akhir</h4>
                            <hr>
                            <div class="form-group">
                                <label for="pengeluaran_kasbon_id">Kasbon (Pengeluaran)</label>
                                <select name="pengeluaran_kasbon_id" id="pengeluaran_kasbon_id" class="form-control select2 @error('pengeluaran_kasbon_id') is-invalid @enderror">
                                    <option value="">Tidak Ada Kasbon</option>
                                    @foreach ($kasbonPengeluaran as $kasbon)
                                        <option value="{{ $kasbon->id }}" data-kasbon-amount="{{ $kasbon->total }}" {{ old('pengeluaran_kasbon_id') == $kasbon->id ? 'selected' : '' }}>
                                            {{ $kasbon->keterangan }} (Rp{{ number_format($kasbon->total, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('pengeluaran_kasbon_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="jumlah_bonus">Jumlah Bonus (Otomatis)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="text" id="jumlah_bonus_display" class="form-control" value="0" readonly>
                                    <input type="hidden" name="jumlah_bonus" id="jumlah_bonus" value="{{ old('jumlah_bonus', 0) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sisa_gaji">Sisa Gaji Diterima (Otomatis)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="text" id="sisa_gaji_display" class="form-control font-weight-bold" value="0" readonly>
                                    <input type="hidden" name="sisa_gaji" id="sisa_gaji" value="{{ old('sisa_gaji', 0) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status_pembayaran">Status Pembayaran</label>
                                <select name="status_pembayaran" id="status_pembayaran" class="form-control @error('status_pembayaran') is-invalid @enderror" required>
                                    <option value="belum_lunas" {{ old('status_pembayaran') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                    {{-- <option value="dibayar_sebagian" {{ old('status_pembayaran') == 'sebagian' ? 'selected' : '' }}>Dibayar Sebagian</option> --}}
                                    <option value="lunas" {{ old('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                </select>
                                @error('status_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('gaji.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Gaji</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    const karyawanSelect = document.getElementById('karyawan_id');
    const jumlahGajiInput = document.getElementById('jumlah_gaji');
    const bonusPersenInput = document.getElementById('bonus_persen');
    const jumlahBonusInput = document.getElementById('jumlah_bonus');
    const jumlahBonusDisplay = document.getElementById('jumlah_bonus_display');
    const kasbonSelect = document.getElementById('pengeluaran_kasbon_id');
    const sisaGajiInput = document.getElementById('sisa_gaji');
    const sisaGajiDisplay = document.getElementById('sisa_gaji_display');

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function calculateGaji() {
        const gajiPokok = parseFloat(jumlahGajiInput.value) || 0;
        const bonusPersen = parseFloat(bonusPersenInput.value) || 0;
        
        const jumlahBonus = gajiPokok * (bonusPersen / 100);
        jumlahBonusDisplay.value = formatRupiah(jumlahBonus);
        jumlahBonusInput.value = jumlahBonus;

        let kasbonAmount = 0;
        const selectedKasbonOption = kasbonSelect.options[kasbonSelect.selectedIndex];
        if (selectedKasbonOption && selectedKasbonOption.dataset.kasbonAmount) {
            kasbonAmount = parseFloat(selectedKasbonOption.dataset.kasbonAmount);
        }

        const sisaGaji = (gajiPokok + jumlahBonus) - kasbonAmount;
        sisaGajiDisplay.value = formatRupiah(sisaGaji);
        sisaGajiInput.value = sisaGaji;
    }

    // Event listener untuk select karyawan
    $('#karyawan_id').on('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const gajiPokok = selectedOption.dataset.gajiPokok;
        jumlahGajiInput.value = gajiPokok ? parseFloat(gajiPokok) : 0;
        calculateGaji();
    });
    
    // Event listener untuk input lainnya
    jumlahGajiInput.addEventListener('input', calculateGaji);
    bonusPersenInput.addEventListener('input', calculateGaji);
    $('#pengeluaran_kasbon_id').on('change', calculateGaji);

    // Panggil kalkulasi saat halaman dimuat untuk mengisi nilai awal
    calculateGaji();
});
</script>
@endpush
