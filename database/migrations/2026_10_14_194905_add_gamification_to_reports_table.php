<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGamificationToReportsTable extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->decimal('bobot_kg', 8, 2)->default(0)->after('jenis_sampah');
            $table->integer('poin')->default(0)->after('bobot_kg');
            $table->decimal('saldo_rp', 12, 2)->default(0)->after('poin');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu')->after('saldo_rp');
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['bobot_kg', 'poin', 'saldo_rp', 'status']);
        });
    }
}