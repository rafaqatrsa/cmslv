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
            ['label' => 'DASHBOARD', 'icon' => 'fa-solid fa-desktop', 'active' => true],
            ['label' => 'MODULES', 'icon' => 'fa-solid fa-cubes'],
            ['label' => 'REPORTS', 'icon' => 'fa-solid fa-chart-column'],
            ['label' => 'CMS', 'icon' => 'fa-solid fa-building-columns'],
            ['label' => 'LMS', 'icon' => 'fa-solid fa-book'],
            ['label' => 'SYSTEM SETTINGS', 'icon' => 'fa-solid fa-gears'],
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
                <button class="admin-module-tab {{ ($tab['active'] ?? false) ? 'is-active bg-[#2f61b3] text-white' : 'bg-white text-neutral-800' }}">
                    <i class="{{ $tab['icon'] }}"></i>
                    <span>{{ $tab['label'] }}</span>
                </button>
            @endforeach
        </div>

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
    </section>
@endsection
