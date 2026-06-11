<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /** @use HasFactory<\Database\Factories\LeadFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'city',
        'industry',
        'source',
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
        'status',
        'notes',
        'customer_id',
        'converted_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => LeadStatus::class,
            'converted_at' => 'datetime',
            'followers' => 'integer',
            'lead_score' => 'integer',
            'audit_score' => 'integer',
            'page_speed_ms' => 'integer',
            'best_pick' => 'boolean',
            'has_ssl' => 'boolean',
            'mobile_friendly' => 'boolean',
            'has_contact_form' => 'boolean',
            'has_whatsapp' => 'boolean',
            'is_ecommerce' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $leadQuery) use ($term): void {
            $leadQuery
                ->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('source', 'like', "%{$term}%")
                ->orWhere('notes', 'like', "%{$term}%");
        });
    }
}
