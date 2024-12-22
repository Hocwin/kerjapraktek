<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Transaksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('idTransaksi');
            $table->unsignedBigInteger('idToko')->index()->nullable()->constrained()->onDelete('cascade');
            $table->enum('tipePembayaran', ['cash', 'tempo']);
            $table->enum('status', ['lunas', 'belum lunas'])->default('belum lunas');
            $table->timestamp('tanggalTransaksi')->useCurrent();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('idToko')->references('idToko')->on('toko')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
}
