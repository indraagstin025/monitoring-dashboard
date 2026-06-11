<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            // Fitur Pause/Resume Monitoring
            $table->boolean('is_paused')->default(false)->after('status');

            // Fitur SSL Monitoring
            $table->timestamp('ssl_expires_at')->nullable()->after('is_paused');

            // Konfigurasi Alert yang Lebih Fleksibel
            $table->integer('alert_threshold')->default(3)->after('ssl_expires_at')
                  ->comment('Jumlah kegagalan berturut-turut sebelum alert dikirim');

            // Keyword check pada HTTP response body
            $table->string('keyword_check')->nullable()->after('alert_threshold')
                  ->comment('Kata kunci yang harus ada di response body');

            // Organisasi Target
            $table->string('group_name')->nullable()->after('keyword_check');
            $table->json('tags')->nullable()->after('group_name');

            // Uptime Percentage (sudah dikalkulasi, bukan real-time)
            $table->float('uptime_1d')->nullable()->after('tags')
                  ->comment('Uptime % 24 jam terakhir');
            $table->float('uptime_7d')->nullable()->after('uptime_1d')
                  ->comment('Uptime % 7 hari terakhir');
            $table->float('uptime_30d')->nullable()->after('uptime_7d')
                  ->comment('Uptime % 30 hari terakhir');

            // Kontrol Notifikasi Per-Target
            $table->boolean('notify_on_recovery')->default(true)->after('uptime_30d');

            // Index untuk filtering
            $table->index('is_paused');
            $table->index('group_name');
        });

        $this->updateStatusValues(['up', 'down', 'unknown', 'unstable']);
    }

    public function down(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->dropColumn([
                'is_paused', 'ssl_expires_at', 'alert_threshold',
                'keyword_check', 'group_name', 'tags',
                'uptime_1d', 'uptime_7d', 'uptime_30d',
                'notify_on_recovery',
            ]);
        });

        $this->updateStatusValues(['up', 'down', 'unknown']);
    }

    private function updateStatusValues(array $values): void
    {
        $driver = DB::connection()->getDriverName();
        $quotedValues = collect($values)->map(fn ($value) => "'{$value}'")->implode(', ');

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE targets MODIFY status ENUM({$quotedValues}) NOT NULL DEFAULT 'unknown'");

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE targets DROP CONSTRAINT IF EXISTS targets_status_check');
            DB::statement("ALTER TABLE targets ADD CONSTRAINT targets_status_check CHECK (status IN ({$quotedValues}))");
        }
    }
};
