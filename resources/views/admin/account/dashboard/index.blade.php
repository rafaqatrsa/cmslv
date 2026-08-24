@extends('admin.layouts.app')

@section('title', 'Accounts Dashboard')

@section('content')
    @push('styles')
    <style>
        /* ===================================================
           CMSC Accounts Dashboard Layout & Styles
           =================================================== */

        .acc-dashboard-wrap {
            font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
        }

        /* Top Module Header / Quick Bar */
        .acc-subnav-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 15px;
            background: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #d2d6de;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .acc-subnav-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            background: #f8f9fa;
            border: 1px solid #d2d6de;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .acc-subnav-btn:hover {
            background: #24448d;
            color: #fff;
            border-color: #24448d;
        }

        .acc-subnav-btn.active {
            background: #2F5DA8;
            color: #fff;
            border-color: #2F5DA8;
        }

        /* Grid Layout */
        .acc-grid-row {
            display: grid;
            grid-template-columns: minmax(0, 8fr) minmax(280px, 4fr);
            gap: 15px;
            margin-bottom: 15px;
        }

        @media (max-width: 991px) {
            .acc-grid-row {
                grid-template-columns: 1fr;
            }
        }

        /* CMSC Box Panels */
        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 15px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        .box.box-primary {
            border-top-color: #2F5DA8;
        }

        .box.box-info {
            border-top-color: #2F5DA8;
        }

        .box.borderwhite {
            border: 1px solid #d4d4d4;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .box-header {
            color: #fff;
            background-color: #2F5DA8;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            position: relative;
        }

        .box-header.with-border {
            border-bottom: 1px solid #f4f4f4;
        }

        .box-title {
            display: inline-block;
            font-size: 14px;
            margin: 0;
            line-height: 1;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.2px;
        }

        .box-tools {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .box-tools .btn-box-tool {
            padding: 2px 6px;
            font-size: 11px;
            background: transparent;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 2px;
            transition: background 0.15s ease;
        }

        .box-tools .btn-box-tool:hover {
            background: rgba(255,255,255,0.2);
        }

        .box-body {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
            padding: 12px;
            background: #fff;
        }

        /* Stats Summary Text */
        .info-box-content-stats {
            text-align: center;
            padding: 6px 0 10px;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 8px;
        }

        .stat-text {
            color: #000;
            display: inline-block;
            padding: 0 8px 0 0;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .stat-text-large {
            color: #000;
            display: inline-block;
            padding: 0 15px 0 0;
            font-size: 14px;
            font-weight: 700;
        }

        /* Fee Overview Progress Styles */
        .topprograssstart {
            background: #fff;
            padding: 0;
        }

        .topprograssstart-content {
            padding: 6px 4px;
        }

        .topprograssstart-content p {
            margin: 10px 0 4px 0;
            font-size: 12px;
            font-weight: 700;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topprograssstart-content p span.pull-right {
            font-size: 12px;
            font-weight: 700;
        }

        .topprograssstart-content p span.pull-right a {
            color: inherit;
            text-decoration: none;
        }

        .progress-group {
            margin-bottom: 10px;
        }

        .progress.progress-minibar {
            height: 6px;
            background-color: #f0f0f0;
            border-radius: 3px;
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
            margin-bottom: 0;
        }

        .progress-bar {
            height: 100%;
            font-size: 12px;
            line-height: 20px;
            color: #fff;
            text-align: center;
            transition: width .6s ease;
        }

        .progress-bar-success {
            background-color: #00a65a !important;
        }

        .progress-bar-alert {
            background-color: #dd4b39 !important;
        }

        .progress-bar-warning {
            background-color: #f39c12 !important;
        }

        /* Responsive chart wrappers */
        .chart-responsive {
            position: relative;
            min-height: 200px;
            width: 100%;
        }

        .chart {
            position: relative;
            min-height: 200px;
            width: 100%;
        }

        /* Animation */
        @keyframes accFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .acc-animate {
            animation: accFadeIn 0.4s ease-out forwards;
        }
    </style>
    @endpush

    <div class="acc-dashboard-wrap acc-animate">
        {{-- Sub-Navigation Bar for Accounts Module --}}
        <div class="acc-subnav-bar">
            <a href="{{ route('admin.account.accounts.dashboard', absolute: false) }}" class="acc-subnav-btn active">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
            <a href="{{ route('admin.account.accounts.newaccounts', absolute: false) }}" class="acc-subnav-btn">
                <i class="fa fa-list"></i> Chart of Accounts
            </a>
            <a href="{{ route('admin.account.student-fees.index', absolute: false) }}" class="acc-subnav-btn">
                <i class="fa fa-money-bill"></i> Student Fee
            </a>
            <a href="{{ route('admin.account.fee-master.index', absolute: false) }}" class="acc-subnav-btn">
                <i class="fa fa-file-invoice"></i> Fee Structure
            </a>
            <a href="{{ route('admin.account.expenses.index', absolute: false) }}" class="acc-subnav-btn">
                <i class="fa fa-receipt"></i> Expenses
            </a>
            <a href="{{ route('admin.account.payments.index', absolute: false) }}" class="acc-subnav-btn">
                <i class="fa fa-credit-card"></i> Payments
            </a>
            <a href="{{ route('admin.account.receipts.index', absolute: false) }}" class="acc-subnav-btn">
                <i class="fa fa-file-alt"></i> Receipts
            </a>
            <a href="{{ route('admin.account.purchases.index', absolute: false) }}" class="acc-subnav-btn">
                <i class="fa fa-shopping-cart"></i> Purchases
            </a>
            <a href="{{ route('admin.account.sales.index', absolute: false) }}" class="acc-subnav-btn">
                <i class="fa fa-chart-line"></i> Sales
            </a>
        </div>

        {{-- Row 1: Fees Collection Statistics (Left) + Fee Overview (Right) --}}
        <div class="acc-grid-row">
            {{-- Left: Fees Collection Statistics --}}
            <div>
                <div class="box box-primary borderwhite">
                    <div class="box-header with-border">
                        <h3 class="box-title">Fees Collection Statistics For - {{ date('M Y') }}</h3>
                        <div class="box-tools">
                            <button type="button" class="btn-box-tool" data-widget="collapse" onclick="toggleBox(this)">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="info-box-content-stats">
                            <span class="stat-text">RECEIVABLE: {{ number_format($total_amount) }} /</span>
                            <span class="stat-text">COLLECTION: {{ number_format($paid_amout) }} /</span>
                            <span class="stat-text">WAIVE OFF: {{ number_format($partial_amout) }} /</span>
                            <span class="stat-text">BALANCE: {{ number_format($total_amount - $paid_amout) }} /</span>
                            <span class="stat-text">TODAY COLLECTION: {{ number_format($total_fee_receive) }}</span>
                        </div>
                        <div class="chart-responsive" style="height: 220px;">
                            <canvas id="feecollectionChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Fee Overview --}}
            <div>
                <div class="box box-primary borderwhite">
                    <div class="box-header with-border">
                        <h3 class="box-title">Fee Overview</h3>
                        <div class="box-tools">
                            <button type="button" class="btn-box-tool" data-widget="collapse" onclick="toggleBox(this)">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="topprograssstart">
                            <div class="topprograssstart-content">
                                <p class="text-uppercase">
                                    <span>{{ $fees_overview['total_paid'] }} PAID</span>
                                    <span class="pull-right" style="color: #00a65a;">{{ round($fees_overview['paid_progress'], 2) }}%</span>
                                </p>
                                <div class="progress-group">
                                    <div class="progress progress-minibar">
                                        <div class="progress-bar progress-bar-success" style="width: {{ $fees_overview['paid_progress'] }}%"></div>
                                    </div>
                                </div>

                                <p class="text-uppercase">
                                    <span>{{ $fees_overview['total_unpaid'] }} UN PAID</span>
                                    <span class="pull-right" style="color: #dd4b39;">{{ round($fees_overview['unpaid_progress'], 2) }}%</span>
                                </p>
                                <div class="progress-group">
                                    <div class="progress progress-minibar">
                                        <div class="progress-bar progress-bar-alert" style="width: {{ $fees_overview['unpaid_progress'] }}%"></div>
                                    </div>
                                </div>

                                <p class="text-uppercase">
                                    <span>{{ $fees_overview['total_partial'] }} CONCESSION</span>
                                    <span class="pull-right" style="color: #f39c12;">{{ round($fees_overview['partial_progress'], 2) }}%</span>
                                </p>
                                <div class="progress-group">
                                    <div class="progress progress-minibar">
                                        <div class="progress-bar progress-bar-warning" style="width: {{ $fees_overview['partial_progress'] }}%"></div>
                                    </div>
                                </div>

                                <p class="text-uppercase">
                                    <span>{{ $fees_overview['total_free'] }} FREE</span>
                                    <span class="pull-right" style="color: #f95d9b;">{{ round($fees_overview['free_progress'], 2) }}%</span>
                                </p>
                                <div class="progress-group">
                                    <div class="progress progress-minibar">
                                        <div class="progress-bar" style="background-color: #f95d9b; width: {{ $fees_overview['free_progress'] }}%"></div>
                                    </div>
                                </div>

                                <p class="text-uppercase">
                                    <span>{{ $fees_overview['total_unpaid'] }} DEFAULTER</span>
                                    <span class="pull-right" style="color: #b41600;">{{ round($fees_overview['unpaid_progress'], 2) }}%</span>
                                </p>
                                <div class="progress-group">
                                    <div class="progress progress-minibar">
                                        <div class="progress-bar" style="background-color: #b41600; width: {{ $fees_overview['unpaid_progress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 2: Expenses (Bar Chart Left) + Expenses (Doughnut Chart Right) --}}
        <div class="acc-grid-row">
            {{-- Left: Expenses Bar Chart --}}
            <div>
                <div class="box box-info borderwhite">
                    <div class="box-header with-border">
                        <h3 class="box-title">Expenses For - {{ date('F Y') }}</h3>
                        <div class="box-tools">
                            <button type="button" class="btn-box-tool" data-widget="collapse" onclick="toggleBox(this)">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="info-box-content-stats">
                            <span class="stat-text-large">TOTAL: {{ number_format($month_expense) }}</span>
                            <span class="stat-text-large">TODAY: {{ number_format($today_expns) }}</span>
                        </div>
                        <div class="chart" style="height: 220px;">
                            <canvas id="monthexpChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Expenses Doughnut Chart --}}
            <div>
                <div class="box box-primary borderwhite">
                    <div class="box-header with-border">
                        <h3 class="box-title">Expenses For - {{ date('F Y') }}</h3>
                        <div class="box-tools">
                            <button type="button" class="btn-box-tool" data-widget="collapse" onclick="toggleBox(this)">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart-responsive" style="height: 220px; display: flex; align-items: center; justify-content: center;">
                            <canvas id="doughnut-chart-expenses" height="180"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script>
        function toggleBox(btn) {
            var box = btn.closest('.box');
            var body = box.querySelector('.box-body');
            var icon = btn.querySelector('i');
            if (body.style.display === 'none') {
                body.style.display = 'block';
                icon.className = 'fa fa-minus';
            } else {
                body.style.display = 'none';
                icon.className = 'fa fa-plus';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // ===== Line Chart: Fees Collection =====
            var currentMonthDays = @json($current_month_days);
            var daysCollection = @json($days_collection);

            var ctxCol = document.getElementById("feecollectionChart");
            if (ctxCol) {
                new Chart(ctxCol.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: currentMonthDays,
                        datasets: [{
                            label: "Collection",
                            lineTension: 0.3,
                            backgroundColor: "rgba(78, 115, 223, 0.08)",
                            borderColor: "rgba(78, 115, 223, 1)",
                            pointRadius: 3,
                            pointBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointBorderColor: "rgba(78, 115, 223, 1)",
                            pointHoverRadius: 4,
                            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                            pointHitRadius: 10,
                            pointBorderWidth: 2,
                            data: daysCollection,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        layout: {
                            padding: { left: 5, right: 15, top: 15, bottom: 5 }
                        },
                        scales: {
                            xAxes: [{
                                gridLines: { display: false, drawBorder: false },
                                ticks: { fontColor: "#858796", fontSize: 11 }
                            }],
                            yAxes: [{
                                gridLines: {
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)",
                                    drawBorder: false,
                                    borderDash: [2],
                                    zeroLineBorderDash: [2]
                                },
                                ticks: {
                                    fontColor: "#858796",
                                    fontSize: 11,
                                    suggestedMin: -1.0,
                                    suggestedMax: 1.0,
                                    stepSize: 0.2
                                }
                            }]
                        },
                        legend: { display: false },
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyFontColor: "#858796",
                            titleMarginBottom: 10,
                            titleFontColor: '#6e707e',
                            titleFontSize: 13,
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 12,
                            yPadding: 12,
                            displayColors: false,
                            intersect: false,
                            mode: 'index',
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, chart) {
                                    var label = chart.datasets[tooltipItem.datasetIndex].label || '';
                                    return label + ': ' + tooltipItem.yLabel;
                                }
                            }
                        }
                    }
                });
            }

            // ===== Bar Chart: Monthly Expenses =====
            var monthExpDays = @json($month_exp_days);
            var daysExpPaid = @json($days_exp_paid);

            var ctxExp = document.getElementById("monthexpChart");
            if (ctxExp) {
                new Chart(ctxExp.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: monthExpDays,
                        datasets: [{
                            label: "Expenses",
                            backgroundColor: "#b41600",
                            hoverBackgroundColor: "#d32f2f",
                            borderColor: "#b41600",
                            data: daysExpPaid,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        layout: {
                            padding: { left: 5, right: 15, top: 15, bottom: 5 }
                        },
                        scales: {
                            xAxes: [{
                                gridLines: { display: false, drawBorder: false },
                                ticks: { fontColor: "#858796", fontSize: 11 },
                                maxBarThickness: 20,
                            }],
                            yAxes: [{
                                gridLines: {
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)",
                                    drawBorder: false,
                                    borderDash: [2],
                                    zeroLineBorderDash: [2]
                                },
                                ticks: {
                                    fontColor: "#858796",
                                    fontSize: 11,
                                    suggestedMin: -1.0,
                                    suggestedMax: 1.0,
                                    stepSize: 0.2
                                }
                            }]
                        },
                        legend: { display: false },
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyFontColor: "#858796",
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 12,
                            yPadding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(tooltipItem, chart) {
                                    var label = chart.datasets[tooltipItem.datasetIndex].label || '';
                                    return label + ': ' + tooltipItem.yLabel;
                                }
                            }
                        }
                    }
                });
            }

            // ===== Doughnut Chart: Expenses by Category =====
            var expenseGraph = @json($expensegraph);
            var expLabels = expenseGraph.map(function(item) { return item.exp_category; });
            var expData = expenseGraph.map(function(item) { return item.total; });
            var expColors = ["#03a9f4", "#c53da9", "#757575", "#8e24aa", "#d81b60", "#7cb342", "#fb8c00", "#fb3b3b"];

            var ctxDoughnut = document.getElementById("doughnut-chart-expenses");
            if (ctxDoughnut) {
                new Chart(ctxDoughnut.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: expLabels,
                        datasets: [{
                            data: expData.length > 0 && expData.some(function(v){ return v > 0; }) ? expData : [1, 1, 1, 1],
                            backgroundColor: expColors.slice(0, expLabels.length),
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        circumference: Math.PI,
                        rotation: -Math.PI,
                        legend: {
                            position: 'top',
                            labels: { boxWidth: 12, fontSize: 11 }
                        },
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        }
                    }
                });
            }
        });
    </script>
    @endpush
@endsection
