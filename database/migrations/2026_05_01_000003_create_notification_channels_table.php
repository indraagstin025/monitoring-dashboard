<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('name')->comment('Nama channel, misal: Bot Pribadi Saya');
            $table->string('type')->default('telegram')
                  ->comment('Tipe channel: telegram, discord, email, webhook');

            // Config disimpan sebagai JSON:
            // telegram: { bot_token, chat_id }
            // discord:  { webhook_url }
            // email:    { address }
            $table->json('config');

            $table->boolean('is_active')->default(true);
            $table->timestamp('last_tested_at')->nullable();
            $table->boolean('last_test_passed')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_channels');
    }
};
