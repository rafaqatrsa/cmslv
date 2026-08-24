@extends('admin.layouts.app')

@section('title', 'Custom Fee Voucher')

@push('styles')
<style>
    .customfeevoucher-page-container {
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
        justify-content: flex-end;
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

    .grid-4-col {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    @media (max-width: 991px) {
        .grid-4-col {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 576px) {
        .grid-4-col {
            grid-template-columns: 1fr;
        }
    }

    .btn-cmsc-primary {
        background-color: #1e3a8a;
        color: #ffffff;
        border: 1px solid #1e3a8a;
        padding: 7px 18px;
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
<div class="customfeevoucher-page-container">
    <h2 class="main-box-title">Custom Fee Voucher</h2>

    <div class="box">
        <form id="customfeevoucherForm" action="{{ url('admin/account/studentfee/customfeevoucher/' . $brc_id) }}" method="POST">
            @csrf
            <div class="box-body">
                {{-- Row 1: Branch | Class | Section | Fee Type --}}
                <div class="grid-4-col">
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
                        <label for="class_id">Class</label>
                        <select id="class_id" name="class_id" class="form-control-cmsc" onchange="loadSectionsForClass(this.value, '')">
                            <option value="">Select</option>
                            @foreach ($classlist as $cls)
                                <option value="{{ $cls->id }}" {{ (string)old('class_id', $class_id) === (string)$cls->id ? 'selected' : '' }}>
                                    {{ $cls->class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="section_id">Section</label>
                        <select id="section_id" name="section_id" class="form-control-cmsc">
                            <option value="">Select</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="feetype_id">Fee Type <span class="req">*</span></label>
                        <select id="feetype_id" name="feetype_id[]" class="form-control-cmsc">
                            <option value="">Select Choose</option>
                            @foreach ($feetypeList as $ft)
                                <option value="{{ $ft->id }}" {{ in_array($ft->id, (array)$selected_feetypes) ? 'selected' : '' }}>
                                    {{ $ft->type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Row 2: Issue Date | Due Date | Search Type --}}
                <div class="grid-4-col">
                    <div class="form-group">
                        <label for="issue_date">Issue Date <span class="req">*</span></label>
                        <input type="text" id="issue_date" name="issue_date" class="form-control-cmsc" value="{{ old('issue_date', $issue_date) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="due_date">Due Date <span class="req">*</span></label>
                        <input type="text" id="due_date" name="due_date" class="form-control-cmsc" value="{{ old('due_date', $due_date) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="search_type">Search Type <span class="req">*</span></label>
                        <select id="search_type" name="search_type" class="form-control-cmsc" onchange="togglePeriodDates(this.value)">
                            <option value="this_month" {{ $search_type === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="period" {{ $search_type === 'period' ? 'selected' : '' }}>Period</option>
                        </select>
                    </div>

                    <div class="form-group" id="periodCol" style="{{ $search_type === 'period' ? '' : 'display: none;' }}">
                        <label for="end_date">End Date</label>
                        <input type="text" id="end_date" name="end_date" class="form-control-cmsc" value="{{ old('end_date', $end_date) }}">
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" name="search" value="search_filter" class="btn-cmsc-primary">
                    <i class="fa fa-address-card"></i> Generate Fee Voucher
                </button>
            </div>
        </form>
    </div>

    {{-- Results Table after Generation --}}
    @if (isset($resultlist) && count($resultlist) === 0)
        <div class="box" style="padding: 15px; background: #fff; border: 1px solid #d2d6de; margin-top: 15px;">
            <div style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 10px 15px; border-radius: 4px;">
                <i class="fa fa-info-circle"></i> No student records found for the selected criteria.
            </div>
        </div>
    @endif

    @if (!empty($resultlist) && count($resultlist) > 0)
        <div class="box" style="margin-top: 20px;">
            <div style="background-color: #f0fdf4; color: #166534; border-bottom: 1px solid #bbf7d0; padding: 10px 15px; font-weight: 600;">
                <i class="fa fa-check-circle"></i> Fee Vouchers Generated Successfully for {{ count($resultlist) }} Students!
            </div>
            <div class="box-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="box-title">Generated Fee Vouchers ({{ count($resultlist) }} Students)</h3>
                @php
                    $stdIds = $resultlist->pluck('id')->toArray();
                    $printQuery = http_build_query([
                        'student_id' => $stdIds,
                        'brc_id' => $brc_id,
                        'issue_date' => $issue_date,
                        'due_date' => $due_date,
                    ]);
                @endphp
                <a href="{{ url('admin/account/studentfee/printfeevoucher?' . $printQuery) }}" target="_blank" class="btn-cmsc-primary">
                    <i class="fa fa-print"></i> Print All Vouchers
                </a>
            </div>
            <div class="box-body" style="overflow-x: auto;">
                <table class="table-results">
                    <thead>
                        <tr>
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
                                <td>{{ $std->admission_no }}</td>
                                <td>{{ $std->class }} {{ $std->section ? '(' . $std->section . ')' : '' }}</td>
                                <td>{{ $std->firstname }} {{ $std->lastname }}</td>
                                <td>{{ $std->father_name }}</td>
                                <td>{{ $std->father_phone }}</td>
                                <td>
                                    <a href="{{ url('admin/account/studentfee/printfeevoucher?student_id=' . $std->id . '&brc_id=' . $brc_id . '&issue_date=' . $issue_date . '&due_date=' . $due_date) }}" target="_blank" class="btn btn-xs btn-default" style="border: 1px solid #ccc; padding: 2px 6px; text-decoration: none;">
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
    function changeBranch(brcId) {
        if (brcId) {
            window.location.href = "{{ url('admin/account/studentfee/customfeevoucher') }}/" + brcId;
        }
    }

    function loadSectionsForClass(classId, selectedSectionId) {
        var secSelect = document.getElementById('section_id');
        secSelect.innerHTML = '<option value="">Select</option>';
        if (!classId) return;

        var url = "{{ url('admin/account/studentfee/get-sections') }}/" + classId;
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (Array.isArray(data)) {
                    data.forEach(function(s) {
                        var opt = document.createElement('option');
                        opt.value = s.section_id || s.id;
                        opt.text = s.section || s.name;
                        if (selectedSectionId && String(opt.value) === String(selectedSectionId)) {
                            opt.selected = true;
                        }
                        secSelect.appendChild(opt);
                    });
                }
            })
            .catch(function(err) {
                console.error('Error loading sections:', err);
            });
    }

    function togglePeriodDates(val) {
        var periodCol = document.getElementById('periodCol');
        if (val === 'period') {
            periodCol.style.display = 'block';
        } else {
            periodCol.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var classSelect = document.getElementById('class_id');
        if (classSelect && classSelect.value) {
            loadSectionsForClass(classSelect.value, "{{ $section_id }}");
        }
    });
</script>
@endpush
@endsection