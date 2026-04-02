<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Paid = 'paid';
    case Unpaid = 'unpaid';

    public function label(): string
    {
        return match ($this) {
            self::Paid => 'Paid',
            self::Unpaid => 'Unpaid',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Paid => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            self::Unpaid => 'bg-rose-100 text-rose-700 ring-rose-200',
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
