<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SystemSettingsDashboardController extends Controller
{
    public function index(): View
    {
        $settingGroups = [
            'System Settings' => [
                'General Settings',
                'Branch Settings',
                'Session Settings',
                'Notification Setting',
                'Whatsaap Messaging',
                'SMS Setting',
                'Email Setting',
                'Modules Setting',
                'Roles Permissions',
                'Front CMS Setting',
            ],
            'Particles' => [
                'Department',
                'Designation',
                'Academic Year',
                'Leave Types',
                'Skills',
                'Banks',
                'Training',
                'Organization',
            ],
        ];

        return view('admin.systemsettings.dashboard', compact('settingGroups'));
    }
}
