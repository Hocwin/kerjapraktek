@extends('layout.master')
@section('title', 'Detail Transaksi')

@section('content')
<div class="container" style="padding-top: 100px;">
    <h1 class="mb-4 text-center">Detail Transaksi</h1>
    
    <!-- Display Flash Message -->
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
        <p><strong>Tanggal Transaksi:</strong> {{ $transaksi->tanggalTransaksi }}</p>
        <p><strong>Status:</strong> {{ $transaksi->status }}</p>
    </div>

    <!-- Tombol untuk menambahkan detail transaksi (Only visible to admins) -->
    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
    <div class="mb-3">
        <a href="{{ route('add_detail_transaksi', ['idTransaksi' => $transaksi->idTransaksi]) }}" class="btn btn-primary">Tambah Detail</a>
    </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total Harga</th>
                    <th>Nama Gudang</th>
                    <!-- Aksi hanya terlihat oleh admin -->
                    @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
                    <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($detailTransaksi as $detail)
                    <tr>
                        <td>{{ $detail->produk->namaProduk }}</td>
                        <td>{{ $detail->jumlahProduk }}</td>
                        <td>
                            @if ($transaksi->tipePembayaran == 'cash')
                                {{ number_format($detail->produk->hargaCash, 0, ',', '.') }}
                            @else
                                {{ number_format($detail->produk->hargaTempo, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>
                            @if ($transaksi->tipePembayaran == 'cash')
                                {{ number_format($detail->jumlahProduk * $detail->produk->hargaCash, 0, ',', '.') }}
                            @else
                                {{ number_format($detail->jumlahProduk * $detail->produk->hargaTempo, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>{{ $detail->namaGudang }}</td>
                        @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
                        <td>
                            <!-- Tombol Edit (Only for admins) -->
                            <a href="{{ route('edit_detail_transaksi', ['idDetailTransaksi' => $detail->idDetailTransaksi]) }}" class="btn btn-warning btn-sm">Edit</a>
                            <!-- Form untuk Delete (Only for admins) -->
                            <form action="{{ route('destroy_detail_transaksi', ['idDetailTransaksi' => $detail->idDetailTransaksi]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus detail ini?')">Hapus</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada detail transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tombol Kembali -->
    <a href="{{ route('transaksi') }}" class="btn btn-secondary btn-sm d-inline-block mx-auto">Kembali</a>
</div>
@endsection
