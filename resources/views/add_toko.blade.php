@extends('layout.master')
@section('title', 'Tambah Toko')

@section('content')
<style>
    .add-store-container {
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

<div class="container add-store-container">
    <h2 class="text-center mb-4">Tambah Toko Baru</h2>
    <form method="POST" action="{{ route('store_toko') }}" enctype="multipart/form-data">
        @csrf

        <!-- Nama Toko -->
        <div class="form-group">
            <label for="namaToko">Nama Toko</label>
            <input type="text" name="namaToko" id="namaToko" class="form-control" required>
        </div>

        <!-- Alamat Toko -->
        <div class="form-group">
            <label for="alamatToko">Alamat Toko</label>
            <input type="text" name="alamatToko" id="alamatToko" class="form-control" required>
        </div>

        <!-- Nomor Telepon -->
        <div class="form-group">
            <label for="nomorTelepon">Nomor Telepon</label>
            <input type="text" name="nomorTelepon" id="nomorTelepon" class="form-control" required>
        </div>

        <!-- Jam Operasional -->
        <div class="form-group">
            <label for="jamOperasional">Jam Operasional</label>
            <input type="text" name="jamOperasional" id="jamOperasional" class="form-control" required>
        </div>

        <!-- Nama Sopir -->
        <div class="form-group">
            <label for="namaSopir">Nama Sopir</label>
            <input type="text" name="namaSopir" id="namaSopir" class="form-control" required>
        </div>

        <!-- Gambar Toko -->
        <div class="form-group">
            <label for="imageAsset">Gambar Toko</label>
            <input type="file" name="imageAsset" id="imageAsset" class="form-control" required>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="submit-btn mt-3 w-100">Tambah Toko</button>
    </form>
</div>
@endsection