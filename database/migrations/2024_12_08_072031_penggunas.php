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
        Schema::create('penggunas', function (Blueprint $table) {
            $table->id('idPengguna');
            $table->string('namaPengguna');
            $table->string('emailPengguna')->unique();
            $table->string('password');
            $table->string('alamatPengguna');
            $table->enum('jenisKelamin', ['L', 'P']);
            $table->enum('rolePengguna', ['admin', 'sales', 'manager'])->default('sales');
            $table->string('plaintext_password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunas');
    }
};
