<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); // NIK / Nomor ID Kepala Keluarga
            $table->string('name');             // Nama Kepala Keluarga
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user'); // Filterisasi Role
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};