@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $metricCards = [
            // Row 1
            ['label' => 'ADMISSION INQUIRY', 'value' => $stats['admission_inquiries'] ?? 0, 'meta' => 'TODAY : 0 WON : ' . ($stats['admission_inquiries'] ?? 0), 'img' => 'admission_inquiry.png', 'colorClass' => 'db-red', 'url' => route('admin.adm.enquiries.index', absolute: false)],
            ['label' => 'REGISTRATION', 'value' => $stats['registrations'] ?? 0, 'meta' => 'SELF : 0 ONLINE : 0', 'img' => 'student_regd.png', 'colorClass' => 'db-black', 'url' => route('admin.adm.student-registrations.index', absolute: false)],
            ['label' => 'ADMISSION', 'value' => $stats['admissions'] ?? 0, 'meta' => 'TODAY : 0 LEAVING : 0', 'img' => 'admission.png', 'colorClass' => 'db-green', 'url' => route('admin.adm.students.index', absolute: false)],
            ['label' => 'STUDENTS', 'value' => $stats['students'] ?? 0, 'meta' => 'P : 0 A : 0 L : 0', 'img' => 'students.png', 'colorClass' => 'db-orange', 'url' => route('admin.adm.students.index', absolute: false)],
            // Row 2
            ['label' => 'ADMIN STAFF', 'value' => $stats['admin_staff'] ?? 0, 'meta' => 'P : 0 A : 0 L : 0', 'img' => 'admindb.png', 'colorClass' => 'db-teal', 'url' => route('admin.hrms.staff.index', absolute: false)],
            ['label' => 'TEACHING STAFF', 'value' => $stats['teaching_staff'] ?? 0, 'meta' => 'P : 0 A : 0 L : 0', 'img' => 'staff.png', 'colorClass' => 'db-grey', 'url' => route('admin.academics.teachers.index', absolute: false)],
            ['label' => 'ALLIED STAFF', 'value' => $stats['allied_staff'] ?? 0, 'meta' => 'P : 0 A : 0 L : 0', 'img' => 'staff-a.png', 'colorClass' => 'db-barf', 'url' => route('admin.hrms.staff.index', absolute: false)],
            ['label' => 'FAMILIES', 'value' => $stats['families'] ?? 0, 'meta' => '', 'img' => 'family.png', 'colorClass' => 'db-carmine', 'url' => route('admin.adm.siblings.index', absolute: false)],
            // Row 3
            ['label' => 'COMPLAIN', 'value' => $stats['complaints'] ?? 0, 'meta' => 'TODAY : 0', 'img' => 'complaint.png', 'colorClass' => 'db-purple', 'url' => route('admin.adm.complaints.index', absolute: false)],
            ['label' => 'VISITORS', 'value' => $stats['visitors'] ?? 0, 'meta' => 'TODAY : 0', 'img' => 'visitor.png', 'colorClass' => 'db-brown', 'url' => route('admin.adm.visitor-purposes.index', absolute: false)],
            ['label' => 'PURCHASE', 'value' => $stats['purchases'] ?? 0, 'meta' => 'TODAY : 0', 'img' => 'purchase.png', 'colorClass' => 'db-netmeg', 'url' => route('admin.account.purchases.index', absolute: false)],
            ['label' => 'SALES', 'value' => $stats['sales'] ?? 0, 'meta' => 'TODAY : 0', 'img' => 'sales.png', 'colorClass' => 'db-partsale', 'url' => route('admin.account.sales.index', absolute: false)],
        ];

        $moduleTabs = [
            ['label' => 'DASHBOARD', 'icon' => 'fa-solid fa-desktop', 'active' => true, 'url' => route('admin.dashboard', absolute: false)],
            ['label' => 'ACCOUNTS & COA', 'icon' => 'fa-solid fa-calculator', 'active' => false, 'url' => route('admin.account.accounts.dashboard', absolute: false)],
            ['label' => 'ACADEMICS', 'icon' => 'fa-solid fa-graduation-cap', 'active' => false, 'url' => route('admin.academics.dashboard', absolute: false)],
            ['label' => 'STUDENT ADM', 'icon' => 'fa-solid fa-user-plus', 'active' => false, 'url' => route('admin.adm.dashboard', absolute: false)],
            ['label' => 'HRMS', 'icon' => 'fa-solid fa-users', 'active' => false, 'url' => route('admin.hrms.dashboard', absolute: false)],
            ['label' => 'REPORTS', 'icon' => 'fa-solid fa-chart-column', 'active' => false, 'url' => route('admin.report.index', absolute: false)],
            ['label' => 'CMS', 'icon' => 'fa-solid fa-building-columns', 'active' => false, 'url' => route('admin.frontcms.index', absolute: false)],
        ];

        $progressPanels = [
            'Admission Enquiry For ' . date('M Y') => [
                ['label' => 'ACTIVE', 'value' => '0', 'pct' => '0%'],
                ['label' => 'WON', 'value' => '0', 'pct' => '0%'],
                ['label' => 'PASSIVE', 'value' => '0', 'pct' => '0%'],
                ['label' => 'LOST', 'value' => '0', 'pct' => '0%'],
                ['label' => 'DEAD', 'value' => '0', 'pct' => '0%'],
            ],
            'Student Today Attendance' => [
                ['label' => 'PRESENT', 'value' => '', 'pct' => '0%'],
                ['label' => 'ABSENT', 'value' => '', 'pct' => '0%'],
                ['label' => 'LEAVE', 'value' => '', 'pct' => '0%'],
                ['label' => 'LATE', 'value' => '', 'pct' => '0%'],
                ['label' => 'HALF DAY', 'value' => '', 'pct' => '0%'],
            ],
            'Staff Today Attendance' => [
                ['label' => 'PRESENT', 'value' => '', 'pct' => '0%'],
                ['label' => 'RED LEAVE', 'value' => '', 'pct' => '0%'],
                ['label' => 'BLUE LEAVE', 'value' => '', 'pct' => '0%'],
                ['label' => 'GREEN LEAVE', 'value' => '', 'pct' => '0%'],
                ['label' => 'LATE', 'value' => '', 'pct' => '0%'],
            ],
        ];
    @endphp

    {{-- Dashboard Animations CSS --}}
    @push('styles')
    <style>
        /* ============================================
           CMSC-style Dashboard Info-Box Buttons
           with smooth hover animations
           ============================================ */

        /* Base info-box card */
        .dashboard-main-tabs .info-box {
            transition: all .3s cubic-bezier(.25,.8,.25,1);
            display: block;
            cursor: pointer;
            border: solid 1px #dde4eb;
            min-height: 60px;
            background: #fff;
            width: 100%;
            margin-bottom: 10px;
            box-shadow: 0 0 0 0 rgba(90, 113, 208, 0.11), 0 4px 16px 0 rgba(167, 175, 183, 0.33);
            border-radius: 12px;
        }

        .dashboard-main-tabs .info-box a {
            color: #333;
            text-decoration: none;
            transition: all 0.3s linear;
            width: 100%;
            display: block;
            padding: 3px;
        }

        .dashboard-main-tabs .info-box-icon {
            display: block;
            float: left;
            height: 45px;
            width: 30px;
            text-align: center;
            font-size: 50px;
            line-height: 30px;
            background: none !important;
            border-radius: 5px;
            box-shadow: none !important;
            border: 2px solid #fff inset;
            margin: 3px 0 0 0px;
        }

        .dashboard-main-tabs .info-box-icon img {
            width: 30px;
            height: 30px;
            transition: filter 0.3s linear;
        }

        .dashboard-main-tabs .info-box-content {
            margin-left: 30px;
            padding-top: 0px;
        }

        .dashboard-main-tabs .info-box-content .info-box-text {
            text-transform: uppercase;
            display: block;
            font-size: 11px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dashboard-main-tabs .info-box-content .info-box-number {
            display: block;
            font-weight: 700;
            font-size: 11px;
        }

        /* ===== Color Variants with Hover Animations ===== */

        /* Red - Admission Inquiry */
        .dashboard-main-tabs .db-red { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-red span { color: #ff0001; }
        .dashboard-main-tabs .db-red:hover {
            border: 1px solid #ff0001 !important;
            background: #ff0001;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 0, 1, 0.35);
        }
        .dashboard-main-tabs .db-red:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-red:hover span img { filter: brightness(0) invert(1); }

        /* Black - Registration */
        .dashboard-main-tabs .db-black { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-black span { color: #000; }
        .dashboard-main-tabs .db-black:hover {
            border: 2px solid #000;
            background: #000;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.35);
        }
        .dashboard-main-tabs .db-black:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-black:hover span img { filter: brightness(0) invert(1); }

        /* Green - Admission */
        .dashboard-main-tabs .db-green { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-green span { color: #009e49; }
        .dashboard-main-tabs .db-green:hover {
            border: 1px solid #009e49 !important;
            background: #009e49;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 158, 73, 0.35);
        }
        .dashboard-main-tabs .db-green:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-green:hover span img { filter: brightness(0) invert(1); }

        /* Orange - Students */
        .dashboard-main-tabs .db-orange { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-orange span { color: #ff8c00; }
        .dashboard-main-tabs .db-orange:hover {
            border: 2px solid #ff8c00;
            background: #ff8c00;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 140, 0, 0.35);
        }
        .dashboard-main-tabs .db-orange:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-orange:hover span img { filter: brightness(0) invert(1); }

        /* Teal (Pink) - Admin Staff */
        .dashboard-main-tabs .db-teal { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-teal span { color: #ec008c; }
        .dashboard-main-tabs .db-teal:hover {
            border: 1px solid #ec008c !important;
            background: #ec008c;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(236, 0, 140, 0.35);
        }
        .dashboard-main-tabs .db-teal:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-teal:hover span img { filter: brightness(0) invert(1); }

        /* Grey - Teaching Staff */
        .dashboard-main-tabs .db-grey { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-grey span { color: #27404d; }
        .dashboard-main-tabs .db-grey:hover {
            border: 1px solid #27404d !important;
            background: #27404d;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(39, 64, 77, 0.35);
        }
        .dashboard-main-tabs .db-grey:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-grey:hover span img { filter: brightness(0) invert(1); }

        /* Barf (Yellow-Green) - Allied Staff */
        .dashboard-main-tabs .db-barf { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-barf span { color: #94ac02; }
        .dashboard-main-tabs .db-barf:hover {
            border: 1px solid #94ac02;
            background: #94ac02;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(148, 172, 2, 0.35);
        }
        .dashboard-main-tabs .db-barf:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-barf:hover span img { filter: brightness(0) invert(1); }

        /* Carmine (Blue) - Families */
        .dashboard-main-tabs .db-carmine { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-carmine span { color: #00a4ef; }
        .dashboard-main-tabs .db-carmine:hover {
            border: 1px solid #00a4ef !important;
            background: #00a4ef;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 164, 239, 0.35);
        }
        .dashboard-main-tabs .db-carmine:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-carmine:hover span img { filter: brightness(0) invert(1); }

        /* Purple - Complain */
        .dashboard-main-tabs .db-purple { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-purple span { color: #68217a; }
        .dashboard-main-tabs .db-purple:hover {
            border: 1px solid #68217a !important;
            background: #68217a;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(104, 33, 122, 0.35);
        }
        .dashboard-main-tabs .db-purple:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-purple:hover span img { filter: brightness(0) invert(1); }

        /* Brown - Visitors */
        .dashboard-main-tabs .db-brown { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-brown span { color: #7F5112; }
        .dashboard-main-tabs .db-brown:hover {
            border: 2px solid #7F5112;
            background: #7F5112;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(127, 81, 18, 0.35);
        }
        .dashboard-main-tabs .db-brown:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-brown:hover span img { filter: brightness(0) invert(1); }

        /* Netmeg (Orange-Red) - Purchase */
        .dashboard-main-tabs .db-netmeg { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-netmeg span { color: #f25022; }
        .dashboard-main-tabs .db-netmeg:hover {
            border: 1px solid #f25022 !important;
            background: #f25022;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(242, 80, 34, 0.35);
        }
        .dashboard-main-tabs .db-netmeg:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-netmeg:hover span img { filter: brightness(0) invert(1); }

        /* Partsale (Green) - Sales */
        .dashboard-main-tabs .db-partsale { border: 1px solid #ccc; }
        .dashboard-main-tabs .db-partsale span { color: #00B400; }
        .dashboard-main-tabs .db-partsale:hover {
            border: 1px solid #00B400 !important;
            background: #00B400;
            color: #fff !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 180, 0, 0.35);
        }
        .dashboard-main-tabs .db-partsale:hover span { color: #fff !important; }
        .dashboard-main-tabs .db-partsale:hover span img { filter: brightness(0) invert(1); }

        /* ===== Entrance Animation (Staggered Fade-In + Slide-Up) ===== */
        @keyframes dbCardFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard-main-tabs .info-box {
            opacity: 0;
            animation: dbCardFadeIn 0.5s ease-out forwards;
        }

        .dashboard-main-tabs .db-card-row .info-box-col:nth-child(1) .info-box { animation-delay: 0.05s; }
        .dashboard-main-tabs .db-card-row .info-box-col:nth-child(2) .info-box { animation-delay: 0.10s; }
        .dashboard-main-tabs .db-card-row .info-box-col:nth-child(3) .info-box { animation-delay: 0.15s; }
        .dashboard-main-tabs .db-card-row .info-box-col:nth-child(4) .info-box { animation-delay: 0.20s; }

        .dashboard-main-tabs .db-card-row:nth-child(2) .info-box-col:nth-child(1) .info-box { animation-delay: 0.25s; }
        .dashboard-main-tabs .db-card-row:nth-child(2) .info-box-col:nth-child(2) .info-box { animation-delay: 0.30s; }
        .dashboard-main-tabs .db-card-row:nth-child(2) .info-box-col:nth-child(3) .info-box { animation-delay: 0.35s; }
        .dashboard-main-tabs .db-card-row:nth-child(2) .info-box-col:nth-child(4) .info-box { animation-delay: 0.40s; }

        .dashboard-main-tabs .db-card-row:nth-child(3) .info-box-col:nth-child(1) .info-box { animation-delay: 0.45s; }
        .dashboard-main-tabs .db-card-row:nth-child(3) .info-box-col:nth-child(2) .info-box { animation-delay: 0.50s; }
        .dashboard-main-tabs .db-card-row:nth-child(3) .info-box-col:nth-child(3) .info-box { animation-delay: 0.55s; }
        .dashboard-main-tabs .db-card-row:nth-child(3) .info-box-col:nth-child(4) .info-box { animation-delay: 0.60s; }

        /* ===== Donut Charts ===== */
        .donut-chart-wrap {
            text-align: center;
            padding: 8px;
        }

        .donut-chart-wrap canvas {
            max-width: 100px;
            max-height: 100px;
            margin: 0 auto;
        }

        .donut-legend {
            list-style: none;
            padding: 0;
            margin: 8px 0 0;
            font-size: 12px;
        }

        .donut-legend li {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }

        .donut-legend .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        /* ===== Progress Panel ===== */
        .db-progress-panel {
            border: 1px solid #d4d4d4;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(167, 175, 183, 0.33);
        }

        .db-progress-panel .panel-heading {
            background: linear-gradient(135deg, #1a5276, #24448d);
            color: #fff;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 700;
        }

        .db-progress-panel .panel-body {
            padding: 12px;
        }

        .db-progress-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            font-size: 13px;
            border-bottom: 1px solid #f0f0f0;
        }

        .db-progress-row:last-child {
            border-bottom: none;
        }

        /* ===== Complaints Panel ===== */
        .db-complaints-panel {
            border: 1px solid #d4d4d4;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(167, 175, 183, 0.33);
        }

        .db-complaints-panel .panel-heading {
            background: linear-gradient(135deg, #0d6efd, #00a4ef);
            color: #fff;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 700;
        }

        /* ===== Module Tabs Animation ===== */
        @keyframes tabSlideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .admin-module-tabs .admin-module-tab {
            opacity: 0;
            animation: tabSlideIn 0.4s ease-out forwards;
        }

        .admin-module-tabs .admin-module-tab:nth-child(1) { animation-delay: 0.05s; }
        .admin-module-tabs .admin-module-tab:nth-child(2) { animation-delay: 0.10s; }
        .admin-module-tabs .admin-module-tab:nth-child(3) { animation-delay: 0.15s; }
        .admin-module-tabs .admin-module-tab:nth-child(4) { animation-delay: 0.20s; }
        .admin-module-tabs .admin-module-tab:nth-child(5) { animation-delay: 0.25s; }
        .admin-module-tabs .admin-module-tab:nth-child(6) { animation-delay: 0.30s; }
        .admin-module-tabs .admin-module-tab:nth-child(7) { animation-delay: 0.35s; }

        /* ===== Responsive Grid ===== */
        .db-card-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 0;
        }

        @media (max-width: 1200px) {
            .db-card-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .db-card-row {
                grid-template-columns: 1fr;
            }
        }

        .db-main-grid {
            display: grid;
            grid-template-columns: minmax(0, 8fr) minmax(280px, 4fr);
            gap: 12px;
            padding: 12px;
        }

        @media (max-width: 1200px) {
            .db-main-grid {
                grid-template-columns: 1fr;
            }
        }

        .db-bottom-panels {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            padding: 0 12px 12px;
        }

        @media (max-width: 1200px) {
            .db-bottom-panels {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .db-bottom-panels {
                grid-template-columns: 1fr;
            }
        }

        /* ===== Pulse animation for donut ===== */
        @keyframes donutPulse {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .donut-chart-wrap {
            animation: donutPulse 0.6s ease-out 0.3s forwards;
            opacity: 0;
        }
    </style>
    @endpush

    <section class="admin-dashboard-section overflow-hidden rounded-lg border border-neutral-300 bg-white shadow-sm">
        {{-- Interactive Module Tabs Navigation --}}
        <div class="admin-module-tabs flex flex-wrap gap-2 border-b border-amber-300 bg-neutral-50 px-4 py-3">
            @foreach ($moduleTabs as $tab)
                <a href="{{ $tab['url'] }}" class="admin-module-tab inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-all shadow-sm {{ ($tab['active'] ?? false) ? 'is-active bg-[#24448d] text-white' : 'border border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-100 hover:text-blue-700' }}">
                    <i class="{{ $tab['icon'] }}"></i>
                    <span>{{ $tab['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Main Dashboard Content --}}
        <div class="dashboard-main-tabs">
            <div class="db-main-grid">
                {{-- Left Column: Stat Cards (8 columns) --}}
                <div>
                    @foreach (array_chunk($metricCards, 4) as $rowIndex => $row)
                        <div class="db-card-row">
                            @foreach ($row as $card)
                                <div class="info-box-col">
                                    <div class="info-box {{ $card['colorClass'] }}">
                                        <a href="{{ $card['url'] }}">
                                            <span class="info-box-icon">
                                                <img src="{{ asset('assets/images/db/' . $card['img']) }}" alt="{{ $card['label'] }}" />
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">{{ $card['label'] }}</span>
                                                <span class="info-box-number">{{ $card['value'] }}</span>
                                                @if ($card['meta'])
                                                    <span class="info-box-text">{{ $card['meta'] }}</span>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{-- Right Column: Donut Charts + Legend (4 columns) --}}
                <div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 0 8px;">
                        {{-- Student Donut --}}
                        <div class="info-box" style="padding: 8px; text-align: center; animation-delay: 0.3s;">
                            <div class="donut-chart-wrap">
                                <canvas id="donutChartstudent" width="100" height="100"></canvas>
                            </div>
                            <ul class="donut-legend">
                                <li><span class="dot" style="background: #00a4ef;"></span> Students <strong style="margin-left:auto;">{{ $stats['students'] ?? 0 }}</strong></li>
                                <li><span class="dot" style="background: #3b82f6;"></span> Boys <strong style="margin-left:auto;">0</strong></li>
                                <li><span class="dot" style="background: #ec4899;"></span> Girls <strong style="margin-left:auto;">0</strong></li>
                            </ul>
                        </div>

                        {{-- Staff Donut --}}
                        <div class="info-box" style="padding: 8px; text-align: center; animation-delay: 0.4s;">
                            <div class="donut-chart-wrap">
                                <canvas id="donutChartTeacher" width="100" height="100"></canvas>
                            </div>
                            <ul class="donut-legend">
                                <li><span class="dot" style="background: #00a651;"></span> Staff <strong style="margin-left:auto;">{{ $stats['staff'] ?? 0 }}</strong></li>
                                <li><span class="dot" style="background: #3b82f6;"></span> Male <strong style="margin-left:auto;">0</strong></li>
                                <li><span class="dot" style="background: #ec4899;"></span> Female <strong style="margin-left:auto;">0</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Row: Admission Enquiry + Student Attendance + Staff Attendance + Complaints --}}
            <div class="db-bottom-panels">
                @foreach ($progressPanels as $title => $rows)
                    <div class="db-progress-panel">
                        <div class="panel-heading">{{ $title }}</div>
                        <div class="panel-body">
                            @foreach ($rows as $row)
                                <div class="db-progress-row">
                                    <span>{{ $row['value'] ? $row['value'] . ' ' : '' }}{{ $row['label'] }}</span>
                                    <span style="font-weight: 700;">{{ $row['pct'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- Complaints Panel --}}
                <div class="db-complaints-panel">
                    <div class="panel-heading">
                        Complains For {{ date('M Y') }} &nbsp; Today : 0 / Total : {{ $stats['complaints'] ?? 0 }} / Solved : 0
                    </div>
                    <div class="panel-body" style="padding: 12px; min-height: 120px;">
                        <p style="font-size: 12px; color: #999; text-align: center; padding: 20px 0;">No complaints found.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Modules Row --}}
        <div class="grid gap-4 px-4 pb-4 md:grid-cols-2">
            <section class="admin-panel overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm p-4">
                <h3 class="text-sm font-bold text-neutral-800 mb-3 flex items-center gap-2">
                    <i class="fa fa-calculator text-blue-600"></i> Chart of Accounts Quick Access
                </h3>
                <p class="text-xs text-neutral-600 mb-3">Manage chart of accounts heads, sub-types, expenses, vouchers, and ledger entries.</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.account.accounts.newaccounts', absolute: false) }}" class="rounded bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                        <i class="fa fa-plus-circle"></i> Accounts Type
                    </a>
                    <a href="{{ route('admin.account.accounts.index', absolute: false) }}" class="rounded border border-neutral-300 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50">
                        Chart of Accounts
                    </a>
                    <a href="{{ route('admin.account.expenses.index', absolute: false) }}" class="rounded border border-neutral-300 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50">
                        Expenses Bill
                    </a>
                    <a href="{{ route('admin.account.payments.index', absolute: false) }}" class="rounded border border-neutral-300 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50">
                        Payment Voucher
                    </a>
                </div>
            </section>

            <section class="admin-panel overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm p-4">
                <h3 class="text-sm font-bold text-neutral-800 mb-3 flex items-center gap-2">
                    <i class="fa fa-bell text-amber-500"></i> System Status
                </h3>
                <p class="text-xs text-neutral-600 mb-3">All application modules connected and operational on MySQL Database (Port 3307).</p>
                <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg p-2.5">
                    <i class="fa fa-check-circle text-emerald-600 text-sm"></i>
                    <span>Database Connected: <strong>tnt</strong> &bull; Total Tables: 241 &bull; Status: Online</span>
                </div>
            </section>
        </div>
    </section>

    {{-- Chart.js for Donut Charts --}}
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Student Donut Chart
            var ctxStudent = document.getElementById('donutChartstudent');
            if (ctxStudent) {
                new Chart(ctxStudent, {
                    type: 'doughnut',
                    data: {
                        labels: ['Students', 'Boys', 'Girls'],
                        datasets: [{
                            data: [{{ $stats['students'] ?? 0 }}, 0, 0],
                            backgroundColor: ['#00a4ef', '#3b82f6', '#ec4899'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: false,
                        legend: { display: false },
                        animation: {
                            animateScale: true,
                            animateRotate: true,
                            duration: 1200,
                            easing: 'easeOutBounce'
                        },
                        cutoutPercentage: 55
                    }
                });
            }

            // Staff Donut Chart
            var ctxTeacher = document.getElementById('donutChartTeacher');
            if (ctxTeacher) {
                new Chart(ctxTeacher, {
                    type: 'doughnut',
                    data: {
                        labels: ['Staff', 'Male', 'Female'],
                        datasets: [{
                            data: [{{ $stats['staff'] ?? 0 }}, 0, 0],
                            backgroundColor: ['#00a651', '#3b82f6', '#ec4899'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: false,
                        legend: { display: false },
                        animation: {
                            animateScale: true,
                            animateRotate: true,
                            duration: 1200,
                            easing: 'easeOutBounce'
                        },
                        cutoutPercentage: 55
                    }
                });
            }
        });
    </script>
    @endpush
@endsection
