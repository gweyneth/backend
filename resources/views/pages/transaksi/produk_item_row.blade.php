<div class="form-row produk-item mt-3" data-index="{{ $index }}">
    <div class="col-md-2">
        <div class="form-group">
            <label>Produk</label>
            <input type="hidden" name="produk_id[{{ $index }}]" class="produk-id" value="{{ old('produk_id.' . $index, $detail->produk_id ?? '') }}">
            <select name="nama_produk[{{ $index }}]" class="form-control produk-name @error('nama_produk.' . $index) is-invalid @enderror" required>
                <option value="">Pilih Produk</option>
                @foreach($produks as $produk)
                    <option value="{{ $produk->nama }}"
                            data-id="{{ $produk->id }}"
                            data-ukuran="{{ $produk->ukuran }}"
                            data-satuan="{{ $produk->satuan->nama ?? '' }}"
                            data-harga="{{ $produk->harga_jual }}"
                            {{ old('nama_produk.' . $index, $detail->nama_produk ?? '') == $produk->nama ? 'selected' : '' }}>
                        {{ $produk->nama }}
                    </option>
                @endforeach
            </select>
            @error('nama_produk.' . $index)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan[{{ $index }}]" class="form-control" placeholder="Keterangan" value="{{ old('keterangan.' . $index, $detail->keterangan ?? '') }}">
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label>Qty</label>
            <input type="number" name="qty[{{ $index }}]" class="form-control item-qty @error('qty.' . $index) is-invalid @enderror" value="{{ old('qty.' . $index, $detail->qty ?? 1) }}" min="1" required>
            @error('qty.' . $index)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label>Ukuran</label>
            <input type="text" name="ukuran[{{ $index }}]" class="form-control produk-ukuran" readonly value="{{ old('ukuran.' . $index, $detail->ukuran ?? '') }}">
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label>Satuan</label>
            <input type="text" name="satuan[{{ $index }}]" class="form-control produk-satuan" readonly value="{{ old('satuan.' . $index, $detail->satuan ?? '') }}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga[{{ $index }}]" class="form-control item-price @error('harga.' . $index) is-invalid @enderror" value="{{ old('harga.' . $index, $detail->harga ?? 0) }}" min="0" step="0.01" required>
            @error('harga.' . $index)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Total</label>
            <input type="text" name="total_item[{{ $index }}]" class="form-control item-total" value="{{ old('total_item.' . $index, $detail->total ?? 0) }}" readonly>
        </div>
    </div>
    <div class="col-md-1 d-flex align-items-end">
        <button type="button" class="btn btn-danger remove-produk-item"><i class="fas fa-trash"></i></button>
    </div>
</div>