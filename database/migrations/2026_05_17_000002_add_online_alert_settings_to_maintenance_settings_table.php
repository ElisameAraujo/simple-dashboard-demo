<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_settings', function (Blueprint $table) {
            $table->boolean('show_online_alert')->default(true)->after('show_header_shortcut');
            $table->unsignedInteger('online_alert_duration_seconds')->default(30)->after('show_online_alert');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_settings', function (Blueprint $table) {
            $table->dropColumn([
                'show_online_alert',
                'online_alert_duration_seconds',
            ]);
        });
    }
};
