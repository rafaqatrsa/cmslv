@php
    $currentRouteName = request()->route()?->getName() ?? '';
    $isHrmsRoute = str_starts_with($currentRouteName, 'admin.hrms.');
    $isAccountRoute = str_starts_with($currentRouteName, 'admin.account.');
    $isAdminDashboardRoute = in_array($currentRouteName, ['admin.dashboard', 'admin.dashboard.clean', 'cmsc.admin.dashboard'], true);
    $isSystemSettingsRoute = $currentRouteName === 'admin.systemsettings.dashboard';
    $requestedMenu = request()->string('menu')->toString();
    $requestedSubmenu = request()->string('submenu')->toString();
    $currentSidebarMenu = match ($currentRouteName) {
        'admin.hrms.dashboard' => 'dashboard',
        'admin.hrms.documents.index' => 'manual_support',
        'admin.hrms.manual.index' => 'manual_support',
        'admin.hrms.staff.index',
        'admin.hrms.staff.profile',
        'admin.hrms.staff.edit' => 'staff_recruitment',
        'admin.account.accounts.dashboard.legacy' => 'accounts',
        'admin.account.documents.index' => 'manual_accounts',
        'admin.account.accounts.index',
        'admin.account.accounts.newaccounts',
        'admin.account.accounts.newaccounts.edit',
        'admin.account.accounts.accountshead',
        'admin.account.accounts.accountshead.edit',
        'admin.account.fee-master.index' => 'chart_of_accounts',
        'admin.account.student-fees.index' => 'fee_voucher',
        'admin.account.expenses.index',
        'admin.account.payments.index',
        'admin.account.receipts.index',
        'admin.account.contra.index',
        'admin.account.journal-vouchers.index' => 'accounting_records',
        'admin.account.payroll.index' => 'payroll_adv_clearance',
        'admin.account.item-categories.index',
        'admin.account.units.index',
        'admin.account.brands.index',
        'admin.account.product-types.index',
        'admin.account.products.index',
        'admin.account.stock.index',
        'admin.account.suppliers.index',
        'admin.account.class-book-sets.index',
        'admin.account.invoice-book-sets.index',
        'admin.account.invoice-book-set-returns.index',
        'admin.account.purchases.index',
        'admin.account.purchase-returns.index',
        'admin.account.sales.index',
        'admin.account.sales-returns.index' => 'inventory_process',
        'admin.account.royalty.index' => 'network_associate_account',
        default => null,
    };

    if ($requestedMenu !== '') {
        $currentSidebarMenu = $requestedMenu;
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

    $dashboardSidebarItems = [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'fa fa-television',
            'route' => 'admin.dashboard',
            'children' => [],
        ],
        [
            'key' => 'staff_recruitment',
            'label' => 'Staff Recruitment',
            'icon' => 'fa fa-users',
            'route' => 'admin.hrms.staff.index',
            'children' => [],
        ],
        [
            'key' => 'internal_external_communication',
            'label' => "Internal & External Comm'n",
            'icon' => 'fa fa-commenting-o',
            'route' => null,
            'children' => [],
        ],
        [
            'key' => 'customer_services_management',
            'label' => 'Customer Services Mgmt.',
            'icon' => 'fa fa-list-ol',
            'route' => 'admin.adm.complaints.index',
            'children' => [],
        ],
        [
            'key' => 'admission_process',
            'label' => 'Admission Process',
            'icon' => 'fa fa-user-plus',
            'route' => 'admin.adm.students.index',
            'children' => [],
        ],
        [
            'key' => 'withdrawal_process',
            'label' => 'Withdrawal Process',
            'icon' => 'fa fa-ban',
            'route' => 'admin.adm.students.index',
            'children' => [],
        ],
        [
            'key' => 'attendance_management',
            'label' => 'Attendance Mgmt.',
            'icon' => 'fa fa-calendar-check-o',
            'route' => 'admin.adm.student-attendance.index',
            'children' => [],
        ],
        [
            'key' => 'syllabus_management',
            'label' => 'Syllabus Management',
            'icon' => 'fa fa-building-o',
            'route' => 'admin.academics.dashboard',
            'children' => [],
        ],
        [
            'key' => 'effective_lesson_planning',
            'label' => 'Lesson Planning',
            'icon' => 'fa fa-calendar-check-o',
            'route' => 'admin.academics.lessons.index',
            'children' => [],
        ],
        [
            'key' => 'timetable_staffing',
            'label' => 'Timetable Staffing',
            'icon' => 'fa fa-clock-o',
            'route' => 'admin.academics.timetables.index',
            'children' => [],
        ],
        [
            'key' => 'homework',
            'label' => 'Homework',
            'icon' => 'fa fa-flask',
            'route' => 'admin.academics.homework.index',
            'children' => [],
        ],
        [
            'key' => 'paper_generate',
            'label' => 'Paper Generate',
            'icon' => 'fa fa-files-o',
            'route' => 'admin.academics.paper-generate.index',
            'children' => [],
        ],
        [
            'key' => 'examination',
            'label' => 'Examination',
            'icon' => 'fa fa-file-text-o',
            'route' => 'admin.academics.exam-groups.index',
            'children' => [],
        ],
        [
            'key' => 'test_system',
            'label' => 'Test System',
            'icon' => 'fa fa-file-o',
            'route' => 'admin.academics.test-groups.index',
            'children' => [],
        ],
        [
            'key' => 'fee_voucher',
            'label' => 'Fee Voucher',
            'icon' => 'fa fa-newspaper-o',
            'route' => 'admin.account.student-fees.index',
            'children' => [],
        ],
        [
            'key' => 'accounting_records',
            'label' => 'Accounting Records',
            'icon' => 'fa fa-money',
            'route' => 'admin.account.accounts.index',
            'children' => [],
        ],
    ];

    $systemSettingsSidebarItems = [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'fa fa-dashboard',
            'route' => 'admin.systemsettings.dashboard',
            'children' => [],
        ],
        [
            'key' => 'system_settings',
            'label' => 'System Settings',
            'icon' => 'fa fa-gears',
            'route' => null,
            'children' => [
                ['label' => 'General Settings', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Branch Settings', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Session Settings', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Notification Setting', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Whatsaap Messaging', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'SMS Setting', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Email Setting', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Modules Setting', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Roles Permissions', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Front CMS Setting', 'route' => 'admin.frontcms.index'],
            ],
        ],
    ];

    $accountSidebarItems = [
        [
            'key' => 'accounts',
            'label' => 'Dashboard',
            'icon' => 'fa fa-dashboard',
            'route' => 'admin.account.accounts.dashboard.legacy',
            'children' => [],
        ],
        [
            'key' => 'manual_accounts',
            'label' => 'Manual Support',
            'icon' => 'fa fa-life-ring',
            'route' => null,
            'children' => [
                ['label' => 'Add Documents', 'route' => 'admin.account.documents.index'],
                ['label' => 'Policy Manual', 'route' => 'admin.account.documents.index'],
                ['label' => 'Flow Charts', 'route' => 'admin.account.documents.index'],
                ['label' => 'Supportive Documents', 'route' => 'admin.account.documents.index'],
                ['label' => 'Registers', 'route' => 'admin.account.documents.index'],
                ['label' => 'Video Supports', 'route' => 'admin.account.documents.index'],
            ],
        ],
        [
            'key' => 'chart_of_accounts',
            'label' => 'Chart Of Accounts',
            'icon' => 'fa fa-list',
            'route' => null,
            'children' => [
                ['label' => 'Add Accounts Type', 'route' => 'admin.account.accounts.newaccounts'],
                ['label' => 'Add New Accounts', 'route' => 'admin.account.accounts.accountshead'],
                ['label' => 'Chart Of Accounts', 'route' => 'admin.account.accounts.index'],
                ['label' => 'Fee Structure', 'route' => 'admin.account.fee-master.index'],
            ],
        ],
        [
            'key' => 'fee_voucher',
            'label' => 'Fee Voucher',
            'icon' => 'fa fa-file-invoice-dollar',
            'route' => null,
            'children' => [
                ['label' => 'Fee Revise', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Assign Dues', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Assign Fee Voucher', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Assign Fee Voucher Date Wise', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Fee Voucher Student Sibling', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Fee Voucher', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Custom Fee Voucher', 'route' => 'admin.account.student-fees.index'],
            ],
        ],
        [
            'key' => 'accounting_records',
            'label' => 'Accounting Records',
            'icon' => 'fa fa-money-bill',
            'route' => null,
            'children' => [
                ['label' => 'Expense Bill', 'route' => 'admin.account.expenses.index'],
                ['label' => 'Payment Voucher', 'route' => 'admin.account.payments.index'],
                ['label' => 'Receipt Voucher', 'route' => 'admin.account.receipts.index'],
                ['label' => 'Contra Voucher', 'route' => 'admin.account.contra.index'],
                ['label' => 'JV', 'route' => 'admin.account.journal-vouchers.index'],
                ['label' => 'Fee Collect', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Import Fee', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Cash Book', 'route' => 'admin.account.accounts.index'],
            ],
        ],
        [
            'key' => 'payroll_adv_clearance',
            'label' => 'Payroll/Advance/Clearance',
            'icon' => 'fa fa-indent',
            'route' => null,
            'children' => [
                ['label' => 'Payroll', 'route' => 'admin.account.payroll.index'],
            ],
        ],
        [
            'key' => 'inventory_process',
            'label' => 'Inventory Process',
            'icon' => 'fa fa-shopping-cart',
            'route' => null,
            'children' => [
                ['label' => 'Item Category', 'route' => 'admin.account.item-categories.index'],
                ['label' => 'Units', 'route' => 'admin.account.units.index'],
                ['label' => 'Brands', 'route' => 'admin.account.brands.index'],
                ['label' => 'Products / Services', 'route' => 'admin.account.products.index'],
                ['label' => 'Stock', 'route' => 'admin.account.stock.index'],
                ['label' => 'Supplier', 'route' => 'admin.account.suppliers.index'],
                ['label' => 'Classes Book Sets', 'route' => 'admin.account.class-book-sets.index'],
                ['label' => 'Invoice Book Sets', 'route' => 'admin.account.invoice-book-sets.index'],
                ['label' => 'Invoice Book Sets Return', 'route' => 'admin.account.invoice-book-set-returns.index'],
                ['label' => 'Purchases', 'route' => 'admin.account.purchases.index'],
                ['label' => 'Purchase Return', 'route' => 'admin.account.purchase-returns.index'],
                ['label' => 'Sale Invoice', 'route' => 'admin.account.sales.index'],
                ['label' => 'Sales Return', 'route' => 'admin.account.sales-returns.index'],
            ],
        ],
        [
            'key' => 'network_associate_account',
            'label' => 'Network Associate Account',
            'icon' => 'fa fa-sitemap',
            'route' => null,
            'children' => [
                ['label' => 'Assign Royalty Voucher', 'route' => 'admin.account.royalty.index'],
                ['label' => 'Collect Royalty', 'route' => 'admin.account.royalty.index'],
            ],
        ],
        [
            'key' => 'account_reports_reviews',
            'label' => 'Reports & Reviews',
            'icon' => 'fa fa-bar-chart',
            'route' => null,
            'children' => [
                ['label' => 'General Report', 'route' => 'admin.account.accounts.index'],
                ['label' => 'Incomes / Fee Report', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Expenses Report', 'route' => 'admin.account.expenses.index'],
                ['label' => 'Payroll Report', 'route' => 'admin.account.payroll.index'],
                ['label' => 'Inventory Reports', 'route' => 'admin.account.purchases.index'],
            ],
        ],
    ];

    $sidebarItems = $isAdminDashboardRoute
        ? $dashboardSidebarItems
        : ($isSystemSettingsRoute ? $systemSettingsSidebarItems : ($isAccountRoute ? $accountSidebarItems : $hrmsSidebarItems));
@endphp

<style>
    .admin-sidebar-tree {
        overflow: hidden;
        max-height: 0;
        background: #3d5fa7;
        transition: max-height .24s ease;
    }

    .admin-sidebar-tree.is-open {
        max-height: var(--sidebar-tree-height, 520px);
    }
</style>

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
                $menuIsActive = ($item['key'] ?? null) !== null && ($item['key'] ?? null) === $currentSidebarMenu;
                $childRouteIsActive = collect($item['children'])->contains(
                    fn (array $child): bool => request()->routeIs($child['route'])
                );
                $hasCurrentSidebarMenu = $currentSidebarMenu !== null && $currentSidebarMenu !== '';
                $isExpanded = $itemIsActive || $menuIsActive || (! $hasCurrentSidebarMenu && $childRouteIsActive);
            @endphp

            @if ($item['children'] !== [])
                <div style="border-bottom:1px solid rgba(255,255,255,.06);">
                    <a
                        data-sidebar-toggle
                        data-sidebar-target="sidebar-menu-{{ $item['key'] }}"
                        aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                        href="{{ $item['route'] ? route($item['route'], absolute: false) : request()->fullUrlWithQuery(['menu' => $item['key']]) }}"
                        style="display:flex;align-items:center;gap:10px;padding:11px 10px;color:#fff;text-decoration:none;font-size:11px;font-weight:600;background:{{ $isExpanded ? '#3b61ad' : 'transparent' }};"
                    >
                        <span style="width:16px;text-align:center;font-size:13px;flex:0 0 16px;"><i class="{{ $item['icon'] }}"></i></span>
                        <span style="flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item['label'] }}</span>
                        <span style="font-size:12px;"><i data-sidebar-chevron class="fa fa-angle-{{ $isExpanded ? 'down' : 'right' }}"></i></span>
                    </a>

                    <div
                        id="sidebar-menu-{{ $item['key'] }}"
                        class="admin-sidebar-tree {{ $isExpanded ? 'is-open' : '' }}"
                        style="{{ $isExpanded ? '--sidebar-tree-height:520px;' : '' }}padding:2px 0;"
                    >
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
                    href="{{ $item['route'] ? route($item['route'], absolute: false) : '#' }}"
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggles = Array.prototype.slice.call(document.querySelectorAll('[data-sidebar-toggle]'));

        toggles.forEach(function (toggle) {
            var target = document.getElementById(toggle.getAttribute('data-sidebar-target'));

            if (!target) {
                return;
            }

            if (target.classList.contains('is-open')) {
                target.style.setProperty('--sidebar-tree-height', target.scrollHeight + 'px');
            }

            toggle.addEventListener('click', function (event) {
                event.preventDefault();

                var shouldOpen = !target.classList.contains('is-open');

                toggles.forEach(function (otherToggle) {
                    var otherTarget = document.getElementById(otherToggle.getAttribute('data-sidebar-target'));
                    var otherChevron = otherToggle.querySelector('[data-sidebar-chevron]');

                    if (!otherTarget) {
                        return;
                    }

                    otherTarget.style.setProperty('--sidebar-tree-height', otherTarget.scrollHeight + 'px');
                    otherTarget.classList.remove('is-open');
                    otherToggle.setAttribute('aria-expanded', 'false');
                    otherToggle.style.background = 'transparent';

                    if (otherChevron) {
                        otherChevron.className = 'fa fa-angle-right';
                    }
                });

                if (shouldOpen) {
                    var chevron = toggle.querySelector('[data-sidebar-chevron]');

                    target.style.setProperty('--sidebar-tree-height', target.scrollHeight + 'px');
                    target.classList.add('is-open');
                    toggle.setAttribute('aria-expanded', 'true');
                    toggle.style.background = '#3b61ad';

                    if (chevron) {
                        chevron.className = 'fa fa-angle-down';
                    }
                }
            });
        });
    });
</script>
