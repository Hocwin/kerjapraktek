@extends('layout.master')

@section('title', 'Edit Detail Transaksi')

<style>
    .form-container {
        padding-top: 150px;
    }
</style>

@section('content')
<div class="container form-container">
    <h2>Edit Detail Transaksi</h2>
    <form method="POST" action="{{ route('update_detail_transaksi', ['idDetailTransaksi' => $detailTransaksi->idDetailTransaksi]) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="produk">Produk</label>
            <select name="idProduk" class="form-control" required>
                @foreach ($produk as $item)
                    <option value="{{ $item->idProduk }}" {{ old('idProduk', $detailTransaksi->idProduk) == $item->idProduk ? 'selected' : '' }}>
                        {{ $item->namaProduk }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlahProduk">Jumlah Produk</label>
            <input type="number" name="jumlahProduk" class="form-control" required min="1" value="{{ old('jumlahProduk', $detailTransaksi->jumlahProduk) }}">
        </div>

        <div class="form-group">
            <label for="idGudang">Gudang</label>
            <select name="idGudang" class="form-control" required>
                @foreach ($gudang as $item)
                    <option value="{{ $item->idGudang }}" {{ old('idGudang', $detailTransaksi->idGudang) == $item->idGudang ? 'selected' : '' }}>
                        {{ $item->namaGudang }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Detail Transaksi</button>
    </form>
</div>
@endsection
