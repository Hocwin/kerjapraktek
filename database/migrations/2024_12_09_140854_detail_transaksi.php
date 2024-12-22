<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('idDetailTransaksi');
            $table->unsignedBigInteger('idTransaksi')->index()->nullable();
            $table->unsignedBigInteger('idProduk')->index()->nullable();
            $table->unsignedBigInteger('idGudang')->index()->nullable();
            $table->string('namaGudang')->nullable()->default('');
            $table->integer('jumlahProduk');
            $table->integer('harga');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('idProduk')->references('idProduk')->on('produk');
            $table->foreign('idTransaksi')->references('idTransaksi')->on('transaksi');
            $table->foreign('idGudang')->references('idGudang')->on('gudang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
