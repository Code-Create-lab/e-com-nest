<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->string('group_name', 120)->nullable()->after('meeting_date');
            $table->boolean('paid')->default(false)->after('billed_invoice_id');
            $table->timestamp('paid_at')->nullable()->after('paid');

            $table->index(['project_id', 'group_name']);
            $table->index(['project_id', 'meeting_date']);
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table): void {
            $table->dropIndex(['project_id', 'group_name']);
            $table->dropIndex(['project_id', 'meeting_date']);
            $table->dropColumn(['group_name', 'paid', 'paid_at']);
        });
    }
};
