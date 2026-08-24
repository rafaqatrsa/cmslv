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
            body.admin-body {
                margin: 0;
                background:
                    radial-gradient(circle at top left, rgba(39, 76, 162, 0.08), transparent 28%),
                    linear-gradient(180deg, #eff4fb 0%, #f5f7fb 26%, #f3f6f8 100%);
                color: #111827;
                font-family: 'Roboto', Arial, Helvetica, sans-serif;
            }

            .admin-content-wrap {
                min-width: 0;
                padding-left: 304px;
            }

            .admin-main {
                padding: 76px 10px 24px;
            }

            .admin-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 30;
                width: 304px;
                overflow: hidden;
                background: linear-gradient(180deg, #264a95 0%, #23408a 100%);
                color: #fff;
                box-shadow: 0 12px 28px rgba(15, 23, 42, .28);
            }

            .admin-sidebar-header {
                display: flex;
                height: 72px;
                align-items: center;
                gap: 10px;
                border-bottom: 1px solid rgba(255, 255, 255, .12);
                padding: 0 10px;
            }

            .admin-avatar {
                display: inline-flex;
                width: 46px;
                height: 46px;
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
                gap: 10px;
                padding: 14px 14px;
                color: #fff;
                font-size: 16px;
                font-weight: 600;
                line-height: 1.15;
                text-decoration: none;
            }

            .admin-sidebar-link:hover,
            .admin-sidebar-link.is-active {
                background: rgba(18, 40, 96, .42);
            }

            .admin-topbar {
                position: fixed;
                top: 0;
                right: 0;
                left: 304px;
                z-index: 20;
                height: 72px;
                background: linear-gradient(180deg, #264a95 0%, #23408a 100%);
                color: #fff;
                box-shadow: 0 2px 8px rgba(15, 23, 42, .26);
            }

            .admin-topbar-inner {
                display: flex;
                height: 100%;
                align-items: center;
                gap: 14px;
                padding: 0 14px 0 10px;
            }

            .admin-brand {
                display: flex;
                height: 48px;
                min-width: 336px;
                align-items: center;
                border-radius: 999px;
                background: linear-gradient(180deg, #4c79cf 0%, #3e68c2 100%);
                padding: 0 22px;
                color: #fff;
                font-size: 22px;
                font-weight: 700;
                letter-spacing: 0.5px;
                text-decoration: none;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .18);
            }

            .admin-search {
                display: flex;
                width: min(470px, 100%);
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
                padding: 11px 18px;
                font-size: 15px;
                outline: 0;
            }

            .admin-search button {
                width: 54px;
                border: 0;
                background: #3e68c2;
                color: #fff;
                font-size: 18px;
            }

            .admin-topbar-icons {
                display: flex;
                align-items: center;
                gap: 18px;
                margin-left: auto;
                font-size: 18px;
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
                gap: 14px;
                border-bottom: 2px solid #f1b65e;
                padding: 14px 14px 12px;
            }

            .admin-module-tab {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                border: 0;
                border-radius: 8px;
                background: #fff;
                padding: 12px 22px;
                color: #1f2937;
                font-size: 15px;
                font-weight: 600;
                box-shadow: 0 8px 18px rgba(15, 23, 42, .14);
            }

            .admin-module-tab.is-active {
                background: linear-gradient(180deg, #3f70c9 0%, #3259ab 100%);
                color: #fff;
            }

            .admin-dashboard-grid {
                display: grid;
                grid-template-columns: minmax(0, 4fr) minmax(250px, 1fr) minmax(250px, 1fr);
                gap: 12px;
                padding: 12px;
            }

            .admin-metric-grid {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 12px;
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
                min-height: 96px;
                align-items: center;
                gap: 12px;
                padding: 10px 12px;
            }

            .admin-metric-icon {
                width: 40px;
                text-align: center;
                font-size: 28px;
                line-height: 1;
            }

            .admin-card-title {
                margin: 0;
                font-size: 13px;
                font-weight: 500;
                letter-spacing: 0.02em;
            }

            .admin-card-value,
            .admin-card-meta {
                margin: 2px 0 0;
                font-size: 13px;
                font-weight: 700;
            }

            .admin-summary-card {
                display: flex;
                min-height: 284px;
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
                gap: 12px;
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
                background: linear-gradient(180deg, #3f70c9 0%, #3259ab 100%);
                color: #fff;
                padding: 6px 8px;
                font-size: 13px;
                font-weight: 700;
            }

            .admin-progress-body {
                padding: 14px 12px;
            }

            .admin-progress-row {
                margin-bottom: 15px;
            }

            .admin-progress-label {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
                font-size: 16px;
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
                font-size: 13px;
            }

            .admin-donut {
                width: 120px;
                height: 120px;
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

            <div class="admin-content-wrap min-w-0 lg:pl-[296px]">
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
