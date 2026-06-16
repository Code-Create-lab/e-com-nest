<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Engagement fields (engagement_type, monthly_amount, billing_day,
     * hours_per_month, support_renews_on) were consolidated into the
     * create_projects_table migration. Kept as a no-op so already-migrated
     * databases retain their history without re-adding the columns.
     */
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
};
