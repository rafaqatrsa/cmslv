@extends('admin.layouts.app')

@section('title', 'Fee Voucher Student & Sibling')

@push('styles')
<style>
    .feevoucher-sibling-container {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #333;
        font-size: 14px;
    }

    .main-box-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 15px 0;
        color: #333;
    }

    .nav-tabs-cmsc {
        display: flex;
        border-bottom: 2px solid #ddd;
        margin-bottom: 20px;
        gap: 5px;
    }

    .nav-tabs-cmsc .nav-tab-item {
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        color: #555;
        text-decoration: none;
        border: 1px solid transparent;
        border-bottom: none;
        border-radius: 4px 4px 0 0;
        background: #f8fafc;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .nav-tabs-cmsc .nav-tab-item.active {
        background: #fff;
        color: #1e3a8a;
        border: 1px solid #ddd;
        border-bottom: 2px solid #fff;
        margin-bottom: -2px;
    }

    .box {
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .box-header {
        padding: 12px 15px;
        border-bottom: 1px solid #f4f4f4;
        background-color: #ffffff;
    }

    .box-title {
        font-size: 15px;
        font-weight: 600;
        margin: 0;
        color: #333;
    }

    .box-body {
        padding: 15px;
    }

    .box-footer {
        padding: 12px 15px;
        background-color: #fcfcfc;
        border-top: 1px solid #f4f4f4;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .form-group label .req {
        color: #e11d48;
        font-weight: bold;
    }

    .form-control-cmsc {
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 13px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 3px;
        box-sizing: border-box;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
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
        padding: 7px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
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
<div class="feevoucher-sibling-container">
    <h2 class="main-box-title">Fee Voucher Student & Sibling</h2>

    {{-- Tabs matching screenshot --}}
    <div class="nav-tabs-cmsc">
        <a href="javascript:void(0)" onclick="switchTab('student')" id="tabBtnStudent" class="nav-tab-item {{ $active_tab === 'student' ? 'active' : '' }}">
            <i class="fa fa-newspaper-o"></i> Student Wise Fee Voucher
        </a>
        <a href="javascript:void(0)" onclick="switchTab('sibling')" id="tabBtnSibling" class="nav-tab-item {{ $active_tab === 'sibling' ? 'active' : '' }}">
            <i class="fa fa-newspaper-o"></i> Sibling Wise Fee Voucher
        </a>
    </div>

    {{-- TAB 1: Student Wise Fee Voucher --}}
    <div id="tabContentStudent" style="{{ $active_tab === 'student' ? 'display: block;' : 'display: none;' }}">
        <div class="card-wrapper">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Student Information</h3>
                </div>

                <form id="studentWiseForm" action="{{ url('admin/account/studentfee/feevoucherstudentsibling/' . $brc_id . '/1') }}" method="POST">
                    @csrf
                    <div class="box-body">
                        {{-- Row 1: Branch & Admission No --}}
                        <div class="grid-2-col">
                            <div class="form-group">
                                <label for="brc_id_std">Branch <span class="req">*</span></label>
                                <select id="brc_id_std" name="brc_id" class="form-control-cmsc" onchange="changeBranch(this.value, 1)">
                                    @foreach ($branchlist as $brc)
                                        <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                            {{ $brc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="student_id">Admission No <span class="req">*</span></label>
                                <select id="student_id" name="student_id" class="form-control-cmsc" onchange="calculateStudentFee()" required>
                                    <option value="">Select</option>
                                    @foreach ($studentdrop as $std)
                                        <option value="{{ $std->student_id }}" {{ (string)old('student_id', $student_detail->student_id ?? '') === (string)$std->student_id ? 'selected' : '' }}>
                                            {{ $std->admission_no }} - {{ $std->firstname }} {{ $std->lastname }} {{ $std->father_name ? '('.$std->father_name.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 2: Issue Date & Due Date --}}
                        <div class="grid-2-col">
                            <div class="form-group">
                                <label for="issue_date_std">Issue Date <span class="req">*</span></label>
                                <input type="text" id="issue_date_std" name="issue_date" class="form-control-cmsc" value="{{ old('issue_date', $issue_date) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="due_date_std">Due Date <span class="req">*</span></label>
                                <input type="text" id="due_date_std" name="due_date" class="form-control-cmsc" value="{{ old('due_date', $due_date) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="total-fee-label">
                            Total Fee:- <span id="total_student_fee_display">{{ number_format($totalfee, 0, '.', ',') }}</span>
                        </div>

                        <button type="submit" name="search" value="search" class="btn-cmsc-primary">
                            <i class="fa fa-address-card"></i> Generate Fee Voucher
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Details after Student Voucher Generation --}}
        @if (!empty($student_detail))
            <div class="box" style="margin-top: 20px;">
                <div class="box-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="box-title">Generated Fee Voucher</h3>
                    <a href="{{ url('admin/account/studentfee/printfeevoucher?student_id=' . $student_detail->student_id . '&brc_id=' . $brc_id . '&issue_date=' . $issue_date . '&due_date=' . $due_date) }}" target="_blank" class="btn-cmsc-primary">
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

    {{-- TAB 2: Sibling Wise Fee Voucher --}}
    <div id="tabContentSibling" style="{{ $active_tab === 'sibling' ? 'display: block;' : 'display: none;' }}">
        <div class="card-wrapper">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Sibling Information</h3>
                </div>

                <form id="siblingWiseForm" action="{{ url('admin/account/studentfee/feevoucherstudentsibling/' . $brc_id . '/2') }}" method="POST">
                    @csrf
                    <div class="box-body">
                        {{-- Row 1: Branch & Sibling Code --}}
                        <div class="grid-2-col">
                            <div class="form-group">
                                <label for="brc_id_sib">Branch <span class="req">*</span></label>
                                <select id="brc_id_sib" name="brc_id" class="form-control-cmsc" onchange="changeBranch(this.value, 2)">
                                    @foreach ($branchlist as $brc)
                                        <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                            {{ $brc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sibling_id">Sibling Code <span class="req">*</span></label>
                                <select id="sibling_id" name="sibling_id" class="form-control-cmsc" onchange="calculateSiblingFee()" required>
                                    <option value="">Select</option>
                                    @foreach ($siblingdrop as $sib)
                                        <option value="{{ $sib->sibling_id ?? $sib->id }}" {{ (string)old('sibling_id') === (string)($sib->sibling_id ?? $sib->id) ? 'selected' : '' }}>
                                            {{ $sib->sibling_code ?? $sib->code ?? $sib->admission_no }} - {{ $sib->sibling_name ?? $sib->name ?? $sib->father_name }} {{ !empty($sib->sibling_phone) ? '('.$sib->sibling_phone.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 2: Issue Date & Due Date --}}
                        <div class="grid-2-col">
                            <div class="form-group">
                                <label for="issue_date_sib">Issue Date <span class="req">*</span></label>
                                <input type="text" id="issue_date_sib" name="issue_date" class="form-control-cmsc" value="{{ old('issue_date', $issue_date) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="due_date_sib">Due Date <span class="req">*</span></label>
                                <input type="text" id="due_date_sib" name="due_date" class="form-control-cmsc" value="{{ old('due_date', $due_date) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="total-fee-label">
                            Total Fee:- <span id="total_sibling_fee_display">{{ number_format($siblingtotalfee, 0, '.', ',') }}</span>
                        </div>

                        <button type="submit" name="search" value="sibling" class="btn-cmsc-primary">
                            <i class="fa fa-address-card"></i> Generate Fee Voucher
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Details after Sibling Voucher Generation --}}
        @if (!empty($sibling_detail) && count($sibling_detail) > 0)
            <div class="box" style="margin-top: 20px;">
                <div class="box-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="box-title">Generated Sibling Fee Vouchers ({{ count($sibling_detail) }} Students)</h3>
                    @php
                        $sibStdIds = $sibling_detail->pluck('student_id')->toArray();
                        $printQuery = http_build_query([
                            'student_id' => $sibStdIds,
                            'brc_id' => $brc_id,
                            'issue_date' => $issue_date,
                            'due_date' => $due_date,
                        ]);
                    @endphp
                    <a href="{{ url('admin/account/studentfee/printfeevoucher?' . $printQuery) }}" target="_blank" class="btn-cmsc-primary">
                        <i class="fa fa-print"></i> Print All Sibling Vouchers
                    </a>
                </div>
                <div class="box-body" style="overflow-x: auto;">
                    <table class="table-results">
                        <thead>
                            <tr>
                                <th>Admission No</th>
                                <th>Class</th>
                                <th>Student Name</th>
                                <th>Father Name</th>
                                <th>Father Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sibling_detail as $std)
                                <tr>
                                    <td>{{ $std->admission_no }}</td>
                                    <td>{{ $std->class }} {{ $std->section ? '('.$std->section.')' : '' }}</td>
                                    <td>{{ $std->firstname }} {{ $std->lastname }}</td>
                                    <td>{{ $std->father_name }}</td>
                                    <td>{{ $std->father_phone }}</td>
                                    <td>
                                        <a href="{{ url('admin/account/studentfee/printfeevoucher?student_id=' . $std->student_id . '&brc_id=' . $brc_id . '&issue_date=' . $issue_date . '&due_date=' . $due_date) }}" target="_blank" class="btn btn-xs btn-default" style="border: 1px solid #ccc; padding: 2px 6px; text-decoration: none;">
                                            <i class="fa fa-print"></i> Print
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function switchTab(tab) {
        var studentTabBtn = document.getElementById('tabBtnStudent');
        var siblingTabBtn = document.getElementById('tabBtnSibling');
        var studentContent = document.getElementById('tabContentStudent');
        var siblingContent = document.getElementById('tabContentSibling');

        if (tab === 'student') {
            studentTabBtn.classList.add('active');
            siblingTabBtn.classList.remove('active');
            studentContent.style.display = 'block';
            siblingContent.style.display = 'none';
        } else {
            siblingTabBtn.classList.add('active');
            studentTabBtn.classList.remove('active');
            siblingContent.style.display = 'block';
            studentContent.style.display = 'none';
        }
    }

    function changeBranch(brcId, tab) {
        if (brcId) {
            window.location.href = "{{ url('admin/account/studentfee/feevoucherstudentsibling') }}/" + brcId + "/" + tab;
        }
    }

    function calculateStudentFee() {
        var studentId = document.getElementById('student_id').value;
        if (!studentId) return;

        var url = "{{ url('admin/account/studentfee/getStudentFeeSummary') }}?student_id=" + studentId;
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.total_fee !== undefined) {
                    document.getElementById('total_student_fee_display').innerText = Number(data.total_fee).toLocaleString();
                }
            })
            .catch(function(err) {
                console.error(err);
            });
    }

    function calculateSiblingFee() {
        var siblingId = document.getElementById('sibling_id').value;
        var brcId = document.getElementById('brc_id_sib').value;
        if (!siblingId) return;

        var url = "{{ url('admin/account/studentfee/getSiblingFeeSummary') }}?sibling_id=" + siblingId + "&brc_id=" + brcId;
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.total_fee !== undefined) {
                    document.getElementById('total_sibling_fee_display').innerText = Number(data.total_fee).toLocaleString();
                }
            })
            .catch(function(err) {
                console.error(err);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var studentId = document.getElementById('student_id').value;
        if (studentId) {
            calculateStudentFee();
        }
    });
</script>
@endpush
@endsection