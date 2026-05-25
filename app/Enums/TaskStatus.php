<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case Blocked = 'blocked';
    case Done = 'done';

    public function label(): string
    {
        return match ($this) {
            self::Todo => 'To Do',
            self::InProgress => 'In Progress',
            self::Blocked => 'Blocked',
            self::Done => 'Done',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Todo => 'bg-slate-100 text-slate-700 ring-slate-200',
            self::InProgress => 'bg-sky-100 text-sky-700 ring-sky-200',
            self::Blocked => 'bg-rose-100 text-rose-700 ring-rose-200',
            self::Done => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
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
