@extends('layout.master')
@section('title', 'Halaman Gudang')

<style>
  .gudang-container {
    padding-top: 150px;
    /* Jarak antara header dan konten */
  }

  .gudang-img {
    width: 100px;
    height: auto;
    object-fit: contain;
    /* Menjaga proporsi gambar */
  }

  .hidden {
    display: none;
  }

  table th,
  table td {
    vertical-align: middle;
    text-align: center;
    padding: 10px;
    /* Add padding to table cells for better spacing */
  }

  .aksi-btn {
    text-align: center;
  }

  .edit-btn,
  .add-btn,
  .restore-btn {
    background-color: transparent;
    border: none;
    color: #007bff;
    padding: 6px 12px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: underline;
  }

  .edit-btn:hover,
  .add-btn:hover,
  .restore-btn:hover {
    color: #0056b3;
    text-decoration: none;
  }

  .edit-btn:focus,
  .add-btn:focus,
  .restore-btn:focus {
    outline: none;
  }

  .add-btn-container {
    text-align: right;
    margin-bottom: 20px;
  }

  .stok-table {
    background-color: transparent !important;
    margin: 0 auto;
    text-align: center;
    border: none;
    /* Keep the table look consistent */
  }

  .stok-table th,
  .stok-table td {
    padding: 4px 8px;
    border: none !important;
    background-color: transparent !important;
  }

  .stok-empty {
    font-style: italic;
    color: #6c757d;
  }

  .table-container {
    margin-bottom: 30px;
  }

  .table-heading {
    margin-top: 20px;
    font-weight: bold;
    font-size: 1.5em;
    text-align: center;
    color: #007bff;
  }

  /* Add styling for the 'Gudang Terhapus' table */
  .table-deleted {
    margin-top: 30px;
    background-color: #f8f9fa;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  .table-deleted thead {
    background-color: #007bff;
    color: white;
  }
</style>

@section('content')

<div class="container gudang-container">
  <div class="add-btn-container">
    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
    <form method="GET" action="{{ route('add_gudang') }}">
      @csrf
      <button type="submit" class="add-btn">Tambah Gudang</button>
    </form>
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
          <th>Stok</th>
          @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
          <th>Aksi</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach ($gudangAktif as $item)
        <tr class="gudang" data-category="{{ $item->idKategori }}">
          <td>
            <img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaGudang }}" class="gudang-img">
          </td>
          <td>{{ $item->namaGudang }}</td>
          <td>{{ $item->lokasi }}</td>
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
                @if ($stok->produk) <!-- Periksa apakah produk tidak null -->
                <tr>
                  <td>{{ $stok->produk->namaProduk }}</td>
                  <td>{{ $stok->stok }} sak</td>
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
            @endif
          </td>
          @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
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

  <!-- Gudang Terhapus Table -->
  @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
  <h2>Gudang Terhapus</h2>
  <div class="table-container">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Gambar</th>
          <th>Nama Gudang</th>
          <th>Lokasi</th>
          <th>Stok</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($gudangTerhapus as $item)
        <tr class="gudang" data-category="{{ $item->idKategori }}">
          <td><img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaGudang }}" class="toko-img"></td>
          <td>{{ $item->namaGudang }}</td>
          <td>{{ $item->lokasi }}</td>
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
                @if ($stok->produk)
                <tr>
                  <td>{{ $stok->produk->namaProduk }}</td>
                  <td>{{ $stok->stok }} sak</td>
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
            @endif
          </td>
          <td class="aksi-btn restore-btn-container">
            <form method="POST" action="{{ route('restore_gudang', ['idGudang' => $item->idGudang]) }}">
              @csrf
              @method('PUT')
              <button type="submit" class="restore-btn">Restore</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
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