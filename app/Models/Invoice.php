<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'project_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'subtotal_amount',
        'discount',
        'final_amount',
        'status',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'subtotal_amount' => 'decimal:2',
            'discount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'status' => InvoiceStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $invoiceQuery) use ($term): void {
            $invoiceQuery
                ->where('invoice_number', 'like', "%{$term}%")
                ->orWhereHas('customer', function (Builder $customerQuery) use ($term): void {
                    $customerQuery
                        ->where('name', 'like', "%{$term}%")
                        ->orWhere('company_name', 'like', "%{$term}%");
                })
                ->orWhereHas('project', function (Builder $projectQuery) use ($term): void {
                    $projectQuery->where('project_name', 'like', "%{$term}%");
                });
        });
    }
}
