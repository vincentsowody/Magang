<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            
            // Data Registrasi Awal (Input Admin)
            $table->string('name');
            $table->string('nim');
            $table->string('code')->unique(); // Kode Akses Login Peserta (Contoh: MAG-2025-001)
            $table->string('univ');
            $table->string('major');
            
            // Data Status HRD (Output Admin)
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->enum('location', ['kantor', 'terminal'])->nullable(); // Penempatan
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};