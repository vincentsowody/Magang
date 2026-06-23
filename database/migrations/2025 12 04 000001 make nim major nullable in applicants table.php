<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pakai raw SQL agar tidak butuh doctrine/dbal
        DB::statement('ALTER TABLE applicants MODIFY nim VARCHAR(50) NULL');
        DB::statement('ALTER TABLE applicants MODIFY major VARCHAR(100) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE applicants MODIFY nim VARCHAR(50) NOT NULL');
        DB::statement('ALTER TABLE applicants MODIFY major VARCHAR(100) NOT NULL');
    }
};