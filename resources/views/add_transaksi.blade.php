@extends('layout.master')

@section('title', 'Tambah Transaksi')

<style>
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

    .submit-btn {
        background-color: #28a745;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .submit-btn:hover {
        background-color: #218838;
    }

    .form-container select,
    .form-container input {
        background-color: #ffffff;
    }
</style>

@section('content')
<div class="container form-container">
    <h2>Tambah Transaksi</h2>
    <form method="POST" action="{{ route('store_transaksi') }}">
        @csrf

        <!-- Pilih Toko -->
        <div class="form-group">
            <label for="idToko">Toko</label>
            <select name="idToko" class="form-select" required>
                @foreach ($toko as $item)
                <option value="{{ $item->idToko }}">{{ $item->namaToko }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipe Pembayaran -->
        <div class="form-group">
            <label for="tipePembayaran">Tipe Pembayaran</label>
            <select name="tipePembayaran" class="form-select" required>
                <option value="cash">Cash</option>
                <option value="tempo">Tempo</option>
            </select>
        </div>

        <!-- Tanggal Transaksi -->
        <div class="form-group">
            <label for="tanggalTransaksi">Tanggal & Jam Transaksi</label>
            <input type="datetime-local" name="tanggalTransaksi" id="tanggalTransaksi" class="form-control" required>
        </div>

        <!-- Status Pembayaran -->
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-select" required>
                <option value="belum lunas">Belum Lunas</option>
                <option value="lunas">Lunas</option>
            </select>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="submit-btn w-100 mt-3">Simpan Transaksi</button>
    </form>
</div>
@endsection