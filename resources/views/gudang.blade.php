@extends('layout.master')
@section('title', 'Halaman Gudang')

<style>
  .gudang-container {
    padding-top: 150px;
  }

  .gudang-img {
    width: 100px;
    height: auto;
    object-fit: contain;
  }

  .hidden {
    display: none;
  }

  table th,
  table td {
    vertical-align: middle;
    text-align: center;
    padding: 10px;
  }

  .aksi-btn {
    text-align: center;
  }

  .btn-action {
    background-color: transparent;
    border: none;
    color: #007bff;
    padding: 6px 12px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: underline;
  }

  .btn-action:hover {
    color: #0056b3;
    text-decoration: none;
  }

  .add-btn-container {
    text-align: right;
    margin-bottom: 20px;
  }

  .table-container {
    margin-bottom: 30px;
  }

  .stok-table {
    background-color: transparent !important;
    margin: 0 auto;
    text-align: center;
    border: none;
  }

  .stok-empty {
    font-style: italic;
    color: #6c757d;
  }
</style>

@section('content')

<div class="container gudang-container">
  <div class="add-btn-container">
    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
    <a href="{{ route('add_gudang') }}" class="btn-action">Tambah Gudang</a>
    @endif
  </div>

  <!-- Active Gudang Table -->
  <div class="table-container">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Gambar</th>
          <th>Nama Gudang</th>
          <th>Lokasi</th>
          <th>Stok & Pemasukan</th>
          @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
          <th>Aksi</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach ($gudangAktif as $item)
        <tr>
          <td><img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaGudang }}" class="gudang-img"></td>
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
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
            @endif
          </td>
          @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
          <td class="aksi-btn">
            <a href="{{ route('edit-gudang', ['idGudang' => $item->idGudang]) }}" class="btn-action">Edit</a>
            <form action="{{ route('destroy_gudang', ['idGudang' => $item->idGudang]) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus gudang ini?')">Hapus</button>
            </form>
          </td>
          @endif
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Deleted Gudang Table -->
  @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
  <div class="table-container">
    <h2 class="text-center text-secondary mb-3">Gudang Terhapus</h2>
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Gambar</th>
          <th>Nama Gudang</th>
          <th>Lokasi</th>
          <th>Stok & Pemasukan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($gudangTerhapus as $item)
        <tr>
          <td><img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaGudang }}" class="gudang-img"></td>
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
                </tr>
              </thead>
              <tbody>
                @foreach ($item->stokPerGudang as $stok)
                @if ($stok->produk)
                <tr>
                  <td>{{ $stok->produk->namaProduk }}</td>
                  <td>{{ $stok->stok }} sak</td>
                  <td>{{ $stok->stokSekarang ?? 0 }} sak</td>
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
            @endif
          </td>
          <td>
            <form method="POST" action="{{ route('restore_gudang', ['idGudang' => $item->idGudang]) }}">
              @csrf
              @method('PUT')
              <button type="submit" class="btn-action">Restore</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>
@endsection