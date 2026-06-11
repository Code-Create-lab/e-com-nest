<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->decimal('hourly_rate', 8, 2)->nullable()->after('system_monitor_url');
        });

        Schema::table('projects', function (Blueprint $table): void {
            $table->decimal('total_development_cost', 12, 2)->nullable()->after('progress');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn('hourly_rate');
        });

        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn('total_development_cost');
        });
    }
};
