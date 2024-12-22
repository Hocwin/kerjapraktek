@extends('layout.master')
@section('title', 'Halaman Produk')

<style>
  .produk-container {
    padding-top: 150px;
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

  /* Styling for the restore button container */
  .restore-btn-container {
    justify-content: center;
    gap: 10px;
    align-items: center;
  }

  /* Adjustments for responsive view */
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
  <div class="add-btn-container">
    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
    <form method="GET" action="{{ route('add_produk') }}">
      @csrf
      <button type="submit" class="add-btn">Add Produk</button>
    </form>
    @endif
  </div>

  <h2>Produk Aktif</h2>
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Gambar</th>
        <th>Nama Produk</th>
        <th>Harga Cash</th>
        <th>Harga Tempo</th>
        <th>Harga Beli</th>
        @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
        <th>Aksi</th>
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
            <button type="submit" class="edit-btn">Edit</button>
          </form>
          <form method="POST" action="{{ route('destroy_product', ['idProduk' => $item->idProduk]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn">Delete</button>
          </form>
        </td>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>

  @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
  <h2>Produk Terhapus</h2>
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Gambar</th>
        <th>Nama Produk</th>
        <th>Harga Cash</th>
        <th>Harga Tempo</th>
        <th>Harga Beli</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($produkTerhapus as $item)
      <tr>
        <td><img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaProduk }}" class="produk-img"></td>
        <td>{{ $item->namaProduk }}</td>
        <td class="harga-cash">Rp. {{ number_format($item->hargaCash, 0, ',', '.') }}</td>
        <td class="harga-tempo">Rp. {{ number_format($item->hargaTempo, 0, ',', '.') }}</td>
        <td class="harga-beli">Rp. {{ number_format($item->hargaBeli, 0, ',', '.') }}</td>
        <td class="aksi-btn restore-btn-container">
          <form method="POST" action="{{ route('restore_product', ['idProduk' => $item->idProduk]) }}">
            @csrf
            @method('PUT')
            <button type="submit" class="restore-btn">Restore</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif
</div>

@endsection