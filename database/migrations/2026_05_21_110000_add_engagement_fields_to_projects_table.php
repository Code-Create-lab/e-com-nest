<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->string('engagement_type', 30)->default('one_time')->after('description');
            $table->decimal('monthly_amount', 12, 2)->nullable()->after('engagement_type');
            $table->unsignedTinyInteger('billing_day')->nullable()->after('monthly_amount');
            $table->unsignedSmallInteger('hours_per_month')->nullable()->after('billing_day');
            $table->date('support_renews_on')->nullable()->after('hours_per_month');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn([
                'engagement_type',
                'monthly_amount',
                'billing_day',
                'hours_per_month',
                'support_renews_on',
            ]);
        });
    }
};
