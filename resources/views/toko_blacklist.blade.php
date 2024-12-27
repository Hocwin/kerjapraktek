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

    <!-- Deleted Toko -->
    <h2 class="section-title">Toko Tidak Aktif</h2>
    @if ($tokoTerhapus->isEmpty())
    <p class="empty-message">Tidak ada toko terhapus.</p>
    @else
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama Toko</th>
                <th>Lokasi</th>
                <th>Nomor Telepon</th>
                <th>Jam Operasional</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tokoTerhapus as $toko)
            <tr>
                <td><img src="{{ asset('storage/images/' . $toko->imageAsset) }}" alt="{{ $toko->namaToko }}" class="item-img"></td>
                <td>{{ $toko->namaToko }}</td>
                <td>{{ $toko->alamatToko }}</td>
                <td>{{ $toko->nomorTelepon }}</td>
                <td>{{ $toko->jamOperasional }}</td>
                <td>
                    <form method="POST" action="{{ route('restore_toko', ['idToko' => $toko->idToko]) }}">
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
        <a href="{{route('toko')}}">Kembali</a>
    </div>
</div>
@endsection
