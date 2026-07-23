@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $metricCards = [
            ['label' => 'ADMISSION INQUIRY', 'value' => $stats['admission_inquiries'] ?? 14, 'meta' => 'TODAY : 0 WON : 6', 'icon' => 'fa-regular fa-clipboard', 'color' => 'text-red-500', 'style' => 'color:#ff1f3d'],
            ['label' => 'REGISTRATION', 'value' => $stats['registrations'] ?? 0, 'meta' => 'SELF : 0 ONLINE : 0', 'icon' => 'fa-regular fa-clipboard', 'color' => 'text-neutral-700', 'style' => 'color:#111827'],
            ['label' => 'ADMISSION', 'value' => $stats['admissions'] ?? 0, 'meta' => 'TODAY : 0 LEAVING : 0', 'icon' => 'fa-regular fa-id-badge', 'color' => 'text-emerald-500', 'style' => 'color:#00a651'],
            ['label' => 'STUDENTS', 'value' => $stats['students'] ?? 0, 'meta' => 'P : 0 A : 0 L : 0', 'icon' => 'fa-solid fa-user-graduate', 'color' => 'text-orange-500', 'style' => 'color:#ff7a00'],
            ['label' => 'ADMIN STAFF', 'value' => $stats['admin_staff'] ?? 0, 'meta' => 'P : 0 A : 0 L : 0', 'icon' => 'fa-solid fa-users-gear', 'color' => 'text-pink-500', 'style' => 'color:#ff008c'],
            ['label' => 'TEACHING STAFF', 'value' => $stats['teaching_staff'] ?? 1, 'meta' => 'P : 0 A : 0 L : 0', 'icon' => 'fa-solid fa-users', 'color' => 'text-slate-500', 'style' => 'color:#64748b'],
            ['label' => 'ALLIED STAFF', 'value' => $stats['allied_staff'] ?? 0, 'meta' => 'P : 0 A : 0 L : 0', 'icon' => 'fa-solid fa-people-group', 'color' => 'text-lime-500', 'style' => 'color:#84cc16'],
            ['label' => 'FAMILIES', 'value' => $stats['families'] ?? 0, 'meta' => '', 'icon' => 'fa-solid fa-people-roof', 'color' => 'text-sky-500', 'style' => 'color:#0ea5e9'],
            ['label' => 'COMPLAIN', 'value' => $stats['complaints'] ?? 0, 'meta' => 'TODAY : 0', 'icon' => 'fa-regular fa-rectangle-list', 'color' => 'text-purple-600', 'style' => 'color:#7e22ce'],
            ['label' => 'VISITORS', 'value' => $stats['visitors'] ?? 0, 'meta' => 'TODAY : 0', 'icon' => 'fa-regular fa-building', 'color' => 'text-amber-700', 'style' => 'color:#92400e'],
            ['label' => 'PURCHASE', 'value' => $stats['purchases'] ?? 0, 'meta' => 'TODAY : 0', 'icon' => 'fa-solid fa-cart-shopping', 'color' => 'text-orange-600', 'style' => 'color:#ff4b1f'],
            ['label' => 'SALES', 'value' => $stats['sales'] ?? 0, 'meta' => 'TODAY : 0', 'icon' => 'fa-solid fa-money-bill-trend-up', 'color' => 'text-green-600', 'style' => 'color:#00a000'],
        ];

        $moduleTabs = [
            ['label' => 'DASHBOARD', 'icon' => 'fa-solid fa-desktop', 'target' => 'dashboard', 'active' => true],
            ['label' => 'MODULES', 'icon' => 'fa-solid fa-cubes', 'target' => 'modules'],
            ['label' => 'REPORTS', 'icon' => 'fa-solid fa-chart-column', 'target' => 'reports'],
            ['label' => 'CMS', 'icon' => 'fa-solid fa-building-columns', 'target' => 'cms'],
            ['label' => 'LMS', 'icon' => 'fa-solid fa-book', 'target' => 'lms'],
            ['label' => 'SYSTEM SETTINGS', 'icon' => 'fa-solid fa-gears', 'href' => route('admin.systemsettings.dashboard', absolute: false)],
        ];

        $moduleCards = [
            ['label' => 'FRONT CMS SETTING', 'image' => 'cms.png', 'href' => '/admin/frontcms'],
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
                ['label' => '1 ACTIVE', 'value' => '25%', 'width' => '25%', 'color' => 'bg-red-500', 'style' => 'background:#ef4444'],
                ['label' => '1 WON', 'value' => '25%', 'width' => '25%', 'color' => 'bg-amber-500', 'style' => 'background:#f59e0b'],
                ['label' => '2 PASSIVE', 'value' => '50%', 'width' => '50%', 'color' => 'bg-orange-400', 'style' => 'background:#fb923c'],
                ['label' => '0 LOST', 'value' => '0%', 'width' => '0%', 'color' => 'bg-neutral-400', 'style' => 'background:#a3a3a3'],
                ['label' => '0 DEAD', 'value' => '0%', 'width' => '0%', 'color' => 'bg-neutral-400', 'style' => 'background:#a3a3a3'],
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
                        <article class="admin-metric-card flex min-h-[74px] items-center gap-3 rounded-xl border border-neutral-300 bg-white px-3 shadow-sm">
                            <div class="admin-metric-icon {{ $card['color'] }} w-10 text-center text-4xl leading-none" style="{{ $card['style'] }}">
                                <i class="{{ $card['icon'] }}"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="admin-card-title {{ $card['color'] }} text-sm font-medium" style="{{ $card['style'] }}">{{ $card['label'] }}</p>
                                <p class="admin-card-value text-sm font-bold text-blue-700">{{ $card['value'] }}</p>
                                @if ($card['meta'])
                                    <p class="admin-card-meta {{ $card['color'] }} text-sm font-semibold" style="{{ $card['style'] }}">{{ $card['meta'] }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <article class="admin-summary-card flex min-h-[248px] flex-col justify-end rounded-xl border border-neutral-300 bg-white p-3 shadow-sm">
                    <div class="admin-stat-line mb-2 flex items-center justify-between border-b border-neutral-200 pb-2 text-sm text-emerald-600">
                        <span><i class="fa-solid fa-user"></i> Students</span>
                        <span class="rounded border border-emerald-500 px-1">{{ $stats['students'] ?? 0 }}</span>
                    </div>
                    <div class="admin-stat-line mb-2 flex items-center justify-between border-b border-neutral-200 pb-2 text-sm text-sky-500">
                        <span><i class="fa-solid fa-child"></i> Boys</span>
                        <span class="rounded border border-sky-500 px-1">0</span>
                    </div>
                    <div class="admin-stat-line flex items-center justify-between text-sm text-pink-600">
                        <span><i class="fa-solid fa-child-dress"></i> Girls</span>
                        <span class="rounded border border-pink-500 px-1">0</span>
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
                        <span class="rounded border border-sky-500 px-1">1</span>
                    </div>
                    <div class="admin-stat-line flex items-center justify-between text-sm text-pink-600">
                        <span><i class="fa-solid fa-venus"></i> Female</span>
                        <span class="rounded border border-pink-500 px-1">1</span>
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
                        <span>Complains For Jul 2026</span>
                        <span>Today : 0 / Total : 0 / Solved : 0</span>
                    </div>
                    <div class="min-h-[288px]"></div>
                </section>
            </div>

            <div class="admin-fees-row grid gap-4 px-3 pb-4 xl:grid-cols-[minmax(0,2fr)_minmax(360px,0.8fr)]">
                <section class="admin-panel overflow-hidden rounded-xl bg-white shadow-lg">
                    <h2 class="admin-panel-title bg-[#2f61b3] px-2 py-1 text-sm font-semibold text-white">Fees Collection Statistics For - Jul 2026</h2>
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
            <div class="legacy-dashboard-card-grid">
                @foreach ($moduleCards as $card)
                    <a href="{{ $card['href'] }}" class="legacy-dashboard-card">
                        <img src="{{ asset('assets/images/db/'.$card['image']) }}" alt="{{ $card['label'] }}">
                        <span>{{ $card['label'] }}</span>
                    </a>
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
