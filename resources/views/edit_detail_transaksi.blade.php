@extends('layout.master')

@section('title', 'Edit Detail Transaksi')

<style>
    .container.form-container {
        max-width: 500px;
        margin: 110px auto;
        padding: 20px;
    }

    .form-container {
        margin-top: 125px;
        max-width: 600px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
        text-align: center;
        color: #343a40;
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
        color: #495057;
    }

    .form-control {
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ced4da;
    }

    .form-select {
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ced4da;
    }

    .form-control:focus {
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        border-color: #007bff;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>

@section('content')
<div class="container form-container">
    <h2>Edit Detail Transaksi</h2>

    <!-- Error and Success Messages -->
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    <form method="POST" action="{{ route('update_detail_transaksi', ['idDetailTransaksi' => $detailTransaksi->idDetailTransaksi]) }}">
        @csrf
        @method('PUT')

        <!-- Produk -->
        <div class="form-group">
            <label for="produk">Produk</label>
            <select name="idProduk" id="produk" class="form-select" required>
                @foreach ($produk as $item)
                <option value="{{ $item->idProduk }}" {{ old('idProduk', $detailTransaksi->idProduk) == $item->idProduk ? 'selected' : '' }}>
                    {{ $item->namaProduk }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Jumlah Produk -->
        <div class="form-group">
            <label for="jumlahProduk">Jumlah Produk</label>
            <input type="number" name="jumlahProduk" id="jumlahProduk" class="form-control" required min="1" value="{{ old('jumlahProduk', $detailTransaksi->jumlahProduk) }}">
        </div>

        <!-- Gudang -->
        <div class="form-group">
            <label for="idGudang">Gudang</label>
            <select name="idGudang" id="idGudang" class="form-select" required>
                @foreach ($gudang as $item)
                <option value="{{ $item->idGudang }}" {{ old('idGudang', $detailTransaksi->idGudang) == $item->idGudang ? 'selected' : '' }}>
                    {{ $item->namaGudang }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Tombol Update -->
        <button type="submit" class="btn btn-primary w-100 mt-3">Update Detail Transaksi</button>
    </form>
</div>
@endsection