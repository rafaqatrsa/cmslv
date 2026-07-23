@extends('admin.layouts.app')

@section('title', 'Chart of Accounts')

@php
    $formatAmount = fn (float|int $value): string => rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
    $feeRows = [
        ['label' => 'PAID', 'total' => $feeOverview['total_paid'], 'progress' => $feeOverview['paid_progress'], 'color' => '#00a65a', 'href' => $branchId ? url('/admin/account/studentfee/getfeesoverview/'.$branchId.'/paid') : '#'],
        ['label' => 'UN PAID', 'total' => $feeOverview['total_unpaid'], 'progress' => $feeOverview['unpaid_progress'], 'color' => '#dd4b39', 'href' => null],
        ['label' => 'CONCESSION', 'total' => $feeOverview['total_partial'], 'progress' => $feeOverview['partial_progress'], 'color' => '#f39c12', 'href' => null],
        ['label' => 'FREE', 'total' => $feeOverview['total_free'], 'progress' => $feeOverview['free_progress'], 'color' => '#f95d9b', 'href' => $branchId ? url('/admin/account/studentfee/getfeesoverview/'.$branchId.'/free') : '#'],
        ['label' => 'DEFAULTER', 'total' => $feeOverview['total_unpaid'], 'progress' => $feeOverview['unpaid_progress'], 'color' => '#b41600', 'href' => $branchId ? url('/admin/account/studentfee/getfeesoverview/'.$branchId.'/defaulter') : '#'],
    ];
@endphp

