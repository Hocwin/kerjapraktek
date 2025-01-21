@extends('layout.master')
@section('title', 'Halaman Produk')

<style>
  .produk-container {
    padding-top: 125px;
  }

  .produk-img {
    width: 100px;
    height: auto;
    object-fit: contain;
  }

  .harga-cash {
    color: #28a745;
    font-weight: bold;
  }

  .harga-tempo {
    color: #dc3545;
    font-weight: bold;
  }

  .harga-beli {
    color: rgb(38, 178, 183);
    font-weight: bold;
  }

  .aksi-btn {
    display: column;
    flex-direction: column;
    gap: 5px;
    justify-content: center;
    align-items: center;
  }

  .add-btn-container {
    text-align: right;
    margin-bottom: 20px;
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
  .restore-btn {
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

  .restore-btn {
    color: #28a745;
  }

  .delete-btn:hover {
    color: #b02a37;
  }

  .restore-btn:hover {
    color: #218838;
  }

  table th,
  table td {
    text-align: center;
    vertical-align: middle;
  }

  .restore-btn-container {
    justify-content: center;
    gap: 10px;
    align-items: center;
  }

  @media (max-width: 768px) {
    .produk-img {
      width: 80px;
    }

    .restore-btn-container {
      gap: 5px;
    }
  }
</style>

@section('content')

<div class="container produk-container">
    <h2>Produk Aktif</h2>
    <div class="btn-container">
        @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
        <form method="GET" action="{{ route('add_produk') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Tambah Produk</button>
        </form>
        <form method="GET" action="{{ route('produk_tidak_aktif') }}">
            @csrf
            <input type="hidden" name="page" value="produk">
            <button type="submit" class="btn btn-danger">Produk Tidak Aktif</button>
        </form>
        @endif
    </div>

    <div class="table-responsive" style="height: 400px; overflow-y: scroll;">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Gambar</th>
            <th>Nama Produk</th>
            <th>Harga Cash</th>
            <th>Harga Tempo</th>
            <th>Harga Beli</th>
            @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
            <th class="text-center">Aksi</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach ($produkAktif as $item)
        <tr>
            <td><img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaProduk }}" class="produk-img"></td>
            <td>{{ $item->namaProduk }}</td>
            <td class="harga-cash">Rp. {{ number_format($item->hargaCash, 0, ',', '.') }}</td>
            <td class="harga-tempo">Rp. {{ number_format($item->hargaTempo, 0, ',', '.') }}</td>
            <td class="harga-beli">Rp. {{ number_format($item->hargaBeli, 0, ',', '.') }}</td>
            @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
            <td class="aksi-btn">
            <form method="GET" action="{{ route('edit_product', ['idProduk' => $item->idProduk]) }}">
                @csrf
                <button type="submit" class="btn btn-primary">Edit</button>
            </form>
            <form method="POST" action="{{ route('destroy_product', ['idProduk' => $item->idProduk]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
            </td>
            @endif
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>

@endsection
