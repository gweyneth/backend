<div class="produk-item border-bottom pb-3 mb-3" data-index="{{ $index }}">
    {{-- Hidden input untuk ID produk --}}
    <input type="hidden" name="produk_id[]" class="produk-id">

    {{-- BARIS PERTAMA: Produk, Keterangan, Qty --}}
    <div class="row g-2 align-items-end">
        <div class="col-lg-5">
            @if($index == 0) <label class="form-label">Produk</label> @endif
            <select name="nama_produk[]" class="form-select form-control produk-name">
                <option value="">Pilih Produk</option>
                @foreach ($produks as $produk)
                    <option value="{{ $produk->nama }}"
                            data-id="{{ $produk->id }}"
                            data-ukuran="{{ $produk->ukuran }}"
                            data-satuan="{{ $produk->satuan ?? '' }}"
                            data-harga="{{ $produk->harga_jual }}">
                        {{ $produk->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-5">
            @if($index == 0) <label class="form-label">Keterangan</label> @endif
            <input type="text" name="keterangan[]" class="form-control keterangan" placeholder="Keterangan">
        </div>

        <div class="col-lg-2">
            @if($index == 0) <label class="form-label">Qty</label> @endif
            <input type="number" name="qty[]" class="form-control item-qty" value="1" min="1" placeholder="Qty">
        </div>
    </div>

    {{-- BARIS KEDUA: Ukuran, Harga, Total, dan Tombol Hapus --}}
    <div class="row g-2 mt-1 align-items-end">
        <div class="col-lg-3 col-md-3 col-6">
            @if($index == 0) <label class="form-label">Ukuran</label> @endif
            <input type="text" name="ukuran[]" class="form-control produk-ukuran" readonly placeholder="Ukuran">
        </div>

        <div class="col-lg-4 col-md-4 col-6">
            @if($index == 0) <label class="form-label">Harga Satuan</label> @endif
            <input type="text" class="form-control item-price-display text-end" placeholder="Harga Satuan">
            <input type="hidden" name="harga[]" class="item-price">
        </div>

        <div class="col-lg-4 col-md-4 col-12 mt-2 mt-md-0">
            @if($index == 0) <label class="form-label">Total</label> @endif
            <input type="text" class="form-control item-total-display text-end fw-bold" readonly placeholder="Total">
            <input type="hidden" name="total_item[]" class="item-total">
        </div>

        <div class="col-lg-1 col-md-1 col-12 text-end">
            <button type="button" class="btn btn-danger remove-produk-item w-100 mt-2 mt-md-0">&times;</button>
        </div>
    </div>
</div>