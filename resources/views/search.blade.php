@extends('layout.master')

<style>
    .stok-table {
        background-color: transparent !important;
        /* Set background to fully transparent */
        margin: 0 auto;
        /* Center the table */
        text-align: center;
        /* Center the text */
        border: none;
        /* Remove borders */
    }

    .stok-table th,
    .stok-table td {
        padding: 4px 8px;
        /* Keep padding for content spacing */
        border: none !important;
        /* Remove borders in table headers and cells */
        background-color: transparent !important;
        /* Make table cell background transparent */
    }

    .stok-empty {
        font-style: italic;
        color: #6c757d;
        /* Gray color for "No stock data" text */
    }
</style>

@section('content')
<div class="container mt-5" style="padding-top: 80px;"> {{-- Add padding for spacing from the header --}}

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

    {{-- Message if no results found --}}
    @if($toko->isEmpty() && $produkAktif->isEmpty() && $gudang->isEmpty())
    <div class="alert alert-warning mt-5 text-center">
        <strong>Maaf!</strong> Tidak ada hasil pencarian yang sesuai untuk "{{ $query }}".
    </div>
    @endif
</div>
@endsection