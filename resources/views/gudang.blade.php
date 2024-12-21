@extends('layout.master')
@section('title', 'Halaman Gudang')

<style>
  .gudang-container {
    padding-top: 150px; /* Memberikan jarak antara header dan tabel */
  }

  .gudang-img {
    width: 100px;
    height: auto;
    object-fit: contain; /* Menjaga proporsi gambar */
  }

  .lokasi-gudang {
    color: #007bff; /* Warna teks biru */
    font-weight: bold;
  }

  .hidden {
    display: none;
  }

  table th, table td {
    vertical-align: middle; /* Menyelaraskan semua konten di tengah secara vertikal */
    text-align: center; /* Menyelaraskan konten ke tengah secara horizontal */
  }

  .aksi-btn {
    text-align: center; /* Menyelaraskan tombol secara horizontal */
  }

  .edit-btn {
    background-color: transparent;
    border: none;
    color: #007bff;
    padding: 6px 12px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: underline;
  }

  .add-btn-container {
    text-align: right;
    margin-bottom: 20px;
  }

  .edit-btn:hover {
    color: #0056b3;
    text-decoration: none;
  }

  .edit-btn:focus {
    outline: none;
  }

  .add-btn {
    background-color: transparent;
    border: none;
    color: #007bff;
    padding: 6px 12px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: underline;
  }

  .stok-table {
  background-color: transparent !important; /* Set background to fully transparent */
  margin: 0 auto; /* Center the table */
  text-align: center; /* Center the text */
  border: none; /* Remove borders */
}

.stok-table th, .stok-table td {
  padding: 4px 8px; /* Keep padding for content spacing */
  border: none !important; /* Remove borders in table headers and cells */
  background-color: transparent !important; /* Make table cell background transparent */
}

.stok-empty {
  font-style: italic;
  color: #6c757d; /* Gray color for "No stock data" text */
}
</style>

@section('content')

<div class="container gudang-container">
  <div class="add-btn-container">
    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
    <form method="GET" action="{{ route('add_gudang') }}">
      @csrf
      <button type="submit" class="add-btn">Add Gudang</button>
    </form>
    @endif
  </div>

  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Gambar</th>
        <th>Nama Gudang</th>
        <th>Lokasi</th>
        <th>Stok</th>
        @if (Auth::user() && Auth::user()->rolePengguna == 'admin')
          <th>Aksi</th>
        @endif
      </tr>
    </thead>
    <tbody>
      @foreach ($gudang as $item)
        <tr class="gudang" data-category="{{ $item->idKategori }}">
          <td>
            <img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaGudang }}" class="gudang-img">
          </td>
          <td>{{ $item->namaGudang }}</td>
          <td class="lokasi-gudang">{{ $item->lokasi }}</td>
          <td>
            @if($item->stokPerGudang->isEmpty())
              <span class="stok-empty">Tidak ada data stok</span>
            @else
            <table class="table table-borderless table-sm stok-table">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Stok</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($item->stokPerGudang as $stok)
                <tr>
                  <td>{{ $stok->produk->namaProduk }}</td>
                  <td>{{ $stok->stok }} sak</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @endif
          </td>
          @if (Auth::user() && Auth::user()->rolePengguna == 'admin')
            <td class="aksi-btn">
              <form method="GET" action="{{ route('edit-gudang', ['idGudang' => $item->idGudang]) }}">
                @csrf
                <button type="submit" class="edit-btn">Edit</button>
              </form>

              <form action="{{ route('destroy_gudang', ['idGudang' => $item->idGudang]) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus gudang ini?')">
                    Hapus
                </button>
              </form>
            </td>
          @endif
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<script>
  function filterGudang() {
    const categorySelect = document.getElementById('kategoriSelect');
    const selectedCategory = categorySelect.value;
    const rows = document.querySelectorAll('.gudang');

    rows.forEach(row => {
      const category = row.getAttribute('data-category');
      if (selectedCategory === '0' || category === selectedCategory) {
        row.classList.remove('hidden');
      } else {
        row.classList.add('hidden');
      }
    });
  }
</script>

@endsection
