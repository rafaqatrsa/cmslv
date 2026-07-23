<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Admin') - {{ config('app.name', 'Laravel') }}</title>
        <link rel="stylesheet" href="{{ asset('assets/themes/default/fontawesome/css/all.min.css') }}">
        @vite(['resources/css/app.css'])
        <style>
            :root {
                --admin-sidebar-width: 266px;
            }

            body.admin-body {
                margin: 0;
                background: #f3f6f8;
                color: #111827;
                font-family: Arial, Helvetica, sans-serif;
            }

            .admin-content-wrap {
                display: flex;
                min-height: 100vh;
                flex-direction: column;
                min-width: 0;
                padding-left: var(--admin-sidebar-width);
            }

            .admin-main {
                flex: 1 0 auto;
                padding: 72px 8px 24px;
            }

            .admin-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 30;
                width: var(--admin-sidebar-width);
                overflow: hidden;
                background: #24448d;
                color: #fff;
                box-shadow: 0 12px 28px rgba(15, 23, 42, .3);
            }

            .admin-sidebar-header {
                display: flex;
                height: 64px;
                align-items: center;
                gap: 8px;
                border-bottom: 1px solid rgba(255, 255, 255, .12);
                padding: 0 8px;
            }

            .admin-avatar {
                display: inline-flex;
                width: 42px;
                height: 42px;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, .55);
                background: rgba(255, 255, 255, .92);
                color: #64748b;
            }

            .admin-sidebar-link {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 14px;
                color: #fff;
                font-size: 18px;
                font-weight: 600;
                text-decoration: none;
            }

            .admin-sidebar-link:hover,
            .admin-sidebar-link.is-active {
                background: #1d3a7d;
            }

            .admin-topbar {
                position: fixed;
                top: 0;
                right: 0;
                left: var(--admin-sidebar-width);
                z-index: 20;
                height: 64px;
                background: #24448d;
                color: #fff;
                box-shadow: 0 2px 8px rgba(15, 23, 42, .3);
            }

            .admin-topbar-inner {
                display: flex;
                height: 100%;
                align-items: center;
                gap: 16px;
                padding: 0 14px;
            }

            .admin-brand {
                display: flex;
                height: 36px;
                min-width: 300px;
                align-items: center;
                border-radius: 999px 18px 0 999px;
                background: #3f70c9;
                padding: 0 18px;
                color: #fff;
                font-size: 24px;
                font-weight: 700;
                letter-spacing: 1px;
                text-decoration: none;
            }

            .admin-search {
                display: flex;
                width: min(420px, 100%);
                margin: 0 auto;
                overflow: hidden;
                border-radius: 999px;
                background: #fff;
                box-shadow: 0 1px 4px rgba(15, 23, 42, .18);
            }

            .admin-search input {
                min-width: 0;
                flex: 1;
                border: 0;
                padding: 10px 16px;
                font-size: 16px;
                outline: 0;
            }

            .admin-search button {
                width: 50px;
                border: 0;
                background: #3f70c9;
                color: #fff;
                font-size: 18px;
            }

            .admin-topbar-icons {
                display: flex;
                align-items: center;
                gap: 22px;
                margin-left: auto;
                font-size: 20px;
            }

            .admin-dashboard-section {
                overflow: hidden;
                border: 1px solid #d4d4d4;
                background: #fff;
                box-shadow: 0 1px 3px rgba(15, 23, 42, .12);
            }

            .admin-module-tabs {
                display: flex;
                flex-wrap: wrap;
                gap: 16px;
                border-bottom: 1px solid #f59e0b;
                padding: 12px;
            }

            .admin-module-tab {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                border: 0;
                border-radius: 6px;
                background: #fff;
                padding: 9px 20px;
                color: #1f2937;
                font-size: 16px;
                box-shadow: 0 8px 18px rgba(15, 23, 42, .18);
            }

            .admin-module-tab.is-active {
                background: #2f61b3;
                color: #fff;
            }

            .admin-dashboard-grid {
                display: grid;
                grid-template-columns: minmax(0, 4fr) minmax(250px, 1fr) minmax(250px, 1fr);
                gap: 10px;
                padding: 12px;
            }

            .admin-metric-grid {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 10px;
            }

            .admin-metric-card,
            .admin-summary-card,
            .admin-panel {
                border: 1px solid #d4d4d4;
                border-radius: 12px;
                background: #fff;
                box-shadow: 0 8px 18px rgba(15, 23, 42, .08);
            }

            .admin-metric-card {
                display: flex;
                min-height: 74px;
                align-items: center;
                gap: 12px;
                padding: 8px 12px;
            }

            .admin-metric-icon {
                width: 40px;
                text-align: center;
                font-size: 30px;
                line-height: 1;
            }

            .admin-card-title {
                margin: 0;
                font-size: 14px;
                font-weight: 500;
            }

            .admin-card-value,
            .admin-card-meta {
                margin: 2px 0 0;
                font-size: 14px;
                font-weight: 700;
            }

            .admin-summary-card {
                display: flex;
                min-height: 248px;
                flex-direction: column;
                justify-content: flex-end;
                padding: 12px;
            }

            .admin-donut-card {
                justify-content: center;
            }

            .admin-panels-row,
            .admin-fees-row {
                display: grid;
                gap: 10px;
                padding: 0 12px 12px;
            }

            .admin-panels-row {
                grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
            }

            .admin-three-panels {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 10px;
            }

            .admin-fees-row {
                grid-template-columns: minmax(0, 2fr) minmax(360px, .8fr);
            }

            .admin-panel-title {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: #2f61b3;
                color: #fff;
                padding: 6px 8px;
                font-size: 14px;
                font-weight: 700;
            }

            .admin-progress-body {
                padding: 14px 12px;
            }

            .admin-progress-row {
                margin-bottom: 16px;
            }

            .admin-progress-label {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
                font-size: 18px;
            }

            .admin-progress-track {
                height: 5px;
                background: #e5e7eb;
            }

            .admin-progress-fill {
                height: 100%;
            }

            .admin-stat-line {
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #e5e7eb;
                padding: 6px 0;
                font-size: 14px;
            }

            .admin-donut {
                width: 112px;
                height: 112px;
                margin: 0 auto 18px;
                border-radius: 999px;
                background: conic-gradient(#0ea5e9 0 50%, #ec4899 50% 100%);
                position: relative;
            }

            .admin-donut::after {
                content: "";
                position: absolute;
                inset: 28px;
                border-radius: 999px;
                background: #fff;
            }

            @media (max-width: 1279px) {
                .admin-dashboard-grid,
                .admin-panels-row,
                .admin-fees-row {
                    grid-template-columns: 1fr;
                }

                .admin-metric-grid,
                .admin-three-panels {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 1023px) {
                .admin-sidebar {
                    display: none;
                }

                .admin-content-wrap {
                    padding-left: 0;
                }

                .admin-topbar {
                    left: 0;
                }
            }

            @media (max-width: 767px) {
                .admin-search,
                .admin-topbar-icons {
                    display: none;
                }

                .admin-brand {
                    min-width: 0;
                    flex: 1;
                }

                .admin-metric-grid,
                .admin-three-panels {
                    grid-template-columns: 1fr;
                }
            }
        </style>
        @stack('styles')
    </head>
    <body class="admin-body bg-[#f3f6f8] text-neutral-900">
        <div class="min-h-screen">
            @include('admin.partials.sidebar')

            <div class="admin-content-wrap min-w-0">
                @include('admin.partials.header')
                @include('admin.partials.navbar')

                <main class="admin-main px-2 pb-6 pt-[72px] sm:px-3">
                    @include('admin.partials.alerts')
                    @yield('content')
                </main>

                @include('admin.partials.footer')
            </div>
        </div>

        <script>
            window.adminRoutes = {
                dashboard: @json(route('admin.dashboard')),
                staff: @json(route('admin.staff.index')),
                report: @json(route('admin.report.index')),
                frontcms: @json(route('admin.frontcms.index')),
                membership: @json(route('admin.membership.index')),
                qms: @json(route('admin.qms.index')),
                systemNotification: @json(route('admin.system-notification.index')),
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (window.jQuery && csrfToken) {
                window.jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
            }
        </script>
        @stack('scripts')
    </body>
</html>
