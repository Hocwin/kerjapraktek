@extends('layout.master')

@section('title', 'Tambah Transaksi')

<style>
    .form-container {
        padding-top: 150px;
    }
</style>

@section('content')
<div class="container form-container">
    <h2>Tambah Transaksi</h2>
    <form method="POST" action="{{ route('store_transaksi') }}">
        @csrf
        <div class="form-group">
            <label for="idToko">Toko</label>
            <select name="idToko" class="form-control" required>
                @foreach ($toko as $item)
                    <option value="{{ $item->idToko }}">{{ $item->namaToko }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="tipePembayaran">Tipe Pembayaran</label>
            <select name="tipePembayaran" class="form-control" required>
                <option value="cash">Cash</option>
                <option value="tempo">Tempo</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggalTransaksi">Tanggal Transaksi</label>
            <input type="date" name="tanggalTransaksi" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control" required>
                <option value="belum lunas">Belum Lunas</option>
                <option value="lunas">Lunas</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan Transaksi</button>
    </form>
</div>
@endsection
