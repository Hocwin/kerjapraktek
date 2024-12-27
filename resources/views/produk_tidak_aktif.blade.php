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
     <!-- Deleted Produk -->
    <h2 class="section-title">Produk Tidak Aktif</h2>
    @if ($produkTerhapus->isEmpty())
    <p class="empty-message">Tidak ada produk terhapus.</p>
    @else
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
            @foreach ($produkTerhapus as $produk)
            <tr>
                <td><img src="{{ asset('storage/images/' . $produk->imageAsset) }}" alt="{{ $produk->namaProduk }}" class="item-img"></td>
                <td>{{ $produk->namaProduk }}</td>
                <td>Rp. {{ number_format($produk->hargaCash, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($produk->hargaTempo, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($produk->hargaBeli, 0, ',', '.') }}</td>
                <td>
                    <form method="POST" action="{{ route('restore_product', ['idProduk' => $produk->idProduk]) }}">
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

    <div class="btn-action">
        <a href="{{route('produk')}}">Kembali</a>
    </div>
</div>
@endsection
