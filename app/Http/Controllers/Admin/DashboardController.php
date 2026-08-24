<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemNotification;
use App\Services\Admin\DashboardMetricsService;
use App\Services\BranchContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardMetricsService $metrics, ?int $branch = null): View
    {
        if ($branch !== null) {
            foreach (config('legacy.session.branch_keys', ['brc_id', 'branch_id']) as $key) {
                $request->session()->put($key, $branch);
            }
        }

        $branchId = $branch ?? app(BranchContext::class)->id();
        $stats = $metrics->for($branchId);

        $latestNotifications = Schema::hasTable('system_notification')
            ? SystemNotification::query()
                ->latest('created_at')
                ->limit(5)
                ->get()
            : collect();

        return view('admin.dashboard.index', compact('stats', 'latestNotifications'));
    }
}
