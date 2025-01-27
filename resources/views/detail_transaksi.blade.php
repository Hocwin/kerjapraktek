@extends('layout.master')
@section('title', 'Detail Transaksi')

<style>
    .detail-container {
        padding-top: 100px;
        padding-bottom: 50px;
    }

    .detail-container h1 {
        font-size: 2rem;
        color: #343a40;
        margin-bottom: 30px;
    }

    .table-responsive {
        max-height: 300px;
        overflow-y: auto;
    }

    .table th,
    .table td {
        vertical-align: middle;
        text-align: center;
    }

    .btn {
        margin: 0 5px;
    }

    /* Responsivitas */
    @media (max-width: 768px) {
        .detail-container h1 {
            font-size: 1.5rem;
        }

        .btn {
            width: 100%;
            margin: 5px 0;
        }
    }
</style>

@section('content')
<div class="container detail-container">
    <h1 class="mb-4 text-center">Detail Transaksi</h1>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @elseif (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="mb-4">
        <p><strong>ID Transaksi:</strong> {{ $transaksi->idTransaksi }}</p>
        <p><strong>Nama Toko:</strong> {{ $toko->namaToko }}</p>
        <p><strong>Tanggal Transaksi:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggalTransaksi)->format('Y-m-d H:i') }}</p>
        <p><strong>Status:</strong> {{ $transaksi->status }}</p>
    </div>

    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
    <div class="mb-3 text-left">
        <a href="{{ route('add_detail_transaksi', ['idTransaksi' => $transaksi->idTransaksi]) }}" class="btn btn-primary">Tambah Detail</a>
    </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total Harga</th>
                    <th>Nama Gudang</th>
                    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
                    <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($detailTransaksi as $detail)
                @if ($detail->produk && !$detail->produk->trashed())
                <tr>
                    <td>{{ $detail->produk->namaProduk }}</td>
                    <td>{{ $detail->jumlahProduk }}</td>
                    <td>
                        {{ number_format($transaksi->tipePembayaran == 'cash' ? $detail->hargaC : $detail->hargaT, 0, ',', '.') }}
                    </td>
                    <td>
                        {{ number_format($detail->jumlahProduk * ($transaksi->tipePembayaran == 'cash' ? $detail->hargaC : $detail->hargaT), 0, ',', '.') }}
                    </td>
                    <td>{{ $detail->namaGudang }}</td>
                    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
                    <td>
                        <a href="{{ route('edit_detail_transaksi', ['idDetailTransaksi' => $detail->idDetailTransaksi]) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('destroy_detail_transaksi', ['idDetailTransaksi' => $detail->idDetailTransaksi]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus detail ini?')">Hapus</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="{{ Auth::check() && Auth::user()->rolePengguna == 'admin' ? 6 : 5 }}" class="text-center">Tidak ada detail transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="text-left mt-4">
        <a href="{{ route('transaksi') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
