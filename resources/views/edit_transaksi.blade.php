@extends('layout.master')
@section('title', 'Edit Transaksi')

@section('content')
<div class="container pt-5">
    <h1>Edit Transaksi</h1>
    <form method="POST" action="{{ route('update_transaksi', $transaksi->idTransaksi) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="idToko">Nama Toko:</label>
            <select name="idToko" id="idToko" class="form-control" required>
                <option value="">Pilih Toko</option>
                @foreach($toko as $item)
                <option value="{{ $item->idToko }}" {{ $transaksi->idToko == $item->idToko ? 'selected' : '' }}>
                    {{ $item->namaToko }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="tipePembayaran">Tipe Pembayaran:</label>
            <select name="tipePembayaran" id="tipePembayaran" class="form-control" required>
                <option value="cash" {{ $transaksi->tipePembayaran == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="tempo" {{ $transaksi->tipePembayaran == 'tempo' ? 'selected' : '' }}>Tempo</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="lunas" {{ $transaksi->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="belum lunas" {{ $transaksi->status == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggalTransaksi">Tanggal & Jam Transaksi:</label>
            <input type="datetime-local" name="tanggalTransaksi" id="tanggalTransaksi" class="form-control"
                value="{{ old('tanggalTransaksi', \Carbon\Carbon::parse($transaksi->tanggalTransaksi)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection