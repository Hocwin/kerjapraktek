@extends('layout.master')
@section('title', 'Tambah Toko')

@section('content')
<div class="container mt-5">
    <h2>Tambah Toko Baru</h2>
    <form method="POST" action="{{ route('store_toko') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="namaToko">Nama Toko</label>
            <input type="text" name="namaToko" id="namaToko" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="alamatToko">Alamat Toko</label>
            <input type="text" name="alamatToko" id="alamatToko" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nomorTelepon">Nomor Telepon</label>
            <input type="text" name="nomorTelepon" id="nomorTelepon" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="jamOperasional">Jam Operasional</label>
            <input type="text" name="jamOperasional" id="jamOperasional" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="namaSopir">Nama Sopir</label>
            <input type="text" name="namaSopir" id="namaSopir" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="imageAsset">Gambar Toko</label>
            <input type="file" name="imageAsset" id="imageAsset" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Tambah Toko</button>
    </form>
</div>
@endsection