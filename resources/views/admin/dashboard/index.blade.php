@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $metricCards = [
            ['label' => 'ADMISSION INQUIRY', 'value' => $stats['admission_inquiries'] ?? 0, 'meta' => 'TODAY : '.($stats['admission_inquiries_today'] ?? 0).' WON : '.($stats['admission_inquiries_won'] ?? 0), 'image' => 'admission_inquiry.png', 'color' => 'text-red-500', 'style' => 'color:#ff1f3d', 'href' => route('admin.adm.enquiries.index', absolute: false), 'target' => '_blank'],
            ['label' => 'REGISTRATION', 'value' => $stats['registrations'] ?? 0, 'meta' => 'SELF : '.($stats['registrations_self'] ?? 0).' ONLINE : '.($stats['registrations_online'] ?? 0), 'image' => 'student_regd.png', 'color' => 'text-neutral-700', 'style' => 'color:#111827', 'href' => route('admin.adm.student-registrations.index', absolute: false), 'target' => '_blank'],
            ['label' => 'ADMISSION', 'value' => $stats['admissions'] ?? 0, 'meta' => 'TODAY : '.($stats['admissions_today'] ?? 0).' LEAVING : 0', 'image' => 'admission.png', 'color' => 'text-emerald-500', 'style' => 'color:#00a651', 'href' => route('admin.adm.students.index', absolute: false), 'target' => '_blank'],
            ['label' => 'STUDENTS', 'value' => $stats['students'] ?? 0, 'meta' => 'P : 0 A : 0 L : 0', 'image' => 'students.png', 'color' => 'text-orange-500', 'style' => 'color:#ff7a00', 'href' => route('admin.adm.students.index', absolute: false), 'target' => '_blank'],
            ['label' => 'ADMIN STAFF', 'value' => $stats['admin_staff'] ?? 0, 'meta' => 'P : 0 A : 0 L : 0', 'image' => 'admindb.png', 'color' => 'text-pink-500', 'style' => 'color:#ff008c', 'href' => route('admin.hrms.staff.index', absolute: false)],
            ['label' => 'TEACHING STAFF', 'value' => $stats['teaching_staff'] ?? 0, 'meta' => 'P : 0 A : 0 L : 0', 'image' => 'staff.png', 'color' => 'text-slate-500', 'style' => 'color:#64748b', 'href' => route('admin.hrms.staff.index', absolute: false)],
            ['label' => 'ALLIED STAFF', 'value' => $stats['allied_staff'] ?? 0, 'meta' => 'P : 0 A : 0 L : 0', 'image' => 'staff-a.png', 'color' => 'text-lime-500', 'style' => 'color:#84cc16', 'href' => route('admin.hrms.staff.index', absolute: false)],
            ['label' => 'FAMILIES', 'value' => $stats['families'] ?? 0, 'meta' => '', 'image' => 'family.png', 'color' => 'text-sky-500', 'style' => 'color:#0ea5e9', 'href' => route('admin.adm.siblings.index', absolute: false)],
            ['label' => 'COMPLAIN', 'value' => $stats['complaints'] ?? 0, 'meta' => 'TODAY : 0', 'image' => 'complaint.png', 'color' => 'text-purple-600', 'style' => 'color:#7e22ce', 'href' => route('admin.adm.complaints.index', absolute: false)],
            ['label' => 'VISITORS', 'value' => $stats['visitors'] ?? 0, 'meta' => 'TODAY : 0', 'image' => 'visitor.png', 'color' => 'text-amber-700', 'style' => 'color:#92400e', 'href' => route('admin.adm.visitor-purposes.index', absolute: false)],
            ['label' => 'PURCHASE', 'value' => $stats['purchases'] ?? 0, 'meta' => 'TODAY : 0', 'image' => 'purchase.png', 'color' => 'text-orange-600', 'style' => 'color:#ff4b1f', 'href' => route('admin.account.purchases.index', absolute: false)],
            ['label' => 'SALES', 'value' => $stats['sales'] ?? 0, 'meta' => 'TODAY : 0', 'image' => 'sales.png', 'color' => 'text-green-600', 'style' => 'color:#00a000', 'href' => route('admin.account.sales.index', absolute: false)],
        ];

        $moduleTabs = [
            ['label' => 'DASHBOARD', 'icon' => 'fa-solid fa-desktop', 'target' => 'dashboard', 'active' => true],
            ['label' => 'MODULES', 'icon' => 'fa-solid fa-cubes', 'target' => 'modules'],
            ['label' => 'REPORTS', 'icon' => 'fa-solid fa-chart-column', 'target' => 'reports'],
            ['label' => 'CMS', 'icon' => 'fa-solid fa-building-columns', 'target' => 'cms'],
            ['label' => 'LMS', 'icon' => 'fa-solid fa-book', 'target' => 'lms'],
            ['label' => 'SYSTEM SETTINGS', 'icon' => 'fa-solid fa-gears', 'href' => route('admin.systemsettings.dashboard', absolute: false)],
        ];

        $moduleSections = [
            [
                'label' => 'HRMS',
                'icon' => 'fa-solid fa-users',
                'links' => [
                    ['label' => 'HRMS Dashboard', 'href' => route('admin.hrms.dashboard', absolute: false)],
                    ['label' => 'Staff Directory', 'href' => route('admin.hrms.staff.index', absolute: false)],
                    ['label' => 'HRMS Documents', 'href' => route('admin.hrms.documents.index', absolute: false)],
                ],
            ],
            [
                'label' => 'ADMINISTRATION',
                'icon' => 'fa-solid fa-user-plus',
                'links' => [
                    ['label' => 'ADM Dashboard', 'href' => route('admin.adm.dashboard', absolute: false)],
                    ['label' => 'Admission Enquiries', 'href' => route('admin.adm.enquiries.index', absolute: false)],
                    ['label' => 'Student Registration', 'href' => route('admin.adm.student-registrations.index', absolute: false)],
                    ['label' => 'Students', 'href' => route('admin.adm.students.index', absolute: false)],
                    ['label' => 'Attendance', 'href' => route('admin.adm.attendance.index', absolute: false)],
                    ['label' => 'Complaints', 'href' => route('admin.adm.complaints.index', absolute: false)],
                ],
            ],
            [
                'label' => 'ACADEMICS',
                'icon' => 'fa-solid fa-book',
                'links' => [
                    ['label' => 'Academics Dashboard', 'href' => route('admin.academics.dashboard', absolute: false)],
                    ['label' => 'Subject Groups', 'href' => route('admin.academics.subject-groups.index', absolute: false)],
                    ['label' => 'Subjects', 'href' => route('admin.academics.subjects.index', absolute: false)],
                    ['label' => 'Teachers', 'href' => route('admin.academics.teachers.index', absolute: false)],
                    ['label' => 'Exam Groups', 'href' => route('admin.academics.exam-groups.index', absolute: false)],
                ],
            ],
            [
                'label' => 'ACCOUNTS & FINANCE',
                'icon' => 'fa-solid fa-calculator',
                'links' => [
                    ['label' => 'Accounts Dashboard', 'href' => route('admin.account.accounts.dashboard.legacy', absolute: false)],
                    ['label' => 'Fee Master', 'href' => route('admin.account.fee-master.index', absolute: false)],
                    ['label' => 'Student Fees', 'href' => route('admin.account.student-fees.index', absolute: false)],
                    ['label' => 'Purchases', 'href' => route('admin.account.purchases.index', absolute: false)],
                    ['label' => 'Sales', 'href' => route('admin.account.sales.index', absolute: false)],
                ],
            ],
            [
                'label' => 'SYSTEM SETTINGS',
                'icon' => 'fa-solid fa-gears',
                'links' => [
                    ['label' => 'General Settings', 'href' => route('admin.systemsettings.dashboard', absolute: false)],
                    ['label' => 'Front CMS Settings', 'href' => route('admin.frontcms.index', absolute: false)],
                ],
            ],
        ];

        $cmsCards = [
            ['label' => 'HRMS', 'image' => 'human-resources.png', 'href' => '/admin/hrms/hrm/dashboard'],
            ['label' => 'ADMINISTRATION', 'image' => 'admin.png', 'href' => '/admin/adm/admn/dashboard'],
            ['label' => 'ACADEMICS', 'image' => 'education.png', 'href' => '/admin/academics/acadm/dashboard'],
            ['label' => 'ACCOUNTS & FINANCE', 'image' => 'accounting.png', 'href' => '/admin/account/accounts/dashboard'],
        ];

        $reportCards = [
            ['label' => 'REPORTS OVERVIEW', 'icon' => 'fa-solid fa-chart-line', 'href' => route('admin.report.index', absolute: false)],
            ['label' => 'SYSTEM NOTIFICATIONS', 'icon' => 'fa-regular fa-bell', 'href' => route('admin.system-notification.index', absolute: false)],
            ['label' => 'MEMBERSHIP', 'icon' => 'fa-solid fa-id-card', 'href' => route('admin.membership.index', absolute: false)],
            ['label' => 'QMS', 'icon' => 'fa-solid fa-diagram-project', 'href' => route('admin.qms.index', absolute: false)],
        ];

        $lmsCards = [
            ['label' => 'ACADEMICS', 'image' => 'education.png', 'href' => '/admin/academics/acadm/dashboard'],
        ];

        $progressPanels = [
            'Admission Enquiry For Jul 2026' => [
                ['label' => ($stats['enquiry_overview']['active']['count'] ?? 0).' ACTIVE', 'value' => ($stats['enquiry_overview']['active']['percentage'] ?? 0).'%','width' => ($stats['enquiry_overview']['active']['percentage'] ?? 0).'%', 'color' => 'bg-red-500', 'style' => 'background:#ef4444'],
                ['label' => ($stats['enquiry_overview']['won']['count'] ?? 0).' WON', 'value' => ($stats['enquiry_overview']['won']['percentage'] ?? 0).'%','width' => ($stats['enquiry_overview']['won']['percentage'] ?? 0).'%', 'color' => 'bg-amber-500', 'style' => 'background:#f59e0b'],
                ['label' => ($stats['enquiry_overview']['passive']['count'] ?? 0).' PASSIVE', 'value' => ($stats['enquiry_overview']['passive']['percentage'] ?? 0).'%','width' => ($stats['enquiry_overview']['passive']['percentage'] ?? 0).'%', 'color' => 'bg-orange-400', 'style' => 'background:#fb923c'],
                ['label' => ($stats['enquiry_overview']['lost']['count'] ?? 0).' LOST', 'value' => ($stats['enquiry_overview']['lost']['percentage'] ?? 0).'%','width' => ($stats['enquiry_overview']['lost']['percentage'] ?? 0).'%', 'color' => 'bg-neutral-400', 'style' => 'background:#a3a3a3'],
                ['label' => ($stats['enquiry_overview']['dead']['count'] ?? 0).' DEAD', 'value' => ($stats['enquiry_overview']['dead']['percentage'] ?? 0).'%','width' => ($stats['enquiry_overview']['dead']['percentage'] ?? 0).'%', 'color' => 'bg-neutral-400', 'style' => 'background:#a3a3a3'],
            ],
            'Student Today Attendance' => [
                ['label' => 'PRESENT', 'value' => '', 'width' => '0%', 'color' => 'bg-green-500', 'style' => 'background:#22c55e'],
                ['label' => 'ABSENT', 'value' => '', 'width' => '0%', 'color' => 'bg-red-500', 'style' => 'background:#ef4444'],
                ['label' => 'LEAVE', 'value' => '', 'width' => '0%', 'color' => 'bg-blue-500', 'style' => 'background:#3b82f6'],
                ['label' => 'LATE', 'value' => '', 'width' => '0%', 'color' => 'bg-amber-500', 'style' => 'background:#f59e0b'],
                ['label' => 'HALF DAY', 'value' => '', 'width' => '0%', 'color' => 'bg-purple-500', 'style' => 'background:#a855f7'],
            ],
            'Staff Today Attendance' => [
                ['label' => 'PRESENT', 'value' => '', 'width' => '0%', 'color' => 'bg-green-500', 'style' => 'background:#22c55e'],
                ['label' => 'RED LEAVE', 'value' => '', 'width' => '0%', 'color' => 'bg-red-500', 'style' => 'background:#ef4444'],
                ['label' => 'BLUE LEAVE', 'value' => '', 'width' => '0%', 'color' => 'bg-blue-500', 'style' => 'background:#3b82f6'],
                ['label' => 'GREEN LEAVE', 'value' => '', 'width' => '0%', 'color' => 'bg-emerald-500', 'style' => 'background:#10b981'],
                ['label' => 'LATE', 'value' => '', 'width' => '0%', 'color' => 'bg-amber-500', 'style' => 'background:#f59e0b'],
                ['label' => 'HALF DAY', 'value' => '', 'width' => '0%', 'color' => 'bg-purple-500', 'style' => 'background:#a855f7'],
            ],
        ];
    @endphp

    <section class="admin-dashboard-section overflow-hidden rounded border border-neutral-300 bg-white shadow-sm">
        <div class="admin-module-tabs flex flex-wrap gap-4 border-b border-amber-300 px-3 py-3">
            @foreach ($moduleTabs as $tab)
                @if (isset($tab['href']))
                    <a
                        href="{{ $tab['href'] }}"
                        class="admin-module-tab {{ ($tab['active'] ?? false) ? 'is-active bg-[#2f61b3] text-white' : 'bg-white text-neutral-800' }}"
                    >
                        <i class="{{ $tab['icon'] }}"></i>
                        <span>{{ $tab['label'] }}</span>
                    </a>
                @else
                    <a
                        href="#{{ $tab['target'] }}"
                        data-dashboard-tab="{{ $tab['target'] }}"
                        aria-controls="{{ $tab['target'] }}"
                        aria-selected="{{ ($tab['active'] ?? false) ? 'true' : 'false' }}"
                        class="admin-module-tab {{ ($tab['active'] ?? false) ? 'is-active bg-[#2f61b3] text-white' : 'bg-white text-neutral-800' }}"
                    >
                        <i class="{{ $tab['icon'] }}"></i>
                        <span>{{ $tab['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </div>

        <div id="dashboard" data-dashboard-pane class="space-y-0">
            <div class="admin-dashboard-grid grid gap-3 p-3 xl:grid-cols-[minmax(0,4fr)_minmax(260px,1fr)_minmax(260px,1fr)]">
                <div class="admin-metric-grid grid gap-3 md:grid-cols-2 2xl:grid-cols-4">
                    @foreach ($metricCards as $card)
                        <a href="{{ $card['href'] }}" @isset($card['target']) target="{{ $card['target'] }}" rel="noopener" @endisset class="admin-metric-card flex min-h-[96px] items-center gap-3 rounded-xl border border-neutral-300 bg-white px-3 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                            <div class="admin-metric-icon w-10 shrink-0 text-center leading-none">
                                <img src="{{ asset('assets/images/db/'.$card['image']) }}" alt="{{ $card['label'] }}" class="mx-auto h-10 w-10 object-contain">
                            </div>
                            <div class="min-w-0">
                                <p class="admin-card-title {{ $card['color'] }} text-sm font-medium" style="{{ $card['style'] }}">{{ $card['label'] }}</p>
                                <p class="admin-card-value text-sm font-bold text-blue-700">{{ $card['value'] }}</p>
                                @if ($card['meta'])
                                    <p class="admin-card-meta {{ $card['color'] }} text-sm font-semibold" style="{{ $card['style'] }}">{{ $card['meta'] }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <article class="admin-summary-card flex min-h-[248px] flex-col justify-end rounded-xl border border-neutral-300 bg-white p-3 shadow-sm">
                    <div class="admin-stat-line mb-2 flex items-center justify-between border-b border-neutral-200 pb-2 text-sm text-emerald-600">
                        <span><i class="fa-solid fa-user"></i> Students</span>
                        <span class="rounded border border-emerald-500 px-1">{{ $stats['students'] ?? 0 }}</span>
                    </div>
                    <div class="admin-stat-line mb-2 flex items-center justify-between border-b border-neutral-200 pb-2 text-sm text-sky-500">
                        <span><i class="fa-solid fa-child"></i> Boys</span>
                        <span class="rounded border border-sky-500 px-1">{{ $stats['male_students'] ?? 0 }}</span>
                    </div>
                    <div class="admin-stat-line flex items-center justify-between text-sm text-pink-600">
                        <span><i class="fa-solid fa-child-dress"></i> Girls</span>
                        <span class="rounded border border-pink-500 px-1">{{ $stats['female_students'] ?? 0 }}</span>
                    </div>
                </article>

                <article class="admin-summary-card admin-donut-card flex min-h-[248px] flex-col justify-center rounded-xl border border-neutral-300 bg-white p-3 shadow-sm">
                    <div class="admin-donut"></div>
                    <div class="admin-stat-line mb-2 flex items-center justify-between border-b border-neutral-200 pb-2 text-sm text-emerald-600">
                        <span><i class="fa-solid fa-user"></i> Staff</span>
                        <span class="rounded border border-emerald-500 px-1">{{ $stats['staff'] ?? 0 }}</span>
                    </div>
                    <div class="admin-stat-line mb-2 flex items-center justify-between border-b border-neutral-200 pb-2 text-sm text-sky-500">
                        <span><i class="fa-solid fa-mars"></i> Male</span>
                        <span class="rounded border border-sky-500 px-1">{{ $stats['male_staff'] ?? 0 }}</span>
                    </div>
                    <div class="admin-stat-line flex items-center justify-between text-sm text-pink-600">
                        <span><i class="fa-solid fa-venus"></i> Female</span>
                        <span class="rounded border border-pink-500 px-1">{{ $stats['female_staff'] ?? 0 }}</span>
                    </div>
                </article>
            </div>

            <div class="admin-panels-row grid gap-3 px-3 pb-3 xl:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
                <div class="admin-three-panels grid gap-3 lg:grid-cols-3">
                    @foreach ($progressPanels as $title => $rows)
                        <section class="admin-panel overflow-hidden rounded-xl bg-white shadow-lg">
                            <h2 class="admin-panel-title bg-[#2f61b3] px-2 py-1 text-sm font-semibold text-white">{{ $title }}</h2>
                            <div class="admin-progress-body space-y-4 p-3">
                                @foreach ($rows as $row)
                                    <div class="admin-progress-row">
                                        <div class="admin-progress-label mb-3 flex items-center justify-between text-lg">
                                            <span>{{ $row['label'] }}</span>
                                            <span>{{ $row['value'] }}</span>
                                        </div>
                                        <div class="admin-progress-track h-1.5 bg-neutral-200">
                                            <div class="admin-progress-fill h-full {{ $row['color'] }}" style="width: {{ $row['width'] }}; {{ $row['style'] }}"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

                <section class="admin-panel overflow-hidden rounded-xl bg-white shadow-lg">
                    <div class="admin-panel-title flex items-center justify-between bg-[#2f61b3] px-2 py-1 text-sm font-semibold text-white">
                        <span>Complains For {{ now()->format('M Y') }}</span>
                        <span>Today : 0 / Total : {{ $stats['complaints'] ?? 0 }} / Solved : 0</span>
                    </div>
                    <div class="min-h-[288px]"></div>
                </section>
            </div>

            <div class="admin-fees-row grid gap-4 px-3 pb-4 xl:grid-cols-[minmax(0,2fr)_minmax(360px,0.8fr)]">
                <section class="admin-panel overflow-hidden rounded-xl bg-white shadow-lg">
                    <h2 class="admin-panel-title bg-[#2f61b3] px-2 py-1 text-sm font-semibold text-white">Fees Collection Statistics For - {{ now()->format('M Y') }}</h2>
                    <div class="px-4 py-5 text-center text-base">
                        RECEIVABLE: 0 / &nbsp;&nbsp; COLLECTION: 0 / &nbsp;&nbsp; WAIVE OFF: 0 / &nbsp;&nbsp; BALANCE: 0 / &nbsp;&nbsp; TODAY COLLECTION: 0
                    </div>
                </section>

                <section class="admin-panel overflow-hidden rounded-xl bg-white shadow-lg">
                    <h2 class="admin-panel-title bg-[#2f61b3] px-2 py-1 text-sm font-semibold text-white">Fee Overview</h2>
                    <div class="flex items-center justify-between px-3 py-5 text-lg">
                        <span>0 PAID</span>
                        <span class="text-sky-600">0%</span>
                    </div>
                </section>
            </div>
        </div>

        <div id="modules" data-dashboard-pane class="hidden p-[10px]">
            <div class="grid gap-4 lg:grid-cols-5">
                @foreach ($moduleSections as $section)
                    <section class="legacy-module-section">
                        <h2><i class="{{ $section['icon'] }}"></i> {{ $section['label'] }}</h2>
                        <ul>
                            @foreach ($section['links'] as $link)
                                <li><a href="{{ $link['href'] }}"><i class="fa-solid fa-angle-double-right"></i> {{ $link['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </section>
                @endforeach
            </div>
        </div>

        <div id="reports" data-dashboard-pane class="hidden p-3">
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($reportCards as $card)
                    <a href="{{ $card['href'] }}" class="flex min-h-[110px] items-center gap-3 rounded-xl border border-neutral-300 bg-white px-4 py-5 shadow-sm transition hover:border-[#2f61b3] hover:shadow-md">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-[#eef4ff] text-2xl text-[#2f61b3]">
                            <i class="{{ $card['icon'] }}"></i>
                        </span>
                        <span class="text-sm font-semibold tracking-wide text-neutral-800">{{ $card['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div id="cms" data-dashboard-pane class="hidden p-[10px]">
            <div class="legacy-dashboard-card-grid">
                @foreach ($cmsCards as $card)
                    <a href="{{ $card['href'] }}" class="legacy-dashboard-card">
                        <img src="{{ asset('assets/images/db/'.$card['image']) }}" alt="{{ $card['label'] }}">
                        <span>{{ $card['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div id="lms" data-dashboard-pane class="hidden p-[10px]">
            <div class="legacy-dashboard-card-grid">
                @foreach ($lmsCards as $card)
                    <a href="{{ $card['href'] }}" class="legacy-dashboard-card">
                        <img src="{{ asset('assets/images/db/'.$card['image']) }}" alt="{{ $card['label'] }}">
                        <span>{{ $card['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .legacy-dashboard-card-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 5px;
        }

        .legacy-dashboard-card {
            display: flex;
            min-height: 48px;
            align-items: flex-start;
            gap: 10px;
            border: 1px solid #000;
            border-radius: 7px;
            background: #fff;
            padding: 5px 8px;
            color: #000;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.1;
            text-decoration: none;
        }

        .legacy-dashboard-card:hover {
            color: #000;
            text-decoration: none;
        }

        .legacy-dashboard-card img {
            width: 34px;
            height: 34px;
            object-fit: contain;
            flex: 0 0 34px;
        }

        .legacy-module-section {
            min-height: 220px;
            border: 1px solid #d4d4d4;
            border-radius: 7px;
            background: #fff;
            box-shadow: 0 2px 6px rgba(15, 23, 42, .08);
        }

        .legacy-module-section h2 {
            margin: 0;
            border-bottom: 1px solid #d4d4d4;
            padding: 10px;
            color: #2f5da8;
            font-size: 14px;
            font-weight: 700;
        }

        .legacy-module-section ul {
            margin: 0;
            padding: 6px 0;
            list-style: none;
        }

        .legacy-module-section li a {
            display: block;
            padding: 7px 10px;
            color: #333;
            font-size: 12px;
            text-decoration: none;
        }

        .legacy-module-section li a:hover {
            background: #eef4ff;
            color: #2f5da8;
        }

        @media (max-width: 1024px) {
            .legacy-dashboard-card-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .legacy-dashboard-card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (() => {
            const tabLinks = Array.from(document.querySelectorAll('[data-dashboard-tab]'));
            const panes = Array.from(document.querySelectorAll('[data-dashboard-pane]'));

            if (tabLinks.length === 0 || panes.length === 0) {
                return;
            }

            const activateTab = (target) => {
                const activeTarget = panes.some((pane) => pane.id === target) ? target : 'dashboard';

                tabLinks.forEach((link) => {
                    const isActive = link.dataset.dashboardTab === activeTarget;

                    link.classList.toggle('is-active', isActive);
                    link.classList.toggle('bg-[#2f61b3]', isActive);
                    link.classList.toggle('text-white', isActive);
                    link.classList.toggle('bg-white', !isActive);
                    link.classList.toggle('text-neutral-800', !isActive);
                    link.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                panes.forEach((pane) => {
                    pane.classList.toggle('hidden', pane.id !== activeTarget);
                });
            };

            tabLinks.forEach((link) => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();

                    const target = link.dataset.dashboardTab ?? 'dashboard';
                    activateTab(target);
                });
            });

            const initialTarget = window.location.hash.replace('#', '');

            activateTab(initialTarget);

            if (initialTarget !== '') {
                window.history.replaceState(null, '', `${window.location.pathname}${window.location.search}`);
            }
        })();
    </script>
@endpush
