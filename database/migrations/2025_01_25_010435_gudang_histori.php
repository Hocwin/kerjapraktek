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
        Schema::create('gudang_histori', function (Blueprint $table) {
            $table->id("idHistori");
            $table->unsignedBigInteger('idGudang')->index()->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('idPengguna')->index()->nullable()->constrained()->onDelete('cascade');
            $table->string('action'); // 'create', 'update', etc.
            $table->text('details'); // JSON field for storing changes
            $table->timestamps();

            // Relationships
            $table->foreign('idGudang')->references('idGudang')->on('gudang')->onDelete('cascade');
            $table->foreign('idPengguna')->references('idPengguna')->on('penggunas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_histori');
    }
};
