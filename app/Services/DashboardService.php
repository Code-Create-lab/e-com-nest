<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\TaskStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * @return array<string, mixed>
     */
    public function getStats(): array
    {
        $crTotals = $this->changeRequestTotals();

        $invoiceRevenue = (float) Invoice::query()
            ->where('status', InvoiceStatus::Paid)
            ->sum('final_amount');

        $taskStats = $this->pendingTaskStats();

        return [
            'totalCustomers' => Customer::count(),
            'totalLeads' => Lead::count(),
            'totalProjects' => Project::count(),
            'invoiceRevenue' => $invoiceRevenue,
            'totalRevenue' => $invoiceRevenue + $crTotals['paid'],
            'crPaidAmount' => $crTotals['paid'],
            'crUnpaidAmount' => $crTotals['unpaid'],
            'crPaidCount' => $crTotals['paid_count'],
            'crUnpaidCount' => $crTotals['unpaid_count'],
            'crByCustomer' => $this->changeRequestByCustomer(),
            'pendingTasksCount' => $taskStats['pending'],
            'overdueTasksCount' => $taskStats['overdue'],
            'dueThisWeekCount' => $taskStats['due_this_week'],
            'pendingTasks' => $this->pendingTasks(),
            'recentLeads' => Lead::query()
                ->latest()
                ->take(5)
                ->get(),
            'recentProjects' => Project::query()
                ->with('customer')
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    /**
     * @return array{pending: int, overdue: int, due_this_week: int}
     */
    private function pendingTaskStats(): array
    {
        $today = Carbon::now()->toDateString();
        $weekStart = Carbon::now()->startOfWeek()->toDateString();
        $weekEnd = Carbon::now()->endOfWeek()->toDateString();

        $row = DB::table('tasks')
            ->where('status', '!=', TaskStatus::Done->value)
            ->selectRaw('
                COUNT(*) AS pending,
                SUM(CASE WHEN due_date IS NOT NULL AND due_date < ? THEN 1 ELSE 0 END) AS overdue,
                SUM(CASE WHEN due_date BETWEEN ? AND ? THEN 1 ELSE 0 END) AS due_this_week
            ', [$today, $weekStart, $weekEnd])
            ->first();

        return [
            'pending' => (int) ($row->pending ?? 0),
            'overdue' => (int) ($row->overdue ?? 0),
            'due_this_week' => (int) ($row->due_this_week ?? 0),
        ];
    }

    /**
     * Top open tasks: overdue first, then by priority weight, then by due date.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Task>
     */
    private function pendingTasks(): \Illuminate\Database\Eloquent\Collection
    {
        $today = Carbon::now()->toDateString();

        return Task::query()
            ->with(['project.customer'])
            ->where('status', '!=', TaskStatus::Done->value)
            ->selectRaw('tasks.*, CASE WHEN due_date IS NOT NULL AND due_date < ? THEN 0 ELSE 1 END AS overdue_sort', [$today])
            ->orderBy('overdue_sort')
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'normal', 'low')")
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->take(8)
            ->get();
    }

    /**
     * Sum billable amount for CR tasks (billable tasks under one_time projects),
     * split by paid/unpaid status.
     *
     * @return array{paid: float, unpaid: float, paid_count: int, unpaid_count: int}
     */
    private function changeRequestTotals(): array
    {
        $row = DB::table('tasks')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->where('projects.engagement_type', 'one_time')
            ->where('tasks.billable', true)
            ->selectRaw('
                COALESCE(SUM(CASE WHEN tasks.paid = 1 THEN tasks.hours_logged * COALESCE(tasks.hourly_rate, projects.hourly_rate, 0) ELSE 0 END), 0) AS paid_amount,
                COALESCE(SUM(CASE WHEN tasks.paid = 0 THEN tasks.hours_logged * COALESCE(tasks.hourly_rate, projects.hourly_rate, 0) ELSE 0 END), 0) AS unpaid_amount,
                SUM(CASE WHEN tasks.paid = 1 THEN 1 ELSE 0 END) AS paid_count,
                SUM(CASE WHEN tasks.paid = 0 THEN 1 ELSE 0 END) AS unpaid_count
            ')
            ->first();

        return [
            'paid' => (float) ($row->paid_amount ?? 0),
            'unpaid' => (float) ($row->unpaid_amount ?? 0),
            'paid_count' => (int) ($row->paid_count ?? 0),
            'unpaid_count' => (int) ($row->unpaid_count ?? 0),
        ];
    }

    /**
     * Paid-invoice revenue series, bucketed by period.
     * Returns ['labels' => [...], 'values' => [...], 'total' => float, 'period' => string].
     *
     * @return array{labels: array<int, string>, values: array<int, float>, total: float, period: string}
     */
    public function revenueSeries(string $period): array
    {
        $now = Carbon::now();

        return match ($period) {
            'daily' => $this->bucketSeries(
                $now->copy()->subDays(29)->startOfDay(),
                $now->copy()->endOfDay(),
                'day',
                'd M',
                'Y-m-d',
                fn (Carbon $d) => $d->format('Y-m-d'),
            ),
            'weekly' => $this->bucketSeries(
                $now->copy()->subWeeks(11)->startOfWeek(),
                $now->copy()->endOfWeek(),
                'week',
                'd M',
                'oW',
                fn (Carbon $d) => $d->format('o') . str_pad((string) $d->isoWeek(), 2, '0', STR_PAD_LEFT),
            ),
            'yearly' => $this->bucketSeries(
                $now->copy()->subYears(4)->startOfYear(),
                $now->copy()->endOfYear(),
                'year',
                'Y',
                'Y',
                fn (Carbon $d) => $d->format('Y'),
            ),
            default => $this->bucketSeries(
                $now->copy()->subMonths(11)->startOfMonth(),
                $now->copy()->endOfMonth(),
                'month',
                'M Y',
                'Y-m',
                fn (Carbon $d) => $d->format('Y-m'),
            ),
        };
    }

    /**
     * Walk dates by $step, sum paid invoices per bucket using $bucketKey closure.
     *
     * @return array{labels: array<int, string>, values: array<int, float>, total: float, period: string}
     */
    private function bucketSeries(Carbon $start, Carbon $end, string $step, string $labelFmt, string $keyFmt, \Closure $bucketKey): array
    {
        $invoiceSums = [];
        $invoices = Invoice::query()
            ->where('status', InvoiceStatus::Paid)
            ->whereBetween('issue_date', [$start->toDateString(), $end->toDateString()])
            ->get(['issue_date', 'final_amount']);

        foreach ($invoices as $inv) {
            if (! $inv->issue_date) {
                continue;
            }
            $key = $bucketKey(Carbon::parse($inv->issue_date));
            $invoiceSums[$key] = ($invoiceSums[$key] ?? 0) + (float) $inv->final_amount;
        }

        $crSums = [];
        $crRows = DB::table('tasks')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->where('projects.engagement_type', 'one_time')
            ->where('tasks.billable', true)
            ->where('tasks.paid', true)
            ->whereNotNull('tasks.paid_at')
            ->whereBetween('tasks.paid_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->selectRaw('tasks.paid_at, tasks.hours_logged * COALESCE(tasks.hourly_rate, projects.hourly_rate, 0) AS amount')
            ->get();

        foreach ($crRows as $row) {
            $key = $bucketKey(Carbon::parse($row->paid_at));
            $crSums[$key] = ($crSums[$key] ?? 0) + (float) $row->amount;
        }

        $labels = [];
        $invoiceValues = [];
        $crValues = [];
        $totals = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $key = $bucketKey($cursor);
            $labels[] = $cursor->format($labelFmt);
            $iv = round((float) ($invoiceSums[$key] ?? 0), 2);
            $cv = round((float) ($crSums[$key] ?? 0), 2);
            $invoiceValues[] = $iv;
            $crValues[] = $cv;
            $totals[] = round($iv + $cv, 2);

            match ($step) {
                'day' => $cursor->addDay(),
                'week' => $cursor->addWeek(),
                'year' => $cursor->addYear(),
                default => $cursor->addMonth(),
            };
        }

        return [
            'labels' => $labels,
            'values' => $totals,
            'invoice_values' => $invoiceValues,
            'cr_values' => $crValues,
            'total' => array_sum($totals),
            'invoice_total' => array_sum($invoiceValues),
            'cr_total' => array_sum($crValues),
            'period' => $step,
        ];
    }

    /**
     * Per-customer CR breakdown: total paid, unpaid, hours, task counts.
     *
     * @return \Illuminate\Support\Collection<int, object>
     */
    private function changeRequestByCustomer(): \Illuminate\Support\Collection
    {
        return DB::table('tasks')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->join('customers', 'customers.id', '=', 'projects.customer_id')
            ->where('projects.engagement_type', 'one_time')
            ->where('tasks.billable', true)
            ->groupBy('customers.id', 'customers.name', 'customers.company_name')
            ->selectRaw('
                customers.id AS customer_id,
                customers.name AS customer_name,
                customers.company_name AS company_name,
                COALESCE(SUM(tasks.hours_logged), 0) AS total_hours,
                COALESCE(SUM(CASE WHEN tasks.paid = 1 THEN tasks.hours_logged * COALESCE(tasks.hourly_rate, projects.hourly_rate, 0) ELSE 0 END), 0) AS paid_amount,
                COALESCE(SUM(CASE WHEN tasks.paid = 0 THEN tasks.hours_logged * COALESCE(tasks.hourly_rate, projects.hourly_rate, 0) ELSE 0 END), 0) AS unpaid_amount,
                SUM(CASE WHEN tasks.paid = 1 THEN 1 ELSE 0 END) AS paid_count,
                SUM(CASE WHEN tasks.paid = 0 THEN 1 ELSE 0 END) AS unpaid_count,
                COUNT(*) AS total_count
            ')
            ->orderByDesc('unpaid_amount')
            ->orderByDesc('paid_amount')
            ->get();
    }
}
