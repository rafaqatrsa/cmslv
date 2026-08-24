<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fee Voucher</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 4mm 3mm;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background: #fff;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .pagebreak {
                page-break-after: always;
            }
            .no-print {
                display: none !important;
            }
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 5px;
            background: #fff;
        }

        .no-print-toolbar {
            padding: 8px 12px;
            background: #1e3a8a;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .btn-print-now {
            background: #16a34a;
            color: #fff;
            border: none;
            padding: 6px 14px;
            font-weight: bold;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
        }

        .voucher-sheet {
            display: flex;
            width: 100%;
            gap: 6px;
            margin-bottom: 10px;
        }

        .voucher-col {
            flex: 1;
            border: 1.5px solid #000;
            padding: 4px 6px;
            position: relative;
            background: #fff;
        }

        .copy-header {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin: 0 0 2px 0;
            text-transform: capitalize;
        }

        .school-title {
            text-align: center;
            font-family: "Times New Roman", Times, serif;
            font-size: 15px;
            font-weight: bold;
            line-height: 1.1;
            margin: 0;
        }

        .branch-title {
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            margin: 1px 0 3px 0;
        }

        .bank-banner {
            border: 2px solid #000;
            text-align: center;
            padding: 2px 0;
            margin-bottom: 3px;
        }

        .bank-name {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            line-height: 1.1;
        }

        .bank-sub {
            font-size: 9px;
            margin: 0;
        }

        .acc-info {
            text-align: center;
            margin-bottom: 4px;
        }

        .acc-type {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
        }

        .acc-number {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 10.5px;
            margin-bottom: 3px;
        }

        .info-row b {
            font-weight: bold;
        }

        .session-box {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            border: 1.5px solid #000;
            background: #d1d5db !important;
            padding: 2px 0;
            margin: 3px 0 5px 0;
        }

        .particulars-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
            margin-bottom: 4px;
        }

        .particulars-table th, .particulars-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            font-size: 10.5px;
        }

        .particulars-table th {
            background: #fff;
            font-weight: bold;
            text-align: center;
        }

        .particulars-table td.center {
            text-align: center;
        }

        .particulars-table td.right {
            text-align: right;
        }

        .particulars-table td.bold {
            font-weight: bold;
        }

        .due-date-box {
            text-align: center;
            font-size: 12.5px;
            font-weight: bold;
            margin: 4px 0;
        }

        .terms-section {
            font-size: 8.5px;
            line-height: 1.3;
            margin-bottom: 6px;
        }

        .terms-section ul {
            margin: 2px 0;
            padding-left: 12px;
        }

        .depositor-line {
            font-size: 10px;
            margin-bottom: 4px;
        }

        .signature-box {
            text-align: right;
            margin-top: 15px;
            font-size: 10px;
        }

        .signature-box span {
            border-top: 1px dashed #000;
            padding-top: 2px;
            display: inline-block;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print no-print-toolbar">
        <span style="font-weight: bold; font-size: 14px;">Fee Voucher Preview (3-Copy Format)</span>
        <button class="btn-print-now" onclick="window.print()">Print Fee Voucher</button>
    </div>

    @foreach ($vouchers as $index => $v)
        <div class="voucher-sheet {{ $index > 0 ? 'pagebreak' : '' }}">
            @foreach (['School Copy', 'Parents Copy', 'Bank Copy'] as $copyName)
                <div class="voucher-col">
                    <div class="copy-header">{{ $copyName }}</div>
                    <div class="school-title">{{ ucwords(strtolower($settings->raw->name ?? 'Tnt Sol')) }}</div>
                    <div class="branch-title">{{ $v['student']->branch_name ?? 'Main Campus Gujranwala' }}</div>

                    <div class="bank-banner">
                        <div class="bank-name">{{ $bank_name }}</div>
                        <div class="bank-sub">{{ $bank_desc }}</div>
                    </div>

                    <div class="acc-info">
                        <div class="acc-type">Current A/C #</div>
                        <div class="acc-number">{{ $account_no }}</div>
                    </div>

                    <div class="info-row">
                        <span><b>Bill No:-</b> {{ $v['student']->admission_no }}</span>
                        <span><b>Issue Date:-</b> {{ date('d M, Y', strtotime(str_replace('/', '-', $issue_date))) }}</span>
                    </div>

                    <div class="info-row" style="margin-bottom: 2px;">
                        <span><b>Name:-</b> {{ strtoupper($v['student']->firstname . ' ' . $v['student']->lastname) }}</span>
                    </div>

                    <div class="info-row">
                        <span><b>Class:-</b> {{ $v['student']->class }} {{ $v['student']->section ? '- ' . $v['student']->section : '' }}</span>
                        <span><b>Admission No:-</b> {{ $v['student']->admission_no }}</span>
                    </div>

                    <div class="session-box">
                        Session:- {{ $session_name }}
                    </div>

                    <table class="particulars-table">
                        <thead>
                            <tr>
                                <th style="width: 25px;">Sr#</th>
                                <th style="text-align: left;">Particulars</th>
                                <th style="width: 65px; text-align: right;">Amount({{ $currency_symbol }})</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($v['particulars'] as $pIdx => $p)
                                <tr>
                                    <td class="center">{{ $pIdx + 1 }}</td>
                                    <td>{{ $p['name'] }}</td>
                                    <td class="right">{{ number_format($p['amount']) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="right bold">Total Amount:-</td>
                                <td class="right bold">{{ number_format($v['total_amount']) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="right bold">Payable within Due Date:-</td>
                                <td class="right bold">{{ number_format($v['total_amount']) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="due-date-box">
                        Due Date:-{{ date('d M, Y', strtotime(str_replace('/', '-', $due_date))) }}
                    </div>

                    <div class="terms-section">
                        <b>Payment Terms:</b>
                        <ul>
                            <li>Rs 50/- will be charged in case of Re-Issuance of Challan.</li>
                            <li>Parents must keep their copy for record.</li>
                            <li>Rs 15/day will be charged after due date.</li>
                        </ul>
                    </div>

                    <div class="depositor-line"><b>Depositor Name:-</b> _________________________</div>
                    <div class="depositor-line"><b>CNIC NO:-</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; _________________________</div>
                    <div class="depositor-line"><b>Contact No:-</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; _________________________</div>

                    <div class="signature-box">
                        <span>Cashier's / Accountant's</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</body>
</html>