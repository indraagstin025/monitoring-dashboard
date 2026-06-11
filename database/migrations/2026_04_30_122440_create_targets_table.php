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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('host'); // domain atau IP
            $table->unsignedInteger('port')->nullable();
            $table->string('protocol')->default('http'); // http, https, ping, tcp
            $table->enum('status', ['up', 'down', 'unknown'])->default('unknown');
            $table->integer('consecutive_failures')->default(0);
            $table->integer('check_interval')->default(300); // dalam detik
            $table->integer('timeout')->default(5); // dalam detik
            $table->float('last_response_time')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
