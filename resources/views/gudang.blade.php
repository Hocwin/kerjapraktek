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

  .stok-list {
    padding-left: 0;
    margin: 0;
  }

  .stok-list li {
    list-style-type: none;
    margin-bottom: 5px;
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
        @if (Auth::user() && Auth::user()->rolePengguna == 'admin')  <!-- Tampilkan kolom aksi hanya untuk admin -->
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
          <ul class="stok-list">
  @if($item->stokPerGudang->isEmpty())
    <li>Tidak ada data stok</li>
  @else
    @foreach ($item->stokPerGudang as $stok)
      <li>{{ $stok->produk->namaProduk }}: {{ $stok->stok }} sak</li>
    @endforeach
  @endif
</ul>
          </td>
          @if (Auth::user() && Auth::user()->rolePengguna == 'admin')  <!-- Kolom aksi hanya untuk admin -->
            <td class="aksi-btn">
              <form method="GET" action="{{ route('edit-gudang', ['idGudang' => $item->idGudang]) }}">
                @csrf
                <button type="submit" class="edit-btn">Edit</button>
              </form>

              <form action="{{ route('destroy_gudang', ['idGudang' => $item->idGudang]) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE') <!-- HTTP DELETE method -->
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
