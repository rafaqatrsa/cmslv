<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\LibraryMember;
use App\Models\Page;
use App\Models\Post;
use App\Models\Staff;
use App\Models\SystemNotification;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $reportCards = [
            'Active branches' => Branch::query()->active()->count(),
            'Active staff' => Staff::query()->active()->count(),
            'Published pages' => Page::query()->published()->count(),
            'Published posts' => Post::query()->published()->count(),
            'Library members' => LibraryMember::query()->count(),
            'System notifications' => SystemNotification::query()->count(),
        ];

        return view('admin.reports.index', compact('reportCards'));
    }
}