@section('content')
    <style>
        .legacy-account-dashboard {
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .legacy-dashboard-row {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(330px, 1fr);
            gap: 6px;
            margin-bottom: 12px;
        }

        .legacy-box {
            overflow: hidden;
            border: 1px solid #d2d6de;
            border-radius: 5px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .18);
        }

        .legacy-box-header {
            min-height: 21px;
            border-bottom: 1px solid #d2d6de;
            background: #2f61b3;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            line-height: 21px;
            padding: 0 4px;
        }

        .legacy-box-header .box-tool {
            float: right;
            margin-top: 2px;
            border: 0;
            background: transparent;
            color: #fff;
            font-size: 10px;
        }

        .legacy-box-body {
            min-height: 205px;
            padding: 14px 16px 12px;
        }

        .legacy-stat-line {
            margin-bottom: 10px;
            text-align: center;
            white-space: nowrap;
        }

        .legacy-stat-line span {
            display: inline;
            padding-right: 10px;
            color: #000;
            font-size: 12px;
            font-weight: 600;
        }

        .legacy-expense-total span {
            padding-right: 20px;
            font-size: 16px;
            font-weight: 400;
        }

        .legacy-fee-overview {
            padding: 8px 10px;
        }

        .legacy-fee-overview h5 {
            margin: 0 0 8px;
            border-bottom: 1px solid #d2d6de;
            padding-bottom: 10px;
            color: #000;
            font-size: 12px;
            font-weight: 700;
        }

        .legacy-progress-row {
            margin: 10px 0 4px;
            text-transform: uppercase;
        }

        .legacy-progress-row span {
            float: right;
        }

        .legacy-progress-row a {
            color: #00a9e0;
            text-decoration: none;
        }

        .legacy-progress {
            height: 4px;
            overflow: hidden;
            border-radius: 2px;
            background: #f5f5f5;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .1);
        }

        .legacy-progress-bar {
            height: 100%;
            min-width: 0;
        }

        .legacy-chart-wrap {
            position: relative;
            height: 170px;
        }

        @media (max-width: 992px) {
            .legacy-dashboard-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="legacy-account-dashboard">
        <div class="legacy-dashboard-row">
            <div class="legacy-box">
                <div class="legacy-box-header">Fees Collection Statistics For - {{ $currentMonthShort }}</div>
                <div class="legacy-box-body">
                    <div class="legacy-stat-line">
                        <span>RECEIVABLE: {{ $formatAmount($feeOverview['receivable']) }} /</span>
                        <span>COLLECTION: {{ $formatAmount($feeOverview['collection']) }} /</span>
                        <span>WAIVE OFF: {{ $formatAmount($feeOverview['waive_off']) }} /</span>
                        <span>BALANCE: {{ $formatAmount($feeOverview['balance']) }} /</span>
                        <span>TODAY COLLECTION: {{ $formatAmount($feeOverview['today_collection']) }}</span>
                    </div>
                    <div class="legacy-chart-wrap">
                        <canvas id="feecollectionChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="legacy-box">
                <div class="legacy-box-header">Fee Overview</div>
                <div class="legacy-fee-overview">
                    @foreach ($feeRows as $row)
                        <p class="legacy-progress-row">
                            {{ $row['total'] }} {{ $row['label'] }}
                            <span>
                                @if ($row['href'])
                                    <a href="{{ $row['href'] }}">{{ round($row['progress'], 2) }}%</a>
                                @else
                                    {{ round($row['progress'], 2) }}%
                                @endif
                            </span>
                        </p>
                        <div class="legacy-progress">
                            <div class="legacy-progress-bar" style="width: {{ min($row['progress'], 100) }}%; background: {{ $row['color'] }};"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="legacy-dashboard-row">
            <div class="legacy-box">
                <div class="legacy-box-header">Expenses For - {{ $currentMonthLong }}</div>
                <div class="legacy-box-body">
                    <div class="legacy-stat-line legacy-expense-total">
                        <span>TOTAL: {{ $formatAmount($expenseOverview['month_total']) }}</span>
                        <span>TODAY: {{ $formatAmount($expenseOverview['today_total']) }}</span>
                    </div>
                    <div class="legacy-chart-wrap">
                        <canvas id="monthexpChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="legacy-box">
                <div class="legacy-box-header">
                    Expenses For - {{ $currentMonthLong }}
                    <button class="box-tool" type="button" aria-label="Collapse"><i class="fa fa-minus"></i></button>
                </div>
                <div class="legacy-box-body">
                    <div class="legacy-chart-wrap">
                        <canvas id="doughnut-chart-expenses" height="173"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.0/Chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Chart === 'undefined') {
                return;
            }

            var current_month_days = @json($days);
            var days_collection = @json($dailyCollections);
            var month_exp_days = @json($days);
            var days_exp_paid = @json($dailyExpenses);
            var expenseLabels = @json($expenseOverview['head_labels']);
            var expenseTotals = @json($expenseOverview['head_totals']);

            new Chart(document.getElementById('feecollectionChart'), {
                type: 'line',
                data: {
                    labels: current_month_days,
                    datasets: [{
                        label: 'Collection',
                        lineTension: 0.3,
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: days_collection,
                    }],
                },
                options: legacyLineOptions('date'),
            });

            new Chart(document.getElementById('monthexpChart'), {
                type: 'bar',
                data: {
                    labels: month_exp_days,
                    datasets: [{
                        label: 'Expenses',
                        backgroundColor: '#b41600',
                        hoverBackgroundColor: '#b41600',
                        borderColor: '#b41600',
                        data: days_exp_paid,
                    }],
                },
                options: legacyLineOptions('month'),
            });

            new Chart(document.getElementById('doughnut-chart-expenses'), {
                type: 'doughnut',
                data: {
                    labels: expenseLabels,
                    datasets: [{
                        backgroundColor: ['#03a9f4', '#c53da9', '#757575', '#8e24aa', '#d81b60', '#7cb342', '#fb8c00', '#fb3b3b'],
                        data: expenseTotals,
                    }],
                },
                options: {
                    responsive: true,
                    circumference: Math.PI,
                    rotation: -Math.PI,
                    legend: {
                        position: 'top',
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                    },
                },
            });
        });

        function legacyLineOptions(unit) {
            return {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0,
                    },
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: unit,
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        gridLines: {
                            color: 'rgb(234, 236, 244)',
                            zeroLineColor: 'rgb(234, 236, 244)',
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2],
                        },
                    }],
                },
                legend: {
                    display: false,
                },
                tooltips: {
                    backgroundColor: 'rgb(255,255,255)',
                    bodyFontColor: '#858796',
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';

                            return datasetLabel + ': Rs.' + tooltipItem.yLabel;
                        },
                    },
                },
            };
        }
    </script>
@endsection
