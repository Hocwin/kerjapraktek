<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class PerformaBisnis extends Model
{
    use HasFactory;

    protected $table = 'performa_bisnis'; // This is the table to store performance data
    protected $primaryKey = 'idLaporan';

    protected $fillable = [
        'idToko', 'total_penjualan', 'total_profit', 'produk_terlaris', 'periode',
    ];

    // Define the relationship with Toko (Store)
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'idToko', 'idToko');
    }

    // Get total sales for each product in a given period
    public static function getTotalSalesByProduct($startDate, $endDate)
    {
        return DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.idProduk', '=', 'produk.idProduk')
            ->select(DB::raw('produk.namaProduk, SUM(detail_transaksi.jumlahProduk) as total_terjual'))
            ->whereBetween('detail_transaksi.created_at', [$startDate, $endDate])
            ->groupBy('produk.namaProduk')
            ->get();
    }

    // Get total profit for each product in a given period
    public static function getTotalProfitByProduct($startDate, $endDate)
    {
        return DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.idProduk', '=', 'produk.idProduk')
            ->select(DB::raw('produk.namaProduk, SUM(detail_transaksi.jumlahProduk * (produk.harga_jual - detail_transaksi.harga)) as total_profit'))
            ->whereBetween('detail_transaksi.created_at', [$startDate, $endDate])
            ->groupBy('produk.namaProduk')
            ->get();
    }

    // Get the top-selling product for the given period
    public static function getTopSellingProduct($startDate, $endDate)
    {
        return DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.idProduk', '=', 'produk.idProduk')
            ->select(DB::raw('produk.namaProduk, SUM(detail_transaksi.jumlahProduk) as total_terjual'))
            ->whereBetween('detail_transaksi.created_at', [$startDate, $endDate])
            ->groupBy('produk.namaProduk')
            ->orderByDesc('total_terjual')
            ->first(); // Get the product with highest sales
    }

    // Get the store with the highest sales in a given period
    public static function getTopStore($startDate, $endDate)
    {
        return DB::table('transaksi')
            ->join('detail_transaksi', 'transaksi.idTransaksi', '=', 'detail_transaksi.idTransaksi')
            ->select(DB::raw('transaksi.idToko, SUM(detail_transaksi.jumlahProduk * detail_transaksi.harga) as total_sales'))
            ->whereBetween('transaksi.tanggalTransaksi', [$startDate, $endDate])
            ->groupBy('transaksi.idToko')
            ->orderByDesc('total_sales')
            ->first(); // Get the store with the highest total sales
    }

    // Store performance report data for a specific period
    public static function storePerformanceReport($storeId, $startDate, $endDate)
    {
         // Get total sales and profit for the store
    $salesData = self::getTotalSalesByProduct($startDate, $endDate);
    $profitData = self::getTotalProfitByProduct($startDate, $endDate);
    $topSellingProduct = self::getTopSellingProduct($startDate, $endDate);
    $topStore = self::getTopStore($startDate, $endDate);

    // Calculate the total sales and profit for the store
    $totalSales = 0;
    foreach ($salesData as $sale) {
        $totalSales += $sale->total_terjual * $sale->harga_correct;  // Use the correct price based on payment type
    }

    $totalProfit = 0;
    foreach ($profitData as $profit) {
        $totalProfit += $profit->total_profit;
    }

    // Store the report
    $report = self::create([
        'idToko' => $storeId,
        'total_penjualan' => $totalSales,
        'total_profit' => $totalProfit,
        'produk_terlaris' => $topSellingProduct ? $topSellingProduct->namaProduk : 'None',
        'periode' => now()->toDateString(),
    ]);

    return $report;
    }
}
