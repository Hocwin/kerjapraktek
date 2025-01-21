@extends('layout.master')
@section('title', 'Halaman Detail Toko')

<style>
  .transaksi-container {
    margin-top: 150px;
    padding-bottom: 70px;
    height: 420px;
    overflow-y: scroll;
  }

  .aksi-btn {
    display: flex;
    flex-direction: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
  }

  .detail-btn,
  .delete-btn,
  .add-btn,
  .back-btn {
    background-color: transparent;
    border: none;
    color: #007bff;
    padding: 6px 12px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: underline;
  }

  .detail-btn:hover,
  .delete-btn:hover,
  .add-btn:hover,
  .back-btn:hover {
    color: #0056b3;
    text-decoration: none;
  }

  .delete-btn {
    color: #dc3545;
  }

  .delete-btn:hover {
    color: #b02a37;
  }

  .status-lunas {
    color: green;
    font-weight: bold;
  }

  .status-belum-lunas {
    color: red;
    font-weight: bold;
  }

  .add-btn-container {
    text-align: right;
    margin-bottom: 20px;
  }

  footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    text-align: center;
    padding: 10px;
    background-color: #f8f9fa;
  }

  .table td {
    vertical-align: middle;
  }

  .table th,
  .table td {
    text-align: center;
  }

  .table td.aksi-btn {
    padding-top: 10px;
  }

  .back-btn-container {
    text-align: left;
    margin-top: 20px;
  }

  .back-btn {
    background-color: transparent;
    border: none;
    color: #007bff;
    font-weight: bold;
    text-decoration: underline;
  }

  .back-btn:hover {
    color: #0056b3;
    text-decoration: none;
  }
</style>

@section('content')

<div class="container transaksi-container">

  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Nama Toko</th>
        <th>Tipe Pembayaran</th>
        <th>Status</th>
        <th>Tanggal Transaksi</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($transaksi as $item)
      <tr>
        <td>{{ $item->toko->namaToko }}</td>
        <td>{{ ucfirst($item->tipePembayaran) }}</td>
        <td class="{{ $item->status === 'lunas' ? 'status-lunas' : 'status-belum-lunas' }}">
          {{ ucfirst($item->status) }}
        </td>
        <td>{{ $item->tanggalTransaksi }}</td>
        <td class="aksi-btn">
          <form method="GET" action="{{ route('detail_transaksi', ['idTransaksi' => $item->idTransaksi]) }}">
            @csrf
            <button type="submit" class="btn btn-primary">Detail</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="back-btn-container">
    <form method="GET" action="{{ route('toko') }}">
      @csrf
      <button type="submit" class="btn btn-primary">Kembali</button>
    </form>
  </div>

</div>

@endsection