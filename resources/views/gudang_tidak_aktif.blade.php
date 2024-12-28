@extends('layout.master')
@section('title', 'Halaman Trash')

<style>
    .trash-container {
        padding-top: 125px;
    }

    .section-title {
        margin-top: 50px;
        margin-bottom: 20px;
        color: #6c757d;
    }

    .item-img {
        width: 100px;
        height: auto;
        object-fit: contain;
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

    table th,
    table td {
        text-align: center;
        vertical-align: middle;
    }

    .restore-btn {
        color: #28a745;
    }

    .restore-btn:hover {
        color: #218838;
    }

    .empty-message {
        font-style: italic;
        color: #6c757d;
    }
</style>

@section('content')
<div class="container trash-container">
    <!-- Deleted Gudang -->
    <h2>Gudang Terhapus</h2>
    @if ($gudangTerhapus->isEmpty())
    <p class="empty-message">Tidak ada gudang terhapus.</p>
    @else
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
            @foreach ($gudangTerhapus as $gudang)
            <tr>
                <td><img src="{{ asset('storage/images/' . $gudang->imageAsset) }}" alt="{{ $gudang->namaGudang }}" class="item-img"></td>
                <td>{{ $gudang->namaGudang }}</td>
                <td>{{ $gudang->lokasi }}</td>
                <td>
                    @if($gudang->stokPerGudang->isEmpty())
                    <span class="empty-message">Tidak ada data stok</span>
                    @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gudang->stokPerGudang as $stok)
                            <tr>
                                <td>{{ $stok->produk->namaProduk }}</td>
                                <td>{{ $stok->stok }} sak</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{ route('restore_gudang', ['idGudang' => $gudang->idGudang]) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-action restore-btn">Restore</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    <a href="{{route('gudang')}}" class="btn btn-secondary d-inline-block mx-auto">Kembali</a>
</div>
@endsection