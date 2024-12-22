<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Toko extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toko', function (Blueprint $table) {
            $table->id('idToko');
            $table->string('namaToko');
            $table->string('alamatToko');
            $table->string('nomorTelepon');
            $table->string('jamOperasional');
            $table->string('namaSopir');
            $table->softDeletes();
            $table->string('imageAsset');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('toko');
    }
}
