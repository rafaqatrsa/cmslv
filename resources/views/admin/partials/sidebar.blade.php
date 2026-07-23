@php
    $currentRouteName = request()->route()?->getName() ?? '';
    $isHrmsRoute = str_starts_with($currentRouteName, 'admin.hrms.');
    $requestedMenu = request()->string('menu')->toString();
    $requestedSubmenu = request()->string('submenu')->toString();
    $currentHrmsMenu = match ($currentRouteName) {
        'admin.hrms.dashboard' => 'dashboard',
        'admin.hrms.documents.index' => 'manual_support',
        'admin.hrms.manual.index' => 'manual_support',
        'admin.hrms.staff.index',
        'admin.hrms.staff.profile',
        'admin.hrms.staff.edit' => 'staff_recruitment',
        default => null,
    };

    if ($requestedMenu !== '') {
        $currentHrmsMenu = $requestedMenu;
    }

    $hrmsSidebarItems = [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'fa fa-desktop',
            'route' => 'admin.hrms.dashboard',
            'children' => [],
        ],
        [
            'key' => 'manual_support',
            'label' => 'Manual Support',
            'icon' => 'fa fa-life-ring',
            'route' => null,
            'children' => [
                ['label' => 'Add Documents', 'route' => 'admin.hrms.documents.index'],
                ['label' => 'Policy Manual', 'route' => 'admin.hrms.manual.index'],
                ['label' => 'Flow Charts', 'route' => 'admin.hrms.manual.index'],
                ['label' => 'Supportive Documents', 'route' => 'admin.hrms.documents.index'],
                ['label' => 'Registers', 'route' => 'admin.hrms.documents.index'],
                ['label' => 'Video Supports', 'route' => 'admin.hrms.manual.index'],
            ],
        ],
        [
            'key' => 'staff_recruitment',
            'label' => 'Staff Recruitment',
            'icon' => 'fa fa-users',
            'route' => 'admin.hrms.staff.index',
            'children' => [
                ['key' => 'staff_demand', 'label' => 'Staff Demand', 'route' => 'admin.hrms.staff.index'],
                ['key' => 'job_post', 'label' => 'Job Post', 'route' => 'admin.hrms.staff.index'],
                ['key' => 'job_application', 'label' => 'Job Application', 'route' => 'admin.hrms.staff.index'],
                ['key' => 'short_listed_candidates', 'label' => 'Short Listed Candidates', 'route' => 'admin.hrms.staff.index'],
                ['key' => 'written_test', 'label' => 'Written Test', 'route' => 'admin.hrms.staff.index'],
                ['key' => 'interview_call', 'label' => 'Interview Call', 'route' => 'admin.hrms.staff.index'],
                ['key' => 'interview_rating', 'label' => 'Interview Rating', 'route' => 'admin.hrms.staff.index'],
                ['key' => 'job_letter', 'label' => 'Job Letter', 'route' => 'admin.hrms.staff.index'],
                ['key' => 'staff_directory', 'label' => 'Staff Directory', 'route' => 'admin.hrms.staff.index'],
            ],
        ],
        [
            'key' => 'compensations_benefits',
            'label' => 'Compensations Benefits',
            'icon' => 'fa fa-line-chart',
            'route' => null,
            'children' => [
                ['label' => 'Overview', 'route' => 'admin.hrms.staff.index'],
            ],
        ],
        [
            'key' => 'training_development',
            'label' => 'Training Development',
            'icon' => 'fa fa-compass',
            'route' => null,
            'children' => [
                ['label' => 'Overview', 'route' => 'admin.hrms.staff.index'],
            ],
        ],
        [
            'key' => 'performance_management',
            'label' => 'Performance Management',
            'icon' => 'fa fa-pie-chart',
            'route' => null,
            'children' => [
                ['label' => 'Overview', 'route' => 'admin.hrms.staff.index'],
            ],
        ],
        [
            'key' => 'non_conformance',
            'label' => 'Non Conformance',
            'icon' => 'fa fa-bullseye',
            'route' => null,
            'children' => [
                ['label' => 'Overview', 'route' => 'admin.hrms.staff.index'],
            ],
        ],
        [
            'key' => 'registration_termination',
            'label' => 'Registration & Termination',
            'icon' => 'fa fa-window-close',
            'route' => null,
            'children' => [
                ['label' => 'Staff Disable Directory', 'route' => 'admin.hrms.staff.index'],
            ],
        ],
        [
            'key' => 'reports_reviews',
            'label' => 'Reports Reviews',
            'icon' => 'fa fa-bar-chart',
            'route' => null,
            'children' => [
                ['label' => 'Overview', 'route' => 'admin.hrms.staff.index'],
            ],
        ],
    ];

    $sidebarItems = $hrmsSidebarItems;
@endphp

