@extends('layout.master')

@section('title', 'Tambah Detail Transaksi')

<style>
    .produk-row {
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        margin-bottom: 20px;
        position: relative;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .form-select {
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ced4da;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 5px;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        color: #fff;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        color: #fff;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
        color: #fff;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .container {
        max-width: 800px;
    }

    .button-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .button-container .btn {
        width: 48%;
    }
</style>

@section('content')
<div class="container mt-5" style="height: 850px;overflow-y: scroll;">
    <h2 class="mb-4">Tambah Detail Transaksi</h2>
    <form action="{{ route('store_detail_transaksi', $idTransaksi) }}" method="POST">
        @csrf
        <div id="produkContainer">
            <div class="produk-row mb-4" id="productRow0">
                <label for="idProduk">Produk</label>
                <select name="produk[0][idProduk]" required class="form-select mb-3">
                    @foreach($produk as $item)
                    <option value="{{ $item->idProduk }}">{{ $item->namaProduk }}</option>
                    @endforeach
                </select>
                <label for="jumlahProduk">Jumlah</label>
                <input type="number" name="produk[0][jumlahProduk]" required min="1" class="form-control mb-3">

                <label for="idGudang">Gudang</label>
                <select name="produk[0][idGudang]" required class="form-select mb-3">
                    @foreach($gudang as $item)
                    <option value="{{ $item->idGudang }}">{{ $item->namaGudang }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-danger" onclick="removeProductRow(0)">Hapus</button>
            </div>
        </div>

        <div class="button-container">
            <button type="button" onclick="addProductRow()" class="btn btn-secondary">Tambah Produk</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<script>
    let productCount = 1;

    function addProductRow() {
        const container = document.getElementById('produkContainer');
        const newRow = document.createElement('div');
        newRow.classList.add('produk-row', 'mb-4');
        newRow.id = 'productRow' + productCount;

        newRow.innerHTML = `
            <label for="idProduk">Produk</label>
            <select name="produk[${productCount}][idProduk]" required class="form-select mb-3">
                @foreach($produk as $item)
                    <option value="{{ $item->idProduk }}">{{ $item->namaProduk }}</option>
                @endforeach
            </select>
            <label for="jumlahProduk">Jumlah</label>
            <input type="number" name="produk[${productCount}][jumlahProduk]" required min="1" class="form-control mb-3">
            <label for="idGudang">Gudang</label>
            <select name="produk[${productCount}][idGudang]" required class="form-select mb-3">
                @foreach($gudang as $item)
                    <option value="{{ $item->idGudang }}">{{ $item->namaGudang }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-danger" onclick="removeProductRow(${productCount})">Hapus</button>
        `;

        container.appendChild(newRow);
        productCount++;
    }

    function removeProductRow(rowId) {
        const row = document.getElementById('productRow' + rowId);
        if (row) {
            row.remove();
        }
    }
</script>

@endsection