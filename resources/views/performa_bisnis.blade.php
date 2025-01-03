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

        /* Responsif untuk ukuran layar lebih kecil */
        @media (max-width: 768px) {
            canvas {
                height: 300px;
            }
        }

        /* Button Style */
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

        /* Style untuk form */
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
    </div>

    <!-- Tombol Back ke Halaman Produk -->
    <div class="filter-container">
        <a href="{{ route('produk') }}" class="btn-back">Kembali ke Halaman Produk</a>
    </div>

    <script>
        var keuntunganData = @json($keuntungan);
        var produkTerlarisData = @json($produkTerlaris);
        var tokoBanyakPembelianData = @json($tokoBanyakPembelian);

        // Grafik Keuntungan Per Toko
        var ctx1 = document.getElementById('keuntunganChart').getContext('2d');
        new Chart(ctx1, {
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
            }
        });

        // Grafik Produk Terbanyak Terjual
        var ctx2 = document.getElementById('produkTerlarisChart').getContext('2d');
        if (produkTerlarisData.length > 0) {
            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: produkTerlarisData.map(item => item.produk.namaProduk),
                    datasets: [{
                        label: 'Produk Terlaris',
                        data: produkTerlarisData.map(item => item.totalTerjual),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                    }]
                }
            });
        } else {
            // Handle the case where no valid products are available (soft-deleted)
            document.getElementById('produkTerlarisChart').parentNode.innerHTML = '<p>Data produk tidak tersedia.</p>';
        }

        // Grafik Toko dengan Pembelian Terbanyak
        var ctx3 = document.getElementById('tokoBanyakPembelianChart').getContext('2d');
        if (tokoBanyakPembelianData.length > 0) {
            new Chart(ctx3, {
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
                }
            });
        } else {
            // Handle the case where no valid stores are available (soft-deleted)
            document.getElementById('tokoBanyakPembelianChart').parentNode.innerHTML = '<p>Data toko tidak tersedia.</p>';
        }
    </script>
    @else
    <h1>Anda tidak memiliki akses untuk melihat performa bisnis.</h1>
    @endif
</body>

</html>