@extends('layout.master')
@section('title', 'Halaman Toko')

<style>
  .toko-container {
    padding-top: 125px;
  }

  .toko-img {
    width: 100px;
    height: auto;
    object-fit: contain;
  }

  .aksi-btn {
    display: column;
    flex-direction: column;
    gap: 5px;
    justify-content: center;
    align-items: center;
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
    .toko-img {
      width: 80px;
    }

    .restore-btn-container {
      gap: 5px;
    }
  }
</style>

@section('content')

<div class="container toko-container">
  <h2>Toko Aktif</h2>
  <div class="btn-container">
    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
    <form method="GET" action="{{ route('add_toko') }}">
      @csrf
      <button type="submit" class="btn btn-primary">Tambah Toko</button>
    </form>
    <form method="GET" action="{{ route('toko_blacklist') }}">
        @csrf
        <input type="hidden" name="page" value="toko">
        <button type="submit" class="btn btn-danger">Blacklist</button>
    </form>
    @endif
  </div>
  <div class="table-responsive" style="height: 400px; overflow-y: scroll;">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Gambar</th>
        <th>Nama Toko</th>
        <th>Lokasi</th>
        <th>Nomor Telepon</th>
        <th>Jam Operasional</th>
        <th>Sopir</th>
        <th class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($tokoAktif as $item)
      <tr>
        <td><img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaToko }}" class="toko-img"></td>
        <td>{{ $item->namaToko }}</td>
        <td>{{ $item->alamatToko }}</td>
        <td>{{ $item->nomorTelepon }}</td>
        <td>{{ $item->jamOperasional }}</td>
        <td>{{ $item->namaSopir }}</td>
        <td class="aksi-btn">
          <form method="GET" action="{{ route('detail_toko', ['idToko' => $item->idToko]) }}">
            @csrf
            <button type="submit" class="btn btn-warning">Detail</button>
          </form>
          @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
          <form method="GET" action="{{ route('edit_toko', ['idToko' => $item->idToko]) }}">
            @csrf
            <button type="submit" class="btn btn-primary">Edit</button>
          </form>
          <form method="POST" action="{{ route('delete_toko', ['idToko' => $item->idToko]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Blacklist</button>
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