<aside class="admin-sidebar" style="display:block;position:fixed;top:0;left:0;bottom:0;width:266px;background:#2d4b94;color:#fff;z-index:30;box-shadow:0 2px 8px rgba(0,0,0,.24);overflow:hidden;">
    <div style="height:54px;padding:10px 8px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:8px;">
        <div style="width:36px;height:36px;border-radius:50%;background:#f7f7f7;border:1px solid rgba(255,255,255,.4);display:flex;align-items:center;justify-content:center;color:#7b8798;font-size:16px;flex:0 0 36px;">
            <i class="fa fa-user-o"></i>
        </div>
        <a href="{{ route('admin.dashboard', absolute: false) }}" style="color:#ffffff;text-decoration:none;font-size:11px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <i class="fa fa-hand-o-right" style="margin-right:4px;"></i>Super Admin
        </a>
    </div>

    <div style="padding:10px 10px 8px;border-bottom:1px solid rgba(0,0,0,.18);background:#2b478d;">
        <div style="font-size:11px;font-weight:700;color:#fff;">Current Session: 2026-27</div>
        <div style="margin-top:6px;display:flex;align-items:center;justify-content:space-between;font-size:11px;color:#fff;">
            <span>Quick Links</span>
            <span style="font-size:13px;"><i class="fa fa-th"></i></span>
        </div>
    </div>

    <nav style="height:calc(100vh - 96px);overflow-y:auto;overflow-x:hidden;padding-top:2px;">
        @foreach ($sidebarItems as $item)
            @php
                $itemIsActive = $item['route'] ? request()->routeIs($item['route']) : false;
                $menuIsActive = ($item['key'] ?? null) !== null && ($item['key'] ?? null) === $currentHrmsMenu;
                $childRouteIsActive = collect($item['children'])->contains(
                    fn (array $child): bool => request()->routeIs($child['route'])
                );
                $childIsActive = $menuIsActive || (!$isHrmsRoute && $childRouteIsActive);
                $isExpanded = $itemIsActive || $childIsActive;
            @endphp

            @if ($item['children'] !== [])
                <div style="border-bottom:1px solid rgba(255,255,255,.06);">
                    <a
                        href="{{ $item['route'] ? route($item['route'], absolute: false) : request()->fullUrlWithQuery(['menu' => $item['key']]) }}"
                        style="display:flex;align-items:center;gap:10px;padding:11px 10px;color:#fff;text-decoration:none;font-size:11px;font-weight:600;background:{{ $isExpanded ? '#3b61ad' : 'transparent' }};"
                    >
                        <span style="width:16px;text-align:center;font-size:13px;flex:0 0 16px;"><i class="{{ $item['icon'] }}"></i></span>
                        <span style="flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item['label'] }}</span>
                        <span style="font-size:12px;"><i class="fa fa-angle-{{ $isExpanded ? 'down' : 'right' }}"></i></span>
                    </a>

                    <div style="display:{{ $isExpanded ? 'block' : 'none' }};background:#3d5fa7;padding:2px 0;">
                        @foreach ($item['children'] as $child)
                            @php
                                $childKey = $child['key'] ?? '';
                                $childIsCurrent = request()->routeIs($child['route']) && ($requestedSubmenu === '' || $requestedSubmenu === $childKey);
                                $childUrl = route($child['route'], absolute: false);

                                if ($child['route'] !== 'admin.hrms.staff.index') {
                                    $childParams = ['menu' => $item['key']];

                                    if ($childKey !== '') {
                                        $childParams['submenu'] = $childKey;
                                    }

                                    $childUrl = route($child['route'], $childParams, false);
                                }
                            @endphp
                            <a
                                href="{{ $childUrl }}"
                                style="display:flex;align-items:center;gap:8px;padding:6px 14px 6px 12px;color:#fff;text-decoration:none;font-size:10px;font-weight:600;background:{{ $childIsCurrent ? '#31508f' : 'transparent' }};"
                            >
                                <i class="fa fa-angle-double-right" style="font-size:9px;opacity:.9;"></i>
                                <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $child['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <a
                    href="{{ route($item['route'], absolute: false) }}"
                    style="display:flex;align-items:center;gap:10px;padding:11px 10px;border-bottom:1px solid rgba(255,255,255,.06);color:#fff;text-decoration:none;font-size:11px;font-weight:600;background:{{ $itemIsActive ? '#1f3b7d' : 'transparent' }};"
                >
                    <span style="width:16px;text-align:center;font-size:13px;flex:0 0 16px;"><i class="{{ $item['icon'] }}"></i></span>
                    <span style="flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item['label'] }}</span>
                    @unless ($loop->first)
                        <span style="font-size:12px;"><i class="fa fa-angle-right"></i></span>
                    @endunless
                </a>
            @endif
        @endforeach
    </nav>
</aside>
