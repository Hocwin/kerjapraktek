@extends('layout.master')
@section('title', 'Edit Produk')

@section('content')
<style>
    .edit-product-container {
        margin-top: 125px;
        max-width: 600px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
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
</style>

<div class="container edit-product-container">
    <h2 class="text-center mb-4">Edit Produk</h2>
    <form action="{{ route('proses_editproduct', ['idProduk' => $produk->idProduk]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Error Messages -->
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
        @endforeach

        <!-- Nama Produk -->
        <div class="form-group">
            <label for="namaProduk">Nama Produk</label>
            <input type="text" name="namaProduk" id="namaProduk" class="form-control" value="{{ $produk->namaProduk }}" required>
        </div>

        <!-- Harga Cash -->
        <div class="form-group">
            <label for="hargaCash">Harga Cash</label>
            <input type="number" name="hargaCash" id="hargaCash" class="form-control" value="{{ $produk->hargaCash }}" required>
        </div>

        <!-- Harga Tempo -->
        <div class="form-group">
            <label for="hargaTempo">Harga Tempo</label>
            <input type="number" name="hargaTempo" id="hargaTempo" class="form-control" value="{{ $produk->hargaTempo }}" required>
        </div>

        <!-- Harga Beli -->
        <div class="form-group">
            <label for="hargaBeli">Harga Beli</label>
            <input type="number" name="hargaBeli" id="hargaBeli" class="form-control" value="{{ $produk->hargaBeli }}" required>
        </div>

        <!-- Gambar Produk -->
        <div class="form-group">
            <label for="imageAsset">Gambar Produk</label><br>
            <img src="{{ asset('/storage/images/' . $produk->imageAsset) }}" alt="current-image" class="w-100 mb-3" style="max-width: 100px; object-fit: contain;">
            <input type="file" name="imageAsset" id="imageAsset" class="form-control">
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="submit-btn mt-3 w-100">Simpan Perubahan</button>
    </form>
</div>
@endsection