@extends('admin.layouts.app')

@section('title', 'Assign Fee Voucher')

@push('styles')
<style>
    .feevoucher-container {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #333;
        font-size: 14px;
    }

    .page-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .main-box-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        color: #333;
    }

    .btn-print-empty {
        background-color: #16a34a;
        color: #ffffff;
        border: 1px solid #16a34a;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-print-empty:hover {
        background-color: #15803d;
        color: #ffffff;
    }

    .box {
        position: relative;
        border-radius: 4px;
        background: #ffffff;
        border: 1px solid #d2d6de;
        margin-bottom: 20px;
        width: 100%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .box-header {
        color: #333;
        background: #fff;
        border-bottom: 1px solid #f4f4f4;
        padding: 12px 15px;
    }

    .box-title {
        display: inline-block;
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

    .criteria-radios {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f4f4f4;
    }

    .radio-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #333;
        cursor: pointer;
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

    .btn-revert {
        background-color: #ef4444;
        color: #ffffff;
        border: 1px solid #ef4444;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-revert:hover {
        background-color: #dc2626;
        color: #ffffff;
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
<div class="feevoucher-container">
    {{-- Header --}}
    <div class="page-header-flex">
        <h2 class="main-box-title">Assign Fee Voucher</h2>
        <a href="{{ url('admin/account/studentfee/printfeevoucher?brc_id=' . $brc_id) }}" target="_blank" class="btn-print-empty">
            <i class="fa fa-print"></i> Print Empty Fee Voucher
        </a>
    </div>

    {{-- Select Criteria Card --}}
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Select Criteria</h3>
        </div>

        <form id="feevoucherForm" action="{{ url('admin/account/studentfee/assignfeevoucher/' . $brc_id) }}" method="POST">
            @csrf
            <div class="box-body">
                {{-- Radio switches --}}
                <div class="criteria-radios">
                    <label class="radio-label">
                        <input type="radio" name="optradio" id="radio_branch" value="branch_wise_fee" checked onchange="switchCriteriaView('branch')">
                        Branch Wise Fee Challan
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="optradio" id="radio_class" value="class_wise_fee" onchange="switchCriteriaView('class')">
                        Class Wise Fee Challan
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="optradio" id="radio_section" value="section_wise_fee" onchange="switchCriteriaView('section')">
                        Section Wise Fee Challan
                    </label>
                </div>

                {{-- Row 1: Branch & Session --}}
                <div class="grid-2-col">
                    <div class="form-group">
                        <label for="brc_id">Branch <span class="req">*</span></label>
                        <select id="brc_id" name="brc_id" class="form-control-cmsc">
                            @foreach ($branchlist as $brc)
                                <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                    {{ $brc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="session_id">Academic Session <span class="req">*</span></label>
                        <select id="session_id" name="session_id" class="form-control-cmsc">
                            @foreach ($sessionlist as $s)
                                <option value="{{ $s->id }}" {{ (string)$current_session === (string)$s->id ? 'selected' : '' }}>
                                    {{ $s->session }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Class & Section (shown when Class or Section Wise selected) --}}
                <div class="grid-2-col" id="classSectionRow" style="display: none;">
                    <div class="form-group" id="classCol">
                        <label for="class_id">Class <span class="req">*</span></label>
                        <select id="class_id" name="class_id" class="form-control-cmsc" onchange="loadSectionsForClass(this.value)">
                            <option value="">Select</option>
                            @foreach ($classlist as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->class }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="sectionCol" style="display: none;">
                        <label for="section_id">Section <span class="req">*</span></label>
                        <select id="section_id" name="section_id" class="form-control-cmsc">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>

                {{-- Dates Row --}}
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

                {{-- Fee Month Row --}}
                <div class="grid-2-col">
                    <div class="form-group">
                        <label for="fees_month">Fee Month <span class="req">*</span></label>
                        <input type="date" id="fees_month" name="fees_month" class="form-control-cmsc" value="{{ $fees_month ?: date('Y-m-d') }}" required>
                    </div>
                    <div></div>
                </div>
            </div>

            <div class="box-footer">
                <div>
                    <button type="button" class="btn-revert" onclick="submitRevert()">
                        <i class="fa fa-undo"></i> Revert
                    </button>
                </div>

                <div style="display: flex; align-items: center; gap: 15px;">
                    <label style="font-weight: normal; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                        <input type="checkbox" name="frequency[]" value="Monthly" checked> Monthly Fee
                    </label>
                    <label style="font-weight: normal; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                        <input type="checkbox" name="frequency[]" value="Yearly"> Yearly Fee
                    </label>
                    <label style="font-weight: normal; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                        <input type="checkbox" name="notification" value="1" checked> Notification
                    </label>
                    <button type="submit" name="search" value="search_filter_branch" id="btnSubmitSearch" class="btn-cmsc-primary">
                        <i class="fa fa-address-card"></i> Generate Fee Voucher
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Results Table if available --}}
    @if (!empty($resultlist))
        <div class="box">
            <div class="box-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="box-title">Generated Fee Vouchers ({{ count($resultlist) }} Students)</h3>
                <button type="button" class="btn-print-empty" onclick="printSelectedVouchers()">
                    <i class="fa fa-print"></i> Print All Vouchers
                </button>
            </div>
            <div class="box-body" style="overflow-x: auto;">
                <table class="table-results">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" checked onclick="toggleAllStudents(this)"></th>
                            <th>Admit No</th>
                            <th>Class</th>
                            <th>Student Name</th>
                            <th>Father Name</th>
                            <th>Father Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultlist as $std)
                            <tr>
                                <td style="text-align: center;"><input type="checkbox" class="student-cb" checked value="{{ $std->id }}"></td>
                                <td>{{ $std->admission_no }}</td>
                                <td>{{ $std->class }} {{ $std->section ? '(' . $std->section . ')' : '' }}</td>
                                <td>{{ $std->firstname }} {{ $std->lastname }}</td>
                                <td>{{ $std->father_name }}</td>
                                <td>{{ $std->father_phone }}</td>
                                <td>
                                    <a href="{{ url('admin/account/studentfee/printfeevoucher?student_id=' . $std->id . '&brc_id=' . $brc_id . '&issue_date=' . $issue_date . '&due_date=' . $due_date . '&fees_month=' . $fees_month) }}" target="_blank" class="btn btn-xs btn-default" style="border: 1px solid #ccc; padding: 2px 6px;">
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

@push('scripts')
<script>
    function printSelectedVouchers() {
        var cbs = document.querySelectorAll('.student-cb:checked');
        var ids = [];
        cbs.forEach(function(cb) { ids.push(cb.value); });
        var issueDate = document.getElementById('issue_date').value;
        var dueDate = document.getElementById('due_date').value;
        var feesMonth = document.getElementById('fees_month').value;
        var brcId = document.getElementById('brc_id').value;

        var url = "{{ url('admin/account/studentfee/printfeevoucher') }}?brc_id=" + brcId + "&issue_date=" + issueDate + "&due_date=" + dueDate + "&fees_month=" + feesMonth;
        if (ids.length > 0) {
            ids.forEach(function(id) { url += "&student_id[]=" + id; });
        }
        window.open(url, '_blank');
    }
    function switchCriteriaView(type) {
        var csRow = document.getElementById('classSectionRow');
        var secCol = document.getElementById('sectionCol');
        var btnSearch = document.getElementById('btnSubmitSearch');

        if (type === 'branch') {
            csRow.style.display = 'none';
            secCol.style.display = 'none';
            btnSearch.value = 'search_filter_branch';
        } else if (type === 'class') {
            csRow.style.display = 'grid';
            secCol.style.display = 'none';
            btnSearch.value = 'search_filter_class';
        } else if (type === 'section') {
            csRow.style.display = 'grid';
            secCol.style.display = 'block';
            btnSearch.value = 'search_filter_section';
        }
    }

    function loadSectionsForClass(classId) {
        var secSelect = document.getElementById('section_id');
        secSelect.innerHTML = '<option value="">Select</option>';
        if (!classId) return;

        fetch("{{ url('admin/account/studentfee/get-sections') }}/" + classId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                data.forEach(function(s) {
                    var opt = document.createElement('option');
                    opt.value = s.section_id;
                    opt.text = s.section;
                    secSelect.appendChild(opt);
                });
            });
    }

    function submitRevert() {
        if (!confirm('Are you sure you want to revert uncollected fee vouchers for this month?')) {
            return;
        }

        var form = document.getElementById('feevoucherForm');
        var origAction = form.action;
        form.action = "{{ url('admin/account/studentfee/revertfeevoucher') }}";
        form.submit();
    }

    function toggleAllStudents(master) {
        var cbs = document.querySelectorAll('.student-cb');
        cbs.forEach(function(cb) { cb.checked = master.checked; });
    }
</script>
@endpush
@endsection