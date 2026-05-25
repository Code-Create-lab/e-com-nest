<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, DashboardService $dashboardService): View
    {
        $period = in_array($request->input('period'), ['daily', 'weekly', 'monthly', 'yearly'], true)
            ? $request->input('period')
            : 'monthly';

        $stats = $dashboardService->getStats();
        $stats['revenuePeriod'] = $period;
        $stats['revenueSeries'] = $dashboardService->revenueSeries($period);

        return view('dashboard.index', $stats);
    }
}
