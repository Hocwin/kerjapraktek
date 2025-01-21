@extends('layout.master')

@section('content')
<style>
    .add-warehouse-container {
        margin-top: 125px;
        max-width: 600px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        height: 850px;
        overflow-y: scroll;
    }

    .form-group label {
        font-weight: bold;
    }

    .form-control {
        margin-bottom: 15px;
    }

    .submit-btn {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.9rem;
        margin-top: -10px;
        margin-bottom: 10px;
    }

    h3 {
        margin-top: 30px;
        font-weight: bold;
    }
</style>

<div class="container add-warehouse-container">
    <h1 class="text-center mb-4">Tambah Gudang Baru</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('store_gudang') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="namaGudang">Nama Gudang</label>
            <input type="text" name="namaGudang" id="namaGudang" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="lokasi">Lokasi Gudang</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="imageAsset">Gambar Gudang (Opsional)</label>
            <input type="file" name="imageAsset" id="imageAsset" class="form-control" required>
        </div>

        <h3>Stok Produk</h3>
        @foreach($produk as $item)
        <div class="form-group">
            <label for="stok_{{ $item->idProduk }}">Stok untuk {{ $item->namaProduk }}</label>
            <input type="number" name="stok[{{ $item->idProduk }}]" id="stok_{{ $item->idProduk }}" class="form-control" min="1" required placeholder="Minimal 1">
        </div>
        @endforeach

        <button type="submit" class="submit-btn w-100">Simpan Gudang</button>
    </form>
</div>
@endsection