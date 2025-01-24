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
        Schema::create('gudang_pengguna', function (Blueprint $table) {
            $table->id("idGudangPengguna");
            $table->unsignedBigInteger('idGudang')->index()->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('idPengguna')->index()->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->foreign('idGudang')->references('idGudang')->on('gudang')->onDelete('cascade');
            $table->foreign('idPengguna')->references('idPengguna')->on('penggunas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_pengguna');
    }
};
