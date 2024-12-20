@extends('layout.master')
@section('title', 'Halaman Transaksi')

<style>
  .transaksi-container {
    padding-top: 150px; /* Padding atas untuk memberikan ruang antara header dan tabel */
    padding-bottom: 70px; /* Ensure space at the bottom to prevent overlap with the button */
  }

  .aksi-btn {
    display: flex;
    flex-direction: flex; /* Stack buttons vertically */
    justify-content: center; /* Center buttons horizontally */
    align-items: center;
    gap: 5px; /* Jarak antara tombol */
  }

  .detail-btn, .delete-btn, .add-btn, .back-btn {
    background-color: transparent;
    border: none;
    color: #007bff;
    padding: 6px 12px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: underline;
  }

  .detail-btn:hover, .delete-btn:hover, .add-btn:hover, .back-btn:hover {
    color: #0056b3;
    text-decoration: none;
  }

  .delete-btn {
    color: #dc3545; /* Warna merah untuk tombol Delete */
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

  /* Memastikan footer tetap di bawah */
  footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    text-align: center;
    padding: 10px;
    background-color: #f8f9fa;
  }

  /* Center the action buttons vertically under the column header */
  .table td {
    vertical-align: middle; /* Center buttons vertically */
  }

  .table th, .table td {
    text-align: center; /* Center text in table cells */
  }

  /* Optional: To add space between the button and table rows */
  .table td.aksi-btn {
    padding-top: 10px; /* Add some space between button and row */
  }

  /* Position the "Back to Toko" button below the table */
  .back-btn-container {
    text-align: left;
    margin-top: 20px; /* Space above the button */
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
  <!-- Menempatkan tombol "Add" di bawah header tabel -->
  <div class="add-btn-container">
    <!-- Tombol Add (Only visible to admins) -->
    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
    <form method="GET" action="{{ route('add_transaksi') }}">
      @csrf
      <button type="submit" class="add-btn">Add Transaksi</button>
    </form>
    @endif
  </div>

  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Nama Toko</th>
        <th>Tipe Pembayaran</th>
        <th>Status</th>
        <th>Tanggal Transaksi</th>
        @if (Auth::check() && Auth::user()->rolePengguna == 'admin') 
          <th>Aksi</th>
        @endif
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
              <!-- Detail Button -->
              <form method="GET" action="{{ route('detail_transaksi', ['idTransaksi' => $item->idTransaksi]) }}">
                @csrf
                <button type="submit" class="detail-btn">Detail</button>
              </form>
              @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
              <!-- Delete Button -->
              <form method="POST" action="{{ route('proses_deletetransaksi', ['idTransaksi' => $item->idTransaksi]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
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

  <!-- Tombol Back ke Halaman Toko -->
  <div class="back-btn-container">
    <form method="GET" action="{{ route('toko') }}"> <!-- Adjust the route to point to your Toko page -->
      @csrf
      <button type="submit" class="back-btn">Kembali</button>
    </form>
  </div>

</div>

@endsection
