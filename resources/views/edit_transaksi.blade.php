@extends('layout.master')

@section('title', 'Edit Transaksi')

<style>
    .form-container {
        margin-top: 125px;
        max-width: 600px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-container h1 {
        text-align: center;
        color: #343a40;
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
        color: #495057;
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
        background-color: #007bff;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }
</style>

@section('content')
<div class="container form-container">
    <h1>Edit Transaksi</h1>
    <form method="POST" action="{{ route('update_transaksi', $transaksi->idTransaksi) }}">
        @csrf
        @method('PUT')

        <!-- Pilih Toko -->
        <div class="form-group">
            <label for="idToko">Nama Toko</label>
            <select name="idToko" id="idToko" class="form-select" required>
                <option value="">Pilih Toko</option>
                @foreach($toko as $item)
                <option value="{{ $item->idToko }}" {{ $transaksi->idToko == $item->idToko ? 'selected' : '' }}>
                    {{ $item->namaToko }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Tipe Pembayaran -->
        <div class="form-group">
            <label for="tipePembayaran">Tipe Pembayaran</label>
            <select name="tipePembayaran" id="tipePembayaran" class="form-select" required>
                <option value="cash" {{ $transaksi->tipePembayaran == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="tempo" {{ $transaksi->tipePembayaran == 'tempo' ? 'selected' : '' }}>Tempo</option>
            </select>
        </div>

        <!-- Status -->
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="lunas" {{ $transaksi->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="belum lunas" {{ $transaksi->status == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
            </select>
        </div>

        <!-- Tanggal Transaksi -->
        <div class="form-group">
            <label for="tanggalTransaksi">Tanggal & Jam Transaksi</label>
            <input type="datetime-local" name="tanggalTransaksi" id="tanggalTransaksi" class="form-control"
                value="{{ old('tanggalTransaksi', \Carbon\Carbon::parse($transaksi->tanggalTransaksi)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <!-- Tombol Simpan -->
        <button type="submit" class="submit-btn w-100 mt-3">Simpan</button>
    </form>
</div>
@endsection