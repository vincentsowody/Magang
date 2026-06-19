<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicant_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['status_change', 'document', 'info'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->json('meta')->nullable();           // data tambahan (status baru, dll)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_notifications');
    }
};
