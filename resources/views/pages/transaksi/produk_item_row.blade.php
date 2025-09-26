{{-- File: resources/views/pages/transaksi/produk_item_row.blade.php (SUDAH DIPERBAIKI) --}}
<div class="row produk-item mb-2 align-items-center" data-index="{{ $index }}">
    <input type="hidden" name="produk_id[]" class="produk-id" value="{{ old('produk_id.'.$index, $detail->produk_id ?? '') }}">
    
    <div class="col-md-3"><div class="form-group mb-0">
        @if($index == 0) <label>Produk</label> @endif
        <select name="nama_produk[]" class="form-control produk-name">
            <option value="">Pilih Produk</option>
            @foreach ($produks as $produk)
                <option value="{{ $produk->nama }}"
                        data-id="{{ $produk->id }}"
                        data-ukuran="{{ $produk->ukuran }}"
                        data-satuan="{{ $produk->satuan->nama ?? '' }}"
                        data-harga="{{ $produk->harga_jual }}"
                        {{ old('nama_produk.'.$index, $detail->nama_produk ?? '') == $produk->nama ? 'selected' : '' }}>
                    {{ $produk->nama }}
                </option>
            @endforeach
        </select>
    </div></div>

    <div class="col-md-2"><div class="form-group mb-0">
        @if($index == 0) <label>Keterangan</label> @endif
        <input type="text" name="keterangan[]" class="form-control" placeholder="Keterangan" value="{{ old('keterangan.'.$index, $detail->keterangan ?? '') }}">
    </div></div>
    
    <div class="col-md-1"><div class="form-group mb-0">
        @if($index == 0) <label>Qty</label> @endif
        <input type="number" name="qty[]" class="form-control item-qty" value="{{ old('qty.'.$index, $detail->qty ?? 1) }}" min="1">
    </div></div>

    <div class="col-md-1"><div class="form-group mb-0">
        @if($index == 0) <label>Ukuran</label> @endif
        <input type="text" name="ukuran[]" class="form-control produk-ukuran" readonly value="{{ old('ukuran.'.$index, $detail->ukuran ?? '') }}">
    </div></div>
    
    <div class="col-md-1"><div class="form-group mb-0">
        @if($index == 0) <label>Satuan</label> @endif
        <input type="text" name="satuan[]" class="form-control produk-satuan" readonly value="{{ old('satuan.'.$index, $detail->satuan ?? '') }}">
    </div></div>

    <div class="col-md-2"><div class="form-group mb-0">
        @if($index == 0) <label>Harga</label> @endif
        {{-- PERBAIKAN: type diubah ke "text" dan value diberi (int) --}}
        <input type="text" name="harga[]" class="form-control item-price" value="{{ old('harga.'.$index, isset($detail) ? (int)$detail->harga : 0) }}">
    </div></div>

    <div class="col-md-2"><div class="d-flex align-items-center">
        <div class="form-group mb-0 flex-grow-1">
            @if($index == 0) <label>Total</label> @endif
            {{-- PERBAIKAN: Dipecah menjadi 2 input, hidden untuk nilai, dan display untuk tampilan --}}
            <input type="hidden" name="total_item[]" class="item-total-hidden" value="{{ old('total_item.'.$index, isset($detail) ? (int)$detail->total : 0) }}">
            <input type="text" class="form-control item-total-display" readonly>
        </div>
        <button type="button" class="btn btn-danger btn-sm ml-2 remove-produk-item" style="margin-top: {{ $index == 0 ? '24px' : '0' }};">&times;</button>
    </div></div>
</div>