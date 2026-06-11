<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'assignee',
        'position',
        'completed_at',
        'source',
        'meeting_date',
        'billable',
        'hours_logged',
        'hourly_rate',
        'billed_invoice_id',
        'group_name',
        'paid',
        'paid_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'priority' => TaskPriority::class,
            'due_date' => 'date',
            'meeting_date' => 'date',
            'completed_at' => 'datetime',
            'position' => 'integer',
            'billable' => 'boolean',
            'hours_logged' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'paid' => 'boolean',
            'paid_at' => 'datetime',
        ];
    }

    public function groupKey(): string
    {
        if (filled($this->group_name)) {
            return 'name:' . $this->group_name;
        }

        if ($this->meeting_date) {
            return 'date:' . $this->meeting_date->toDateString();
        }

        return 'none';
    }

    public function groupLabel(): string
    {
        if (filled($this->group_name)) {
            return $this->group_name;
        }

        if ($this->meeting_date) {
            return 'Meeting · ' . $this->meeting_date->format('d M Y');
        }

        return 'Ungrouped';
    }

    public function effectiveHourlyRate(): float
    {
        return (float) (
            $this->hourly_rate
            ?? $this->project?->hourly_rate
            ?? $this->project?->customer?->hourly_rate
            ?? 0
        );
    }

    public function billableAmount(): float
    {
        if (! $this->billable) {
            return 0.0;
        }

        return round((float) $this->hours_logged * $this->effectiveHourlyRate(), 2);
    }

    public function isBilled(): bool
    {
        return ! is_null($this->billed_invoice_id);
    }

    public function scopeBillable(Builder $query): Builder
    {
        return $query->where('billable', true);
    }

    public function scopeUnbilled(Builder $query): Builder
    {
        return $query->where('billable', true)->whereNull('billed_invoice_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function isDone(): bool
    {
        return $this->status === TaskStatus::Done;
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && ! $this->isDone()
            && $this->due_date->isPast();
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', '!=', TaskStatus::Done->value);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->where('status', '!=', TaskStatus::Done->value);
    }

    public function scopeDueThisWeek(Builder $query): Builder
    {
        return $query
            ->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', '!=', TaskStatus::Done->value);
    }
}
