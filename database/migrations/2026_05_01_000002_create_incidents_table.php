<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_id')->constrained()->cascadeOnDelete();

            $table->timestamp('started_at')->comment('Waktu pertama kali status berubah ke down');
            $table->timestamp('resolved_at')->nullable()->comment('Waktu status kembali ke up, null = masih berlangsung');
            $table->unsignedInteger('duration_seconds')->nullable()->comment('Durasi incident dalam detik');

            $table->string('trigger_status')->default('down')
                  ->comment('Status yang memicu incident: down atau unstable');
            $table->text('notes')->nullable()->comment('Catatan manual dari user');

            $table->timestamps();

            // Index untuk query cepat
            $table->index('started_at');
            $table->index('resolved_at');
            $table->index(['target_id', 'resolved_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
