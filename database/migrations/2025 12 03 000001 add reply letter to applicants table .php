<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('reply_letter_path')->nullable()->after('internship_end');
            $table->string('reply_letter_name')->nullable()->after('reply_letter_path');
            $table->timestamp('reply_letter_uploaded_at')->nullable()->after('reply_letter_name');
            $table->string('lokasi_penempatan')->nullable()->after('location'); // unit/divisi spesifik
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn(['reply_letter_path', 'reply_letter_name', 'reply_letter_uploaded_at', 'lokasi_penempatan']);
        });
    }
};