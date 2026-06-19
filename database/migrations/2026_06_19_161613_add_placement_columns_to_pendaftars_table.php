<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('pendaftars', function (Blueprint $table) {
        $table->string('lokasi_penempatan')->nullable()->after('status');
        $table->date('tanggal_mulai')->nullable()->after('lokasi_penempatan');
        $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            //
        });
    }
};
