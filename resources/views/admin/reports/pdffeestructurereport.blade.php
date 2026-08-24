<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fee Structure Report</title>
    <style type="text/css">
        @page {
            margin: 25px 35px 25px 35px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #000;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .logo-col {
            width: 22%;
            text-align: left;
        }

        .logo-img {
            max-height: 85px;
            max-width: 160px;
        }

        .title-col {
            width: 78%;
            text-align: center;
            padding-right: 12%;
        }

        .school-name {
            font-size: 22px;
            font-weight: bold;
            color: #000000;
            margin: 0 0 3px 0;
            line-height: 1.1;
        }

        .school-address {
            font-size: 13.5px;
            font-weight: bold;
            color: #000000;
            margin: 0 0 3px 0;
        }

        .school-phone {
            font-size: 13.5px;
            font-weight: bold;
            color: #000000;
            margin: 0 0 3px 0;
        }

        .report-title {
            font-size: 14.5px;
            font-weight: bold;
            color: #000000;
            margin: 2px 0 0 0;
        }

        .header-divider {
            border-bottom: 1.5px solid #000000;
            margin-top: 6px;
            margin-bottom: 10px;
            width: 100%;
        }

        /* Fee Structure Main Table */
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000000;
            font-size: 13px;
        }

        .fee-table th {
            border: 1px solid #000000;
            padding: 6px 10px;
            background-color: #ffffff;
            color: #000000;
            font-weight: bold;
            font-size: 13.5px;
        }

        .fee-table td {
            border: 1px solid #000000;
            padding: 5px 10px;
            color: #000000;
            background-color: #ffffff;
        }

        .class-heading-td {
            font-size: 13.5px;
            font-weight: normal;
            background-color: #ffffff;
            padding: 5px 10px;
            border: 1px solid #000000;
        }

        .fee-head-td {
            padding: 5px 10px;
            border: 1px solid #000000;
        }

        .amount-td {
            text-align: center;
            padding: 5px 10px;
            border: 1px solid #000000;
        }

        .total-label-td {
            text-align: right;
            font-weight: bold;
            padding: 7px 25px;
            border: 1px solid #000000;
            font-size: 13.5px;
        }

        .total-amount-td {
            text-align: center;
            font-weight: bold;
            padding: 7px 10px;
            border: 1px solid #000000;
            font-size: 13.5px;
        }
    </style>
</head>
<body>
    {{-- Report Header --}}
    <table class="header-table">
        <tr>
            <td class="logo-col">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo" />
                @endif
            </td>
            <td class="title-col">
                <div class="school-name">{{ $settinglist->name ?? 'TNT SOL' }}</div>
                <div class="school-address">{{ $settinglist->address ?? 'Gujranwala' }}</div>
                <div class="school-phone">{{ $settinglist->phone ?? '923466049180' }}</div>
                <div class="report-title">Branch:- {{ $branch->name ?? 'Main Campus' }} Fee Structure Report</div>
            </td>
        </tr>
    </table>

    <div class="header-divider"></div>

    {{-- Main Fee Structure Table --}}
    <table class="fee-table">
        <thead>
            <tr>
                <th style="width: 25%; text-align: left;">Class</th>
                <th style="width: 50%; text-align: left;">Fee Type</th>
                <th style="width: 25%; text-align: center;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($feemasterList as $feegroup)
                {{-- Class Header Row --}}
                <tr>
                    <td colspan="3" class="class-heading-td">
                        {{ $feegroup->class_name ?? '' }}
                    </td>
                </tr>

                {{-- Fee Heads / Types --}}
                @php
                    $groupTotal = 0;
                @endphp
                @if (!empty($feegroup->feetypes))
                    @foreach ($feegroup->feetypes as $item)
                        @php
                            $groupTotal += (float) $item->amount;
                        @endphp
                        <tr>
                            <td style="border: 1px solid #000000;"></td>
                            <td class="fee-head-td">
                                {{ $item->type }}
                            </td>
                            <td class="amount-td">
                                {{ number_format((float) $item->amount, 0, '.', '') }}
                            </td>
                        </tr>
                    @endforeach
                @endif

                {{-- Total Amount Row per Class --}}
                <tr>
                    <td colspan="2" class="total-label-td">
                        Total Amount
                    </td>
                    <td class="total-amount-td">
                        {{ number_format($groupTotal, 0, '.', '') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px;">
                        No fee structure records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@if (!empty($autoPrint))
<script type="text/javascript">
    window.onload = function() {
        window.print();
    };
</script>
@endif
</body>
</html>
