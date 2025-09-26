<div class="row g-2 mb-3 align-items-center produk-item border-bottom pb-3" data-index="{{ $index }}">
    <input type="hidden" name="produk_id[]" class="produk-id" value="">
    
    <div class="col-md-3">
        @if($index == 0) <label class="form-label">Produk</label> @endif
        <select name="nama_produk[]" class="form-select produk-name">
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
    <div class="col-md-3">
        @if($index == 0) <label class="form-label">Keterangan</label> @endif
        <input type="text" name="keterangan[]" class="form-control keterangan" placeholder="Keterangan">
    </div>
    <div class="col-auto">
        @if($index == 0) <label class="form-label">Qty</label> @endif
        <input type="number" name="qty[]" class="form-control item-qty" value="1" min="1" placeholder="Qty" style="width: 70px;">
    </div>
    <div class="col-auto">
        @if($index == 0) <label class="form-label">Ukuran</label> @endif
        <input type="text" name="ukuran[]" class="form-control produk-ukuran" readonly placeholder="Ukuran" style="width: 100px;">
    </div>
    <div class="col">
        @if($index == 0) <label class="form-label">Harga</label> @endif
        <input type="text" class="form-control item-price-display text-end" readonly placeholder="Harga Satuan">
        <input type="hidden" name="harga[]" class="item-price">
    </div>
    <div class="col">
        @if($index == 0) <label class="form-label">Total</label> @endif
        <input type="text" class="form-control item-total-display text-end" readonly placeholder="Total">
        <input type="hidden" name="total_item[]" class="item-total">
    </div>
    <div class="col-auto align-self-end">
         <button type="button" class="btn btn-danger remove-produk-item">&times;</button>
    </div>
</div>