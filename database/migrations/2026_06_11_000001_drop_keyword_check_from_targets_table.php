<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('targets', 'keyword_check')) {
            return;
        }

        Schema::table('targets', function (Blueprint $table) {
            $table->dropColumn('keyword_check');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('targets', 'keyword_check')) {
            return;
        }

        Schema::table('targets', function (Blueprint $table) {
            $table->string('keyword_check')->nullable()->after('alert_threshold');
        });
    }
};
