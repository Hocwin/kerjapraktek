@extends('layout.master')
@section('title', 'Tambah Produk')

@section('content')
<style>
  .add-product-container {
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
    background-color: #28a745;
    color: white;
    font-weight: bold;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
  }

  .submit-btn:hover {
    background-color: #218838;
  }

  .error-message {
    color: #dc3545;
    font-size: 0.9rem;
    margin-top: -10px;
    margin-bottom: 10px;
  }
</style>

<div class="container add-product-container">
  <h2 class="text-center mb-4">Tambah Produk Baru</h2>
  <form method="POST" action="{{ route('store_product') }}" enctype="multipart/form-data">
    @csrf
    <!-- Nama Produk -->
    <div class="form-group">
      <label for="namaProduk">Nama Produk</label>
      <input type="text" name="namaProduk" id="namaProduk" class="form-control" required>
    </div>

    <!-- Harga Cash -->
    <div class="form-group">
      <label for="hargaCash">Harga Cash</label>
      <input type="number" name="hargaCash" id="hargaCash" class="form-control" required>
    </div>

    <!-- Harga Tempo -->
    <div class="form-group">
      <label for="hargaTempo">Harga Tempo</label>
      <input type="number" name="hargaTempo" id="hargaTempo" class="form-control" required>
    </div>

    <!-- Harga Beli -->
    <div class="form-group">
      <label for="hargaBeli">Harga Beli</label>
      <input type="number" name="hargaBeli" id="hargaBeli" class="form-control" required>
    </div>

    <!-- Gambar Produk -->
    <div class="form-group">
      <label for="imageAsset">Gambar Produk</label>
      <input type="file" name="imageAsset" id="imageAsset" class="form-control" accept="image/*" required>
    </div>

    <!-- Stok per Gudang -->
    <h5 class="mt-4">Stok per Gudang</h5>
    @foreach($gudangs as $gudang)
    <div class="form-group">
      <label for="stok_{{ $gudang->idGudang }}">{{ $gudang->namaGudang }} - Stok</label>
      <input type="number" name="stok[{{ $gudang->idGudang }}]" id="stok_{{ $gudang->idGudang }}" class="form-control" min="0" required placeholder="minimal 0">
    </div>
    @endforeach

    <!-- Tombol Submit -->
    <button type="submit" class="submit-btn mt-4 w-100">Simpan Produk</button>
  </form>
</div>
@endsection