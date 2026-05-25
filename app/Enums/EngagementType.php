<?php

namespace App\Enums;

enum EngagementType: string
{
    case OneTime = 'one_time';
    case MonthlyRetainer = 'monthly_retainer';
    case Hourly = 'hourly';

    public function label(): string
    {
        return match ($this) {
            self::OneTime => 'New Development',
            self::MonthlyRetainer => 'Monthly Support',
            self::Hourly => 'Hourly',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::OneTime => 'bg-sky-100 text-sky-700 ring-sky-200',
            self::MonthlyRetainer => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            self::Hourly => 'bg-amber-100 text-amber-700 ring-amber-200',
        };
    }

    public function isRecurring(): bool
    {
        return $this === self::MonthlyRetainer;
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $type) => $type->value, self::cases());
    }
}
