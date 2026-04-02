<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Planning = 'planning';
    case Active = 'active';
    case OnHold = 'on_hold';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Planning => 'Planning',
            self::Active => 'Active',
            self::OnHold => 'On Hold',
            self::Completed => 'Completed',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Planning => 'bg-slate-100 text-slate-700 ring-slate-200',
            self::Active => 'bg-sky-100 text-sky-700 ring-sky-200',
            self::OnHold => 'bg-amber-100 text-amber-700 ring-amber-200',
            self::Completed => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
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
