<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performa Bisnis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f9;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        h2 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .chart-container {
            margin-bottom: 40px;
            text-align: center;
        }

        canvas {
            max-width: 100%;
            height: 400px;
            margin: 0 auto;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
        }

        .filter-container {
            text-align: center;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            canvas {
                height: 300px;
            }
        }

        .btn-back {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        form {
            display: flex;
            justify-content: center;
            gap: 15px;
            align-items: center;
        }

        label {
            font-size: 1rem;
        }

        select,
        button {
            padding: 5px 10px;
            font-size: 1rem;
        }
    </style>
</head>

<body>
    @if(auth()->user()->rolePengguna == 'admin' || auth()->user()->rolePengguna == 'manager')
    <h1>Performa Bisnis</h1>

    <!-- Form untuk memilih bulan, tahun, dan jenis tampilan -->
    <div class="filter-container">
        <form method="GET" action="{{ route('performa_bisnis') }}">
            <label for="view_type">Tampilan:</label>
            <select name="view_type" id="view_type">
                <option value="monthly" {{ $viewType == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="yearly" {{ $viewType == 'yearly' ? 'selected' : '' }}>Tahunan</option>
            </select>

            <label for="bulan">Bulan:</label>
            <select name="bulan" id="bulan" {{ $viewType == 'yearly' ? 'disabled' : '' }}>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                    </option>
                    @endfor
            </select>

            <label for="tahun">Tahun:</label>
            <select name="tahun" id="tahun">
                @for ($i = 2020; $i <= Carbon\Carbon::now()->year + 1; $i++)
                    <option value="{{ $i }}" {{ $i == $tahun ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
            </select>

            <button type="submit">Terapkan</button>
        </form>
    </div>

    <div class="container">
        <!-- Grafik Keuntungan Per Toko -->
        <div class="chart-container">
            <h2>Keuntungan Per Toko</h2>
            <canvas id="keuntunganChart"></canvas>
        </div>

        <!-- Grafik Produk Terbanyak Terjual -->
        <div class="chart-container">
            <h2>Produk Terbanyak Terjual</h2>
            <canvas id="produkTerlarisChart"></canvas>
        </div>

        <!-- Grafik Toko dengan Pembelian Terbanyak -->
        <div class="chart-container">
            <h2>Toko dengan Pembelian Terbanyak</h2>
            <canvas id="tokoBanyakPembelianChart"></canvas>
        </div>

        <!-- Grafik Produk Terbanyak Terjual Per Toko -->
        <div class="chart-container">
            <h2>Produk Terbanyak Terjual Per Toko</h2>
            <canvas id="produkTerlarisPerTokoChart"></canvas>
        </div>

        <!-- Grafik Gudang dengan Pengeluaran Terbesar -->
        <div class="chart-container">
            <h2>Gudang dengan Pengeluaran Terbesar</h2>
            <canvas id="gudangPengeluaranTerbesarChart"></canvas>
        </div>
    </div>

    <!-- Tombol Back ke Halaman Produk -->
    <div class="filter-container">
        <a href="{{ route('produk') }}" class="btn-back">Kembali ke Halaman Produk</a>
    </div>

    <script>
        // Data dari controller
        var keuntunganData = @json($keuntungan);
        var produkTerlarisData = @json($produkTerlaris);
        var tokoBanyakPembelianData = @json($tokoBanyakPembelian);
        var produkTerlarisPerTokoData = @json($produkTerlarisPerToko);
        var gudangPengeluaranTerbesarData = @json($gudangPengeluaranTerbesar);

        // Fungsi untuk menangani data kosong
        function handleEmptyData(data, canvasId, message) {
            if (!data || data.length === 0) {
                document.getElementById(canvasId).parentNode.innerHTML = `<p>${message}</p>`;
                return false;
            }
            return true;
        }

        // Grafik Keuntungan Per Toko
        if (handleEmptyData(keuntunganData, 'keuntunganChart', 'Data keuntungan tidak tersedia.')) {
            new Chart(document.getElementById('keuntunganChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: keuntunganData.map(item => item.toko.namaToko),
                    datasets: [{
                        label: 'Total Keuntungan',
                        data: keuntunganData.map(item => item.totalKeuntungan),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y' // Membuat grafik horizontal
                }
            });
        }

        // Grafik Produk Terbanyak Terjual
        if (handleEmptyData(produkTerlarisData, 'produkTerlarisChart', 'Data produk tidak tersedia.')) {
            new Chart(document.getElementById('produkTerlarisChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: produkTerlarisData.map(item => item.produk.namaProduk),
                    datasets: [{
                        data: produkTerlarisData.map(item => item.totalTerjual),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                    }]
                }
            });
        }

        // Grafik Toko dengan Pembelian Terbanyak
        if (handleEmptyData(tokoBanyakPembelianData, 'tokoBanyakPembelianChart', 'Data toko tidak tersedia.')) {
            new Chart(document.getElementById('tokoBanyakPembelianChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: tokoBanyakPembelianData.map(item => item.namaToko),
                    datasets: [{
                        label: 'Jumlah Pembelian',
                        data: tokoBanyakPembelianData.map(item => item.totalPembelian),
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y' // Membuat grafik horizontal
                }
            });
        }

        // Grafik Produk Terbanyak Terjual Per Toko
        if (handleEmptyData(produkTerlarisPerTokoData, 'produkTerlarisPerTokoChart', 'Data produk per toko tidak tersedia.')) {
            new Chart(document.getElementById('produkTerlarisPerTokoChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: produkTerlarisPerTokoData.map(item => `${item.namaToko} - ${item.namaProduk}`),
                    datasets: [{
                        label: 'Total Terjual',
                        data: produkTerlarisPerTokoData.map(item => item.totalTerjual),
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y' // Membuat grafik horizontal
                }
            });
        }

        // Grafik Gudang dengan Pengeluaran Terbesar
        if (handleEmptyData(gudangPengeluaranTerbesarData, 'gudangPengeluaranTerbesarChart', 'Data gudang tidak tersedia.')) {
            new Chart(document.getElementById('gudangPengeluaranTerbesarChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: gudangPengeluaranTerbesarData.map(item => item.namaGudang),
                    datasets: [{
                        label: 'Total Pengeluaran',
                        data: gudangPengeluaranTerbesarData.map(item => item.totalStokKeluar),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Warna bar
                        borderColor: 'rgba(255, 99, 132, 1)', // Warna border
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Gudang'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Pengeluaran'
                            }
                        }
                    }
                }
            });
        }
    </script>

    @else
    <h1>Anda tidak memiliki akses untuk melihat performa bisnis.</h1>
    @endif
</body>

</html>