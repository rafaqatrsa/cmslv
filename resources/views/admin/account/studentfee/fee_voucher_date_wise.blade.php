@extends('admin.layouts.app')

@section('title', 'Assign Fee Voucher Date Wise')

@push('styles')
<style>
    .feevoucher-datewise-container {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #333;
        font-size: 14px;
    }

    .main-box-title {
        font-size: 20px;
        font-weight: 500;
        margin: 0 0 15px 0;
        color: #333;
    }

    .box {
        position: relative;
        border-radius: 4px;
        background: #ffffff;
        border: 1px solid #d2d6de;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .box-header {
        color: #333;
        background: #fff;
        border-bottom: 1px solid #f4f4f4;
        padding: 12px 15px;
    }

    .box-title {
        font-size: 16px;
        margin: 0;
        line-height: 1.2;
        font-weight: 600;
        color: #333;
    }

    .box-body {
        padding: 15px;
        background: #fff;
    }

    .box-footer {
        border-top: 1px solid #f4f4f4;
        padding: 12px 15px;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 13px;
        color: #333;
    }

    .form-group .req {
        color: #ff0000;
        font-weight: bold;
    }

    .form-control-cmsc {
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 13px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        border: 1px solid #d2d6de;
        border-radius: 4px;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        box-sizing: border-box;
    }

    .form-control-cmsc:focus {
        border-color: #1e3a8a;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 6px rgba(30,58,138,.4);
    }

    .grid-2-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    @media (max-width: 768px) {
        .grid-2-col {
            grid-template-columns: 1fr;
        }
    }

    .btn-cmsc-primary {
        background-color: #1e3a8a;
        color: #ffffff;
        border: 1px solid #1e3a8a;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-cmsc-primary:hover {
        background-color: #162c6d;
        color: #ffffff;
    }

    .total-fee-label {
        font-size: 15px;
        font-weight: bold;
        color: #d11406;
    }

    .card-wrapper {
        max-width: 680px;
    }

    .table-results {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-top: 15px;
    }

    .table-results th {
        background-color: #f8fafc;
        padding: 10px;
        border: 1px solid #e2e8f0;
        font-weight: 600;
        font-size: 13px;
        text-align: left;
    }

    .table-results td {
        padding: 9px 10px;
        border: 1px solid #e2e8f0;
        font-size: 13px;
    }
</style>
@endpush

@section('content')
<div class="feevoucher-datewise-container">
    <h2 class="main-box-title">Assign Fee Voucher Date Wise</h2>

    <div class="card-wrapper">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Student Information</h3>
            </div>

            <form id="datewiseForm" action="{{ url('admin/account/studentfee/assignfeevoucherdatewise/' . $brc_id) }}" method="POST">
                @csrf
                <div class="box-body">
                    {{-- Row 1: Branch & Admission No --}}
                    <div class="grid-2-col">
                        <div class="form-group">
                            <label for="brc_id">Branch <span class="req">*</span></label>
                            <select id="brc_id" name="brc_id" class="form-control-cmsc" onchange="changeBranch(this.value)">
                                @foreach ($branchlist as $brc)
                                    <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                        {{ $brc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="student_id">Admission No <span class="req">*</span></label>
                            <select id="student_id" name="student_id" class="form-control-cmsc" onchange="calculateTotalFee()" required>
                                <option value="">Select</option>
                                @foreach ($studentdrop as $std)
                                    <option value="{{ $std->student_id }}" {{ (string)$student_id === (string)$std->student_id ? 'selected' : '' }}>
                                        {{ $std->admission_no }} - {{ $std->firstname }} {{ $std->lastname }} {{ $std->father_name ? '('.$std->father_name.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Row 2: From Month & To Month --}}
                    <div class="grid-2-col">
                        <div class="form-group">
                            <label for="from_month">From Month <span class="req">*</span></label>
                            <input type="date" id="from_month" name="from_month" class="form-control-cmsc" value="{{ $from_month ?: date('Y-m-d') }}" onchange="calculateTotalFee()" required>
                        </div>

                        <div class="form-group">
                            <label for="to_month">To Month <span class="req">*</span></label>
                            <input type="date" id="to_month" name="to_month" class="form-control-cmsc" value="{{ $to_month ?: date('Y-m-d') }}" onchange="calculateTotalFee()" required>
                        </div>
                    </div>

                    {{-- Row 3: Issue Date & Due Date --}}
                    <div class="grid-2-col">
                        <div class="form-group">
                            <label for="issue_date">Issue Date <span class="req">*</span></label>
                            <input type="date" id="issue_date" name="issue_date" class="form-control-cmsc" value="{{ $issue_date ?: date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="due_date">Due Date <span class="req">*</span></label>
                            <input type="date" id="due_date" name="due_date" class="form-control-cmsc" value="{{ $due_date ?: date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div>
                        <span class="total-fee-label">Total Fee:- <span id="total_fee_display">{{ !empty($totalfee) ? number_format($totalfee, 0, '.', ',') : '' }}</span></span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 15px;">
                        <label style="font-weight: normal; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="notification" value="1" checked> Notification
                        </label>
                        <button type="submit" name="search" value="search" class="btn-cmsc-primary">
                            <i class="fa fa-address-card"></i> Generate Fee Voucher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Details after Voucher Generation --}}
    @if (!empty($student_detail))
        <div class="box" style="margin-top: 20px;">
            <div class="box-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="box-title">Generated Fee Voucher</h3>
                <a href="{{ url('admin/account/studentfee/printfeevoucher?student_id=' . $student_detail->student_id . '&brc_id=' . $brc_id . '&issue_date=' . $issue_date . '&due_date=' . $due_date . '&from_month=' . $from_month . '&to_month=' . $to_month) }}" target="_blank" class="btn-cmsc-primary" style="text-decoration: none;">
                    <i class="fa fa-print"></i> Print Fee Voucher
                </a>
            </div>
            <div class="box-body" style="overflow-x: auto;">
                <table class="table-results">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Admission No</th>
                            <th>Class</th>
                            <th>Student Name</th>
                            <th>Father Name</th>
                            <th>Father Phone</th>
                            <th>Generated Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $student_detail->branch_name ?? 'Main Campus' }}</td>
                            <td>{{ $student_detail->admission_no }}</td>
                            <td>{{ $student_detail->class }} {{ $student_detail->section ? '('.$student_detail->section.')' : '' }}</td>
                            <td>{{ $student_detail->firstname }} {{ $student_detail->lastname }}</td>
                            <td>{{ $student_detail->father_name }}</td>
                            <td>{{ $student_detail->father_phone }}</td>
                            <td style="font-weight: bold; color: #16a34a;">{{ number_format($totalfee, 0, '.', ',') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function changeBranch(brcId) {
        if (brcId) {
            window.location.href = "{{ url('admin/account/studentfee/assignfeevoucherdatewise') }}/" + brcId;
        }
    }

    function calculateTotalFee() {
        var studentId = document.getElementById('student_id').value;
        var fromMonth = document.getElementById('from_month').value;
        var toMonth = document.getElementById('to_month').value;

        if (!studentId || !fromMonth || !toMonth) {
            return;
        }

        var url = "{{ url('admin/account/studentfee/getStudentFeeSummary') }}?student_id=" + studentId + "&from_month=" + fromMonth + "&to_month=" + toMonth;
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.total_fee !== undefined) {
                    document.getElementById('total_fee_display').innerText = Number(data.total_fee).toLocaleString();
                }
            })
            .catch(function(err) {
                console.error(err);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var studentId = document.getElementById('student_id').value;
        if (studentId) {
            calculateTotalFee();
        }
    });
</script>
@endpush
@endsection