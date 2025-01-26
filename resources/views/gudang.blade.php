@extends('layout.master')
@section('title', 'Halaman Gudang')

<style>
  .gudang-container {
    padding-top: 125px;
  }

  .gudang-img {
    width: 80px;
    height: auto;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .btn-container {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-bottom: 20px;
  }

  .table-container {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .stok-empty {
    font-style: italic;
    color: #6c757d;
  }

  .btn-action {
    padding: 6px 12px;
    font-weight: bold;
    border-radius: 6px;
    border: 1px solid transparent;
  }

  .btn-action.btn-primary {
    background-color: #007bff;
    color: white;
  }

  .btn-action.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
  }

  .btn-action.btn-danger {
    background-color: #dc3545;
    color: white;
  }

  .btn-action.btn-danger:hover {
    background-color: #b02a37;
    border-color: #b02a37;
  }

  .btn-action.btn-secondary {
    background-color: #6c757d;
    color: white;
  }

  .btn-action.btn-secondary:hover {
    background-color: #495057;
    border-color: #495057;
  }

  table th,
  table td {
    vertical-align: middle;
    text-align: center;
    padding: 10px;
  }

  table thead {
    background-color: #f8f9fa;
  }

  table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
  }
</style>

@section('content')

<div class="container gudang-container">
  <h2 class="mb-4">Gudang Aktif</h2>
  <div class="btn-container">
    @if (Auth::check() && Auth::user()->rolePengguna == 'manager')
    <form method="GET" action="{{ route('add_gudang') }}">
      @csrf
      <button type="submit" class="btn btn-primary">Tambah Gudang</button>
    </form>
    <form method="GET" action="{{ route('gudang_tidak_aktif') }}">
      @csrf
      <button type="submit" class="btn btn-danger">Gudang Tidak Aktif</button>
    </form>
    @endif
  </div>

  <div class="table-responsive table-container">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Gambar</th>
          <th>Nama Gudang</th>
          <th>Lokasi</th>
          <th>Stok & Pemasukan</th>
          @if (Auth::check() && Auth::user()->rolePengguna == 'manager')
          <th>Aksi</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach ($gudangAktif as $item)
        <tr>
          <td>
            <img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaGudang }}" class="gudang-img">
          </td>
          <td>{{ $item->namaGudang }}</td>
          <td>{{ $item->lokasi }}</td>
          <td>
            @if($item->stokPerGudang->isEmpty())
            <span class="stok-empty">Tidak ada data stok</span>
            @else
            <table class="stok-table">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Stok</th>
                  <th>Pemasukan</th>
                  <th>Pengeluaran</th>
                  <th>Retur</th>
                  <th>Total Harga Retur</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($item->stokPerGudang as $stok)
                @if ($stok->produk)
                <tr>
                  <td>{{ $stok->produk->namaProduk }}</td>
                  <td>{{ $stok->stok }} sak</td>
                  <td>{{ $stok->pemasukan ?? 0 }} sak</td>
                  <td>{{ $stok->pengeluaran ?? 0 }} sak</td>
                  <td>{{ $stok->retur ?? 0 }} sak</td>
                  <td>Rp {{ number_format($stok->totalHargaRetur ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
            @endif
          </td>
          <td class="aksi-btn">
            @if (Auth::check() && Auth::user()->gudang->contains($item->idGudang))
            <form method="GET" action="{{ route('edit-gudang', ['idGudang' => $item->idGudang]) }}">
              @csrf
              <button type="submit" class="btn-action btn-primary">Edit</button>
            </form>
            @endif
            @if (Auth::check() && Auth::user()->rolePengguna == 'manager')
            <form method="POST" action="{{ route('destroy_gudang', ['idGudang' => $item->idGudang]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko ini?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-action btn-danger">Hapus</button>
            </form>
            @endif
            @if (Auth::check() && Auth::user()->gudang->contains($item->idGudang) || Auth::user()->rolePengguna == 'manager' || Auth::user()->rolePengguna == 'admin')
            <form method="GET" action="{{ route('gudang.history', ['idGudang' => $item->idGudang]) }}">
              @csrf
              <button type="submit" class="btn btn-secondary">Histori</button>
            </form>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection