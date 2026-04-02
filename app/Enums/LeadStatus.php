<?php

namespace App\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Converted = 'converted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::InProgress => 'In Progress',
            self::Converted => 'Converted',
            self::Rejected => 'Rejected',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::New => 'bg-sky-100 text-sky-700 ring-sky-200',
            self::InProgress => 'bg-amber-100 text-amber-700 ring-amber-200',
            self::Converted => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            self::Rejected => 'bg-rose-100 text-rose-700 ring-rose-200',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(
            static fn (self $status) => $status->value,
            self::cases(),
        );
    }
}
