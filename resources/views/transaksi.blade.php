@extends('layout.master')
@section('title', 'Halaman Transaksi')

<style>
  .transaksi-container {
    padding-top: 125px;
  }

  .aksi-btn {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 5px;
  }

  .edit-btn,
  .detail-btn,
  .add-btn {
    background-color: transparent;
    border: none;
    color: #007bff;
    padding: 6px 12px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: underline;
  }

  .detail-btn {
    color: rgb(220, 106, 53);
  }

  .detail-btn:hover {
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
</style>

@section('content')

<div class="container transaksi-container">
  <h2>Transaksi</h2>

  <!-- Filter Form -->
  <form method="GET" action="{{ route('transaksi') }}" class="mb-4">
    <div class="row">
      <div class="col-md-4">
        <label for="toko">Nama Toko</label>
        <select name="toko" id="toko" class="form-select">
          <option value="">Semua Toko</option>
          @foreach ($toko as $t)
          <option value="{{ $t->namaToko }}" {{ $filterToko == $t->namaToko ? 'selected' : '' }}>
            {{ $t->namaToko }}
          </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label for="bulan">Bulan</label>
        <select name="bulan" id="bulan" class="form-select">
          <option value="">Semua Bulan</option>
          @for ($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}" {{ $filterBulan == $i ? 'selected' : '' }}>
            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
            </option>
            @endfor
        </select>
      </div>
      <div class="col-md-2">
        <label for="tahun">Tahun</label>
        <select name="tahun" id="tahun" class="form-select">
          <option value="">Semua Tahun</option>
          @for ($i = 2020; $i <= Carbon\Carbon::now()->year + 1; $i++)
            <option value="{{ $i }}" {{ $i == $filterTahun ? 'selected' : '' }}>
              {{ $i }}
            </option>
            @endfor
        </select>
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('transaksi') }}" class="btn btn-secondary ms-2">Reset</a>
      </div>
    </div>
  </form>

  <!-- Tambah Transaksi Button -->
  <div class="add-btn-container">
    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
    <form method="GET" action="{{ route('add_transaksi') }}">
      @csrf
      <button type="submit" class="btn btn-primary">Tambah Transaksi</button>
    </form>
    @endif
  </div>

  <!-- Transaksi Table -->
  <div class="table-responsive" style="height: 400px; overflow-y: scroll;">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Nama Toko</th>
          <th>Tipe Pembayaran</th>
          <th>Status</th>
          <th>Tanggal Transaksi</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($transaksi as $item)
        <tr>
          <td>{{ $item->toko->namaToko }}</td>
          <td>{{ ucfirst($item->tipePembayaran) }}</td>
          <td class="{{ $item->status === 'lunas' ? 'status-lunas' : 'status-belum-lunas' }}">
            {{ ucfirst($item->status) }}
          </td>
          <td>{{ \Carbon\Carbon::parse($item->tanggalTransaksi)->format('Y-m-d H:i') }}</td>
          <td class="aksi-btn">
            <!-- Detail Button -->
            <form method="GET" action="{{ route('detail_transaksi', ['idTransaksi' => $item->idTransaksi]) }}">
              @csrf
              <button type="submit" class="btn btn-warning">Detail</button>
            </form>
            <!-- Edit Button (Admin Only) -->
            @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
            <form method="GET" action="{{ route('edit_transaksi', ['idTransaksi' => $item->idTransaksi]) }}">
              @csrf
              <button type="submit" class="btn btn-primary">Edit</button>
            </form>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5">Tidak ada transaksi yang ditemukan.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection