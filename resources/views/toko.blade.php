@extends('layout.master')
@section('title', 'Halaman Toko')

<style>
  /* Set the body and container to use flexbox layout to align the footer */
  body {
    display: flex;
    flex-direction: column;
    height: 100vh; /* Ensure the body takes up full viewport height */
    margin: 0; /* Remove default margin */
  }

  /* Main content container, allowing it to grow and take up available space */
  .toko-container {
    flex: 1;
    padding-top: 125px;
    overflow: auto; /* To avoid content overflow issues */
  }

  /* Styling tabel */
  table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
  }

  table th, table td {
    vertical-align: middle;
    padding: 10px;
  }

  thead.thead-dark th {
    background-color: #f8f9fa;
    color: #000;
    font-weight: bold;
  }

  .toko-img {
    width: 100px;
    height: 100px;
    object-fit: contain;
    border-radius: 10px;
    transition: transform 0.3s ease;
  }

  .toko-img:hover {
    transform: scale(1.1);
  }

  .aksi-btn {
    text-align: center; /* Align buttons horizontally */
    justify-content: center;
  }

  .aksi-btn form {
    margin: 0;
  }

  .edit-btn,
  .delete-btn, .detail-btn {
    background-color: transparent;
    border: none;
    font-weight: bold;
    cursor: pointer;
    text-decoration: underline;
    padding: 6px 12px;
  }

  .edit-btn {
    color: #007bff;
  }

  .edit-btn:hover {
    color: #0056b3;
    text-decoration: none;
  }

  .delete-btn {
    color: #dc3545;
  }

  .delete-btn:hover {
    color: #b02a37;
    text-decoration: none;
  }

  .add-btn-container {
    text-align: right;
    margin-bottom: 20px;
  }

  .add-btn {
    background-color: transparent;
    border: none;
    color: #007bff;
    font-weight: bold;
    text-decoration: underline;
    cursor: pointer;
  }

  .add-btn:hover {
    color: #0056b3;
    text-decoration: none;
  }

  /* Styling for the footer */
  footer {
    width: 100%;
    background-color: #f1f1f1;
    text-align: center;
    padding: 10px;
    position: relative;
    bottom: 0;
    left: 0;
  }

</style>

@section('content')
<div class="container toko-container">

  <!-- Tombol Add -->
  <div class="add-btn-container">
    <!-- Tombol Add (Only visible to admins) -->
    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
    <div class="add-btn-container">
      <form method="GET" action="{{ route('add_toko') }}">
        @csrf
        <button type="submit" class="add-btn">Add</button>
      </form>
    </div>
    @endif
  </div>

  <!-- Tabel Daftar Toko -->
  <table class="table table-striped table-hover">
    <thead class="thead-dark">
      <tr>
        <th>Gambar</th>
        <th>Nama Toko</th>
        <th>Lokasi</th>
        <th>Nomor Telepon</th>
        <th>Jam Operasional</th>
        <th>Nama Sopir</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($toko as $item)
        <tr>
          <td>
            <img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaToko }}" class="toko-img">
          </td>
          <td>{{ $item->namaToko }}</td>
          <td>{{ $item->alamatToko }}</td>
          <td>{{ $item->nomorTelepon }}</td>
          <td>{{ $item->jamOperasional }}</td>
          <td>{{ $item->namaSopir }}</td>
          <td class="aksi-btn">
              <!-- Tombol Detail -->
              <form method="GET" action="{{ route('detail_toko', ['idToko' => $item->idToko]) }}">
                @csrf
                <button type="submit" class="detail-btn">Detail</button>
              </form>

              @if (Auth::user()->rolePengguna == 'admin')
              <!-- Tombol Edit (Only for Admin) -->
              <form method="GET" action="{{ route('edit_toko', ['idToko' => $item->idToko]) }}">
                @csrf
                <button type="submit" class="edit-btn">Edit</button>
              </form>

              <!-- Tombol Delete (Only for Admin) -->
              <form method="POST" action="{{ route('delete_toko', ['idToko' => $item->idToko]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn">Delete</button>
              </form>
              @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
