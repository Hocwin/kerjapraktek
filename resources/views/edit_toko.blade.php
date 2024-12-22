@extends('layout.master')
@section('title', 'Edit Toko')

@section('content')
<style>
    /* Custom Styles */
    .form-container {
        margin-top: 50px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background-color: #f9f9f9;
    }

    .form-container img {
        max-width: 100px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    .form-container label {
        font-weight: bold;
        margin-top: 10px;
    }

    .form-container input,
    .form-container select,
    .form-container button {
        margin-bottom: 15px;
        font-size: 14px;
        padding: 10px;
    }

    .form-container button {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        font-size: 16px;
    }

    .alert {
        margin-bottom: 20px;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .form-container {
            margin: 0 10px;
        }

        .container {
            padding-left: 0;
            padding-right: 0;
        }

        .col-4 {
            display: none;
        }

        .col-8 {
            width: 100%;
        }
    }
</style>

<div class="container mt-5">
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4 form-container">
            <form action="{{ route('update_toko', ['idToko' => $toko->idToko]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Menampilkan Error -->
                @foreach($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
                @endforeach

                <!-- Nama Toko -->
                <label for="namaToko">Nama Toko</label>
                <input type="text" name="namaToko" id="namaToko" class="form-control" value="{{ $toko->namaToko }}" required>

                <!-- Alamat Toko -->
                <label for="alamatToko">Alamat Toko</label>
                <input type="text" name="alamatToko" id="alamatToko" class="form-control" value="{{ $toko->alamatToko }}" required>

                <!-- Nomor Telepon Toko -->
                <label for="nomorTelepon">Nomor Telepon</label>
                <input type="text" name="nomorTelepon" id="nomorTelepon" class="form-control" value="{{ $toko->nomorTelepon }}" required>

                <!-- Jam Operasional Toko -->
                <label for="jamOperasional">Jam Operasional</label>
                <input type="text" name="jamOperasional" id="jamOperasional" class="form-control" value="{{ $toko->jamOperasional }}" required>

                <!-- Jam Operasional Toko -->
                <label for="namaSopir">Nama Sopir</label>
                <input type="text" name="namaSopir" id="namaSopir" class="form-control" value="{{ $toko->namaSopir }}" required>

                <!-- Gambar Toko -->
                <label for="imageAsset">Gambar Toko</label><br>
                <img src="{{ asset('/storage/images/' . $toko->imageAsset) }}" alt="current-image" class="w-100" style="max-width: 100px; object-fit: contain;">
                <input type="file" name="imageAsset" id="imageAsset" class="form-control">

                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-primary mt-3">Update Toko</button>
            </form>
        </div>
        <div class="col-4"></div>
    </div>
</div>
@endsection