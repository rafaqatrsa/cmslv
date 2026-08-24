<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\LibraryMember;
use App\Models\Page;
use App\Models\Post;
use App\Models\Staff;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'branches' => Schema::hasTable('branches') ? Branch::query()->count() : 0,
            'staff' => Schema::hasTable('staff') ? Staff::query()->count() : 0,
            'front_pages' => Schema::hasTable('front_cms_pages') ? Page::query()->count() : 0,
            'front_posts' => Schema::hasTable('front_cms_media') ? Post::query()->count() : 0,
            'members' => Schema::hasTable('libarary_members') ? LibraryMember::query()->count() : 0,
            'notifications' => Schema::hasTable('system_notification') ? SystemNotification::query()->count() : 0,
            'students' => Schema::hasTable('students') ? DB::table('students')->count() : 0,
            'admissions' => Schema::hasTable('student_admissions') ? DB::table('student_admissions')->count() : 0,
            'admission_inquiries' => Schema::hasTable('enquiry') ? DB::table('enquiry')->count() : 0,
            'registrations' => Schema::hasTable('student_regd') ? DB::table('student_regd')->count() : 0,
            'complaints' => Schema::hasTable('complaint') ? DB::table('complaint')->count() : 0,
            'visitors' => Schema::hasTable('visitors_book') ? DB::table('visitors_book')->count() : 0,
            'purchases' => Schema::hasTable('purchases') ? DB::table('purchases')->count() : 0,
            'sales' => Schema::hasTable('sales') ? DB::table('sales')->count() : 0,
            'teaching_staff' => Schema::hasTable('staff') ? Staff::query()->where('role_id', 2)->count() : 0,
            'admin_staff' => Schema::hasTable('staff') ? Staff::query()->whereIn('role_id', [1, 3, 4])->count() : 0,
            'allied_staff' => Schema::hasTable('staff') ? Staff::query()->whereNotIn('role_id', [1, 2, 3, 4])->count() : 0,
            'families' => Schema::hasTable('parent') ? DB::table('parent')->count() : 0,
        ];

        $latestNotifications = Schema::hasTable('system_notification')
            ? SystemNotification::query()->latest('created_at')->limit(5)->get()
            : collect();

        return view('admin.dashboard.index', compact('stats', 'latestNotifications'));
    }
}
