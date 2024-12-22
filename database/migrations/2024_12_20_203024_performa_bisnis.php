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
        Schema::create('performa_bisnis', function (Blueprint $table) {
            $table->id('idLaporan');
            $table->unsignedBigInteger('idToko');
            $table->integer('total_penjualan');
            $table->integer('total_profit');
            $table->integer('produk_terlaris');
            $table->timestamp('periode');
            $table->timestamps();

            $table->foreign('idToko')->references('idToko')->on('toko')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performa_bisnis');
    }
};
