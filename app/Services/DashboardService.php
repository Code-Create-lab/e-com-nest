<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Project;

class DashboardService
{
    /**
     * @return array<string, mixed>
     */
    public function getStats(): array
    {
        return [
            'totalCustomers' => Customer::count(),
            'totalLeads' => Lead::count(),
            'totalProjects' => Project::count(),
            'totalRevenue' => (float) Invoice::query()
                ->where('status', InvoiceStatus::Paid)
                ->sum('final_amount'),
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
}
