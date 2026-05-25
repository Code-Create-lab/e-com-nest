<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->decimal('hourly_rate', 8, 2)->nullable()->after('hours_per_month');
        });

        Schema::table('tasks', function (Blueprint $table): void {
            $table->boolean('billable')->default(false)->after('priority');
            $table->decimal('hours_logged', 6, 2)->default(0)->after('billable');
            $table->decimal('hourly_rate', 8, 2)->nullable()->after('hours_logged');
            $table->foreignId('billed_invoice_id')->nullable()->after('hourly_rate')->constrained('invoices')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('billed_invoice_id');
            $table->dropColumn(['billable', 'hours_logged', 'hourly_rate']);
        });

        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn('hourly_rate');
        });
    }
};
