<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->string('live_url', 255)->nullable()->after('address');
            $table->string('stg_url', 255)->nullable()->after('live_url');
            $table->string('system_monitor_url', 255)->nullable()->after('stg_url');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn(['live_url', 'stg_url', 'system_monitor_url']);
        });
    }
};
