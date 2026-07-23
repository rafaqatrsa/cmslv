<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\LibraryMember;
use App\Models\Page;
use App\Models\Post;
use App\Models\Staff;
use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, ?int $branch = null): View
    {
        if ($branch !== null) {
            foreach (config('legacy.session.branch_keys', ['brc_id', 'branch_id']) as $key) {
                $request->session()->put($key, $branch);
            }
        }

        $stats = [
            'branches' => Branch::query()->count(),
            'staff' => Staff::query()->count(),
            'front_pages' => Page::query()->count(),
            'front_posts' => Post::query()->count(),
            'members' => LibraryMember::query()->count(),
            'notifications' => SystemNotification::query()->count(),
        ];

        $latestNotifications = SystemNotification::query()
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'latestNotifications'));
    }
}
