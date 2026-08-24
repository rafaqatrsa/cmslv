<?php

namespace App\Http\Controllers\Admin\Adm;

use App\Services\Admin\DashboardMetricsService;
use App\Services\BranchContext;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdmDashboardController extends BaseAdmController
{
    public function index(Request $request, DashboardMetricsService $metrics): View
    {
        return view('admin.adm.dashboard', [
            'stats' => $metrics->for(app(BranchContext::class)->id()),
        ]);
    }
}
