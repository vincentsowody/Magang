<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            // Masa Magang (diisi admin saat status = accepted)
            $table->date('internship_start')->nullable()->after('location');
            $table->date('internship_end')->nullable()->after('internship_start');
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn(['internship_start', 'internship_end']);
        });
    }
};
