@extends('layout.master')

<style>
    .stok-table {
        background-color: transparent !important;
        margin: 0 auto;
        text-align: center;
        border: none;
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
</style>

@section('content')
<div class="container mt-5" style="padding-top: 80px;"> {{-- Add padding for spacing from the header --}}
    <div class="table-responsive" style="height: 500px; overflow-y: scroll;">
        {{-- Store Search Results --}}
        @if($toko->isNotEmpty())
        <h3 class="mt-5 text-center">Toko</h3> {{-- Centered heading --}}
        <table class="table table-striped table-hover border mt-3 text-center"> {{-- Center table text --}}
            <thead class="thead-dark">
                <tr>
                    <th>Gambar</th>
                    <th>Nama Toko</th>
                    <th>Lokasi</th>
                    <th>Nomor Telepon</th>
                    <th>Jam Operasional</th>
                    <th>Nama Sopir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($toko as $item)
                <tr>
                    <td>
                        <img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaToko }}" class="img-fluid rounded" style="width: 100px; height: 100px;">
                    </td>
                    <td>{{ $item->namaToko }}</td>
                    <td>{{ $item->alamatToko }}</td>
                    <td>{{ $item->nomorTelepon }}</td>
                    <td>{{ $item->jamOperasional }}</td>
                    <td>{{ $item->namaSopir }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- Product Search Results --}}
        @if($produkAktif->isNotEmpty())
        <h3 class="mt-5 text-center">Produk</h3>
        <table class="table table-striped table-hover border mt-3 text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Harga Cash</th>
                    <th>Harga Tempo</th>
                    <th>Harga Beli</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produkAktif as $item)
                <tr>
                    <td>
                        <img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaProduk }}" class="img-fluid rounded" style="width: 100px; height: 100px;">
                    </td>
                    <td>{{ $item->namaProduk }}</td>
                    <td>{{ $item->hargaCash }}</td>
                    <td>{{ $item->hargaTempo }}</td>
                    <td>{{ $item->hargaBeli }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- Warehouse Search Results --}}
        @if($gudang->isNotEmpty())
        <h3 class="mt-5 text-center">Gudang</h3>
        <table class="table table-striped table-hover border mt-3 text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Gambar</th>
                    <th>Nama Gudang</th>
                    <th>Lokasi</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gudang as $item)
                <tr>
                    <td>
                        <img src="{{ asset('storage/images/' . $item->imageAsset) }}" alt="{{ $item->namaGudang }}" class="img-fluid rounded" style="width: 100px; height: 100px;">
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
                                <tr>
                                    <td>{{ $stok->produk->namaProduk }}</td>
                                    <td>{{ $stok->stok }} sak</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($transaksi->isNotEmpty())
        <h3 class="mt-5 text-center">Transaksi</h3>
        <table class="table table-striped table-hover border mt-3 text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Nama Toko</th>
                    <th>Tipe Pembayaran</th>
                    <th>Status</th>
                    <th>Tanggal Transaksi</th>
                    <th class="text-center">Aksi</th>
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
                    <td>{{ \Carbon\Carbon::parse($item->tanggalTransaksi)->format('Y-m-d H:i') }}</td>
                    <td class="aksi-btn">
                        <form method="GET" action="{{ route('detail_transaksi', ['idTransaksi' => $item->idTransaksi]) }}" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-warning">Detail</button>
                        </form>
                        @if (Auth::check() && Auth::user()->rolePengguna == 'admin')
                        <form method="GET" action="{{ route('edit_transaksi', ['idTransaksi' => $item->idTransaksi]) }}" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- Message if no results found --}}
        @if($toko->isEmpty() && $produkAktif->isEmpty() && $gudang->isEmpty())
        <div class="alert alert-warning mt-5 text-center">
            <strong>Maaf!</strong> Tidak ada hasil pencarian yang sesuai untuk "{{ $query }}".
        </div>
        @endif
    </div>
</div>
@endsection