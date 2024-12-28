@extends('layout.master')
@section('title', 'Halaman Gudang')

<style>
  .gudang-container {
    padding-top: 125px;
  }

  .gudang-img {
    width: 100px;
    height: auto;
    object-fit: contain;
  }

  .hidden {
    display: none;
  }

  .btn-container {
    display: flex;
    justify-content: right;
    gap: 20px;
    margin-bottom: 5px;
  }

  .add-btn,
  .edit-btn,
  .delete-btn,
  .restore-btn,
  .detail-btn {
    background-color: transparent;
    border: none;
    color: #007bff;
    padding: 6px 12px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: underline;
  }

  .delete-btn {
    color: #dc3545;
  }

  .detail-btn {
    color: rgb(220, 106, 53);
  }

  .restore-btn {
    color: #28a745;
  }

  .delete-btn:hover {
    color: #b02a37;
  }

  .detail-btn:hover {
    color: #b02a37;
  }

  .restore-btn:hover {
    color: #218838;
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
  <h2>Gudang Aktif</h2>
  <div class="btn-container">
    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
    <form method="GET" action="{{ route('add_gudang') }}">
      @csrf
      <button type="submit" class="btn btn-primary">Tambah Gudang</button>
    </form>
    <form method="GET" action="{{ route('gudang_tidak_aktif') }}">
      @csrf
      <input type="hidden" name="page" value="gudang">
      <button type="submit" class="btn btn-danger">Gudang Tidak Aktif</button>
    </form>
    @endif
  </div>

  <div class="table-responsive" style="height: 400px; overflow-y: scroll;">
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
            <td class="aksi-btn">
              @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
              <form method="GET" action="{{ route('edit-gudang', ['idGudang' => $item->idGudang]) }}">
                @csrf
                <button type="submit" class="btn btn-primary">Edit</button>
              </form>
              <form method="POST" action="{{ route('destroy_gudang', ['idGudang' => $item->idGudang]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
              </form>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection