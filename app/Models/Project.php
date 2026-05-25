<?php

namespace App\Models;

use App\Enums\EngagementType;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'project_name',
        'description',
        'start_date',
        'end_date',
        'status',
        'progress',
        'engagement_type',
        'monthly_amount',
        'billing_day',
        'hours_per_month',
        'hourly_rate',
        'support_renews_on',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'support_renews_on' => 'date',
            'status' => ProjectStatus::class,
            'engagement_type' => EngagementType::class,
            'progress' => 'integer',
            'monthly_amount' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'billing_day' => 'integer',
            'hours_per_month' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('position')->orderBy('id');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $projectQuery) use ($term): void {
            $projectQuery
                ->where('project_name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
                ->orWhereHas('customer', function (Builder $customerQuery) use ($term): void {
                    $customerQuery
                        ->where('name', 'like', "%{$term}%")
                        ->orWhere('company_name', 'like', "%{$term}%");
                });
        });
    }
}
