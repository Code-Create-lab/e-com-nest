<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('website')->nullable()->after('phone');
            $table->string('city', 120)->nullable()->after('website');
            $table->string('industry', 120)->nullable()->after('city');
            $table->string('source_handle', 120)->nullable()->after('source');
            $table->unsignedInteger('followers')->nullable()->after('source_handle');
            $table->text('bio')->nullable()->after('followers');
            $table->unsignedTinyInteger('lead_score')->nullable()->after('bio');
            $table->text('score_reason')->nullable()->after('lead_score');
            $table->boolean('best_pick')->default(false)->after('score_reason');
            $table->unsignedTinyInteger('audit_score')->nullable()->after('best_pick');
            $table->boolean('has_ssl')->nullable()->after('audit_score');
            $table->boolean('mobile_friendly')->nullable()->after('has_ssl');
            $table->unsignedInteger('page_speed_ms')->nullable()->after('mobile_friendly');
            $table->boolean('has_contact_form')->nullable()->after('page_speed_ms');
            $table->boolean('has_whatsapp')->nullable()->after('has_contact_form');
            $table->boolean('is_ecommerce')->nullable()->after('has_whatsapp');
            $table->text('audit_issues')->nullable()->after('is_ecommerce');
            $table->text('audit_summary')->nullable()->after('audit_issues');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'website',
                'city',
                'industry',
                'source_handle',
                'followers',
                'bio',
                'lead_score',
                'score_reason',
                'best_pick',
                'audit_score',
                'has_ssl',
                'mobile_friendly',
                'page_speed_ms',
                'has_contact_form',
                'has_whatsapp',
                'is_ecommerce',
                'audit_issues',
                'audit_summary',
            ]);
        });
    }
};
