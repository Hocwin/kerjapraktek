@extends('layout.master')
@section('title', 'Tambah Produk')

<style>
  .add-product-container {
    padding-top: 150px;
    /* Memberi ruang dari header */
    max-width: 600px;
    margin: 0 auto;
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

@section('content')
<div class="container add-product-container">
  <h2 class="text-center">Tambah Produk Baru</h2>
  <form method="POST" action="{{ route('store_product') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label for="namaProduk">Nama Produk</label>
      <input type="text" name="namaProduk" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="hargaCash">Harga Cash</label>
      <input type="number" name="hargaCash" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="hargaTempo">Harga Tempo</label>
      <input type="number" name="hargaTempo" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="hargaBeli">Harga Beli</label>
      <input type="number" name="hargaBeli" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="imageAsset">Image</label>
      <input type="file" name="imageAsset" class="form-control" required>
    </div>

    <h5>Stok per Gudang</h5>
    @foreach($gudangs as $gudang)
    <div class="form-group">
      <label for="stok_{{ $gudang->idGudang }}">{{ $gudang->namaGudang }} - Stok</label>
      <input type="number" name="stok[{{ $gudang->idGudang }}]" class="form-control" min="0" value="0">
    </div>
    @endforeach

    <button type="submit" class="btn btn-primary">Simpan Produk</button>
  </form>
</div>
@endsection