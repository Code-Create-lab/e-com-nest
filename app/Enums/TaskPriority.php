<?php

namespace App\Enums;

enum TaskPriority: string
{
    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';
    case Urgent = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Low',
            self::Normal => 'Normal',
            self::High => 'High',
            self::Urgent => 'Urgent',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Low => 'bg-slate-100 text-slate-600 ring-slate-200',
            self::Normal => 'bg-indigo-100 text-indigo-700 ring-indigo-200',
            self::High => 'bg-amber-100 text-amber-700 ring-amber-200',
            self::Urgent => 'bg-rose-100 text-rose-700 ring-rose-200',
        };
    }

    public function weight(): int
    {
        return match ($this) {
            self::Low => 0,
            self::Normal => 1,
            self::High => 2,
            self::Urgent => 3,
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $s) => $s->value, self::cases());
    }
}
