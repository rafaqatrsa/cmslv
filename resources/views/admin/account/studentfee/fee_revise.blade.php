@extends('admin.layouts.app')

@section('title', 'Fee Revise')

@push('styles')
<style>
    .feerevise-container {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #333;
        font-size: 14px;
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
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .box-title {
        display: inline-block;
        font-size: 17px;
        margin: 0;
        line-height: 1;
        font-weight: 500;
        color: #333;
    }

    .box-body {
        padding: 15px;
    }

    .box-footer {
        border-top: 1px solid #f4f4f4;
        padding: 10px 15px;
        background-color: #ffffff;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
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
        background-image: none;
        border: 1px solid #d2d6de;
        border-radius: 4px;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    }

    .form-control-cmsc:focus {
        border-color: #1e3a8a;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 6px rgba(30,58,138,.4);
    }

    .criteria-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    @media (max-width: 991px) {
        .criteria-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .criteria-grid {
            grid-template-columns: 1fr;
        }
    }

    .btn-search-cmsc {
        background-color: #1e3a8a;
        color: #ffffff;
        border: 1px solid #1e3a8a;
        padding: 6px 20px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-search-cmsc:hover {
        background-color: #162c6d;
        border-color: #162c6d;
    }

    .btn-save-cmsc {
        background-color: #1e3a8a;
        color: #ffffff;
        border: 1px solid #1e3a8a;
        padding: 7px 22px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-save-cmsc:hover {
        background-color: #162c6d;
        border-color: #162c6d;
    }

    .radio-inline-group {
        display: flex;
        align-items: center;
        gap: 15px;
        height: 34px;
    }

    .radio-inline-group label {
        font-weight: normal;
        margin-bottom: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .cmsc-table-wrap {
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
    }

    .cmsc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .cmsc-table thead th {
        background-color: #1e3a8a;
        color: #ffffff;
        font-weight: 600;
        padding: 10px 12px;
        border: 1px solid #2d4fa8;
        white-space: nowrap;
    }

    .cmsc-table tbody td {
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    .cmsc-table tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }

    .cmsc-table tbody tr:hover {
        background-color: #f0f4ff;
    }

    #ajaxToast {
        position: fixed;
        bottom: 25px;
        right: 25px;
        background: #1e3a8a;
        color: #fff;
        padding: 10px 20px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        display: none;
        z-index: 9999;
    }
</style>
@endpush

@section('content')
<div class="feerevise-container">
    {{-- Criteria Card --}}
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Select Criteria</h3>
        </div>

        <form id="criteriaForm" action="{{ url('admin/account/studentfee/feerevise/' . $brc_id) }}" method="POST">
            @csrf
            <div class="box-body">
                <div class="criteria-grid">
                    {{-- Branch --}}
                    <div class="form-group">
                        <label for="brc_id">Branch <span class="req">*</span></label>
                        <select id="brc_id" name="brc_id" class="form-control-cmsc" onchange="getBranchByID(this.value)">
                            <option value="">Select</option>
                            @foreach ($branchlist as $brc)
                                <option value="{{ $brc->id }}" {{ (string)old('brc_id', $brc_id) === (string)$brc->id ? 'selected' : '' }}>
                                    {{ $brc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Class --}}
                    <div class="form-group">
                        <label for="class_id">Class</label>
                        <select id="class_id" name="class_id" class="form-control-cmsc" onchange="loadSections(this.value)">
                            <option value="">Select</option>
                            @foreach ($classlist as $class)
                                <option value="{{ $class->id }}" {{ (string)old('class_id', $class_post) === (string)$class->id ? 'selected' : '' }}>
                                    {{ $class->class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Section --}}
                    <div class="form-group">
                        <label for="section_id">Section</label>
                        <select id="section_id" name="section_id" class="form-control-cmsc">
                            <option value="">Select</option>
                        </select>
                    </div>

                    {{-- Fees Type --}}
                    <div class="form-group">
                        <label for="due_id">Fees Type <span class="req">*</span></label>
                        <select id="due_id" name="due_id" class="form-control-cmsc" required>
                            <option value="">Select</option>
                            @foreach ($feetypeList as $feetype)
                                <option value="{{ $feetype->id }}" {{ (string)old('due_id', $due_id) === (string)$feetype->id ? 'selected' : '' }}>
                                    {{ $feetype->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Row 2: Fees Management Options --}}
                <div class="criteria-grid" style="margin-top: 10px;">
                    {{-- Fees (Manage Type) --}}
                    <div class="form-group">
                        <label for="fees_manage">Fees <span class="req">*</span></label>
                        <select id="fees_manage" name="fees_manage" class="form-control-cmsc" onchange="handleFeesManageChange(this.value)" required>
                            <option value="">Select</option>
                            <option value="1" {{ (string)old('fees_manage', $feesmanage) === '1' ? 'selected' : '' }}>Increment</option>
                            <option value="2" {{ (string)old('fees_manage', $feesmanage) === '2' ? 'selected' : '' }}>Decrement</option>
                            <option value="3" {{ (string)old('fees_manage', $feesmanage) === '3' ? 'selected' : '' }}>Assign Fee</option>
                        </select>
                    </div>

                    {{-- Increment Type (Radio) --}}
                    <div class="form-group increment-type-group" id="incrementTypeGroup" style="display: {{ (string)$feesmanage === '1' ? 'block' : 'none' }};">
                        <label>Increment By</label>
                        <div class="radio-inline-group">
                            <label>
                                <input type="radio" name="is_increment_type" value="1" {{ (string)$increment_type !== '2' ? 'checked' : '' }} onchange="handleIncrementTypeChange('1')"> Fixed
                            </label>
                            <label>
                                <input type="radio" name="is_increment_type" value="2" {{ (string)$increment_type === '2' ? 'checked' : '' }} onchange="handleIncrementTypeChange('2')"> Percentage %
                            </label>
                        </div>
                    </div>

                    {{-- Amount (Fixed) --}}
                    <div class="form-group" id="incrementAmountGroup" style="display: {{ (string)$feesmanage === '1' && (string)$increment_type !== '2' ? 'block' : 'none' }};">
                        <label for="increment_amount">Amount</label>
                        <input type="number" step="any" min="0" id="increment_amount" name="increment_amount" class="form-control-cmsc" value="{{ old('increment_amount', $increment_amount) }}" placeholder="Enter Amount">
                    </div>

                    {{-- Percentage % (Value) --}}
                    <div class="form-group" id="incrementValueGroup" style="display: {{ (string)$feesmanage === '1' && (string)$increment_type === '2' ? 'block' : 'none' }};">
                        <label for="increment_value">Percentage %</label>
                        <input type="number" step="any" min="0" id="increment_value" name="increment_value" class="form-control-cmsc" value="{{ old('increment_value', $increment_value) }}" placeholder="Enter Percentage">
                    </div>
                </div>
            </div>

            <div class="box-footer" style="text-align: right;">
                <button type="submit" class="btn-search-cmsc">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </form>
    </div>

    {{-- Results Table Card --}}
    @if ($resultlist !== null)
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-list"></i> Fee Revise</h3>
        </div>

        <form id="feeReviseUpdateForm">
            @csrf
            <input type="hidden" name="feesmanage" value="{{ $feesmanage }}">
            <input type="hidden" name="class_post" value="{{ $class_post }}">
            <input type="hidden" name="section_post" value="{{ $section_post }}">

            <div class="box-body">
                <div class="cmsc-table-wrap">
                    <table class="cmsc-table" id="feeReviseTable">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;">
                                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)">
                                </th>
                                <th>Admission No</th>
                                <th>Class</th>
                                <th>Student Name</th>
                                <th>Father Name</th>
                                <th>Date of Birth</th>
                                <th>Gender</th>
                                <th style="text-align: right;">Fee</th>
                                <th style="width: 140px; text-align: right;">
                                    @if ($feesmanage == 1)
                                        Increment:
                                    @elseif ($feesmanage == 2)
                                        Decrement:
                                    @elseif ($feesmanage == 3)
                                        Assign Fee:
                                    @else
                                        Action Amount:
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($resultlist as $student)
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="student-checkbox" name="check[]" value="{{ $student->student_session_id }}" checked>
                                </td>
                                <td>{{ $student->admission_no }}</td>
                                <td>{{ $student->class }} ({{ $student->section }})</td>
                                <td><strong>{{ $student->firstname }} {{ $student->lastname }}</strong></td>
                                <td>{{ $student->father_name }}</td>
                                <td>{{ !empty($student->dob) && $student->dob !== '0000-00-00' ? date('d/m/Y', strtotime($student->dob)) : '' }}</td>
                                <td>{{ $student->gender }}</td>
                                <td style="text-align: right;">{{ number_format((float) $student->current_fee, 0, '.', '') }}</td>
                                <td style="text-align: right;">
                                    <input type="hidden" name="dues_id_{{ $student->student_session_id }}" value="{{ $due_id }}">
                                    @if ($feesmanage == 1)
                                        <input type="number" step="any" name="incrementfee_{{ $student->student_session_id }}" class="form-control-cmsc" style="text-align: right;" value="{{ $student->suggested_fee }}">
                                    @elseif ($feesmanage == 2)
                                        <input type="number" step="any" name="decrementfee_{{ $student->student_session_id }}" class="form-control-cmsc" style="text-align: right;" value="" placeholder="0">
                                    @elseif ($feesmanage == 3)
                                        <input type="number" step="any" name="assignfee_{{ $student->student_session_id }}" class="form-control-cmsc" style="text-align: right;" value="{{ $student->suggested_fee }}">
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="text-align: center; color: #ff0000; padding: 25px;">
                                    No records found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if (count($resultlist) > 0)
            <div class="box-footer" style="text-align: right;">
                <button type="button" id="btnSaveRevise" class="btn-save-cmsc" onclick="submitFeeRevise()">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
            @endif
        </form>
    </div>
    @endif
</div>

{{-- AJAX Toast Notification --}}
<div id="ajaxToast">Fees updated successfully!</div>

@push('scripts')
<script>
    function getBranchByID(val) {
        if (val) {
            window.location.href = "{{ url('admin/account/studentfee/feerevise') }}/" + val;
        }
    }

    function handleFeesManageChange(val) {
        var incGroup = document.getElementById('incrementTypeGroup');
        var incAmtGroup = document.getElementById('incrementAmountGroup');
        var incValGroup = document.getElementById('incrementValueGroup');

        if (val === '1') {
            if (incGroup) incGroup.style.display = 'block';
            var checkedRadio = document.querySelector('input[name="is_increment_type"]:checked');
            var radioVal = checkedRadio ? checkedRadio.value : '1';
            handleIncrementTypeChange(radioVal);
        } else {
            if (incGroup) incGroup.style.display = 'none';
            if (incAmtGroup) incAmtGroup.style.display = 'none';
            if (incValGroup) incValGroup.style.display = 'none';
        }
    }

    function handleIncrementTypeChange(type) {
        var incAmtGroup = document.getElementById('incrementAmountGroup');
        var incValGroup = document.getElementById('incrementValueGroup');
        if (type === '1') {
            if (incAmtGroup) incAmtGroup.style.display = 'block';
            if (incValGroup) incValGroup.style.display = 'none';
        } else {
            if (incAmtGroup) incAmtGroup.style.display = 'none';
            if (incValGroup) incValGroup.style.display = 'block';
        }
    }

    function loadSections(classId, selectedSectionId) {
        var sectionSelect = document.getElementById('section_id');
        if (!sectionSelect) return;
        sectionSelect.innerHTML = '<option value="">Select</option>';
        if (!classId) return;

        fetch("{{ url('admin/account/studentfee/get-sections') }}/" + classId)
            .then(function(res) { return res.json(); })
            .then(function(data) {
                data.forEach(function(item) {
                    var opt = document.createElement('option');
                    opt.value = item.section_id;
                    opt.text = item.section;
                    if (selectedSectionId && String(selectedSectionId) === String(item.section_id)) {
                        opt.selected = true;
                    }
                    sectionSelect.appendChild(opt);
                });
            })
            .catch(function(err) { console.error(err); });
    }

    function toggleSelectAll(masterCheckbox) {
        var checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(function(cb) {
            cb.checked = masterCheckbox.checked;
        });
    }

    function showToast(msg) {
        var toast = document.getElementById('ajaxToast');
        if (!toast) return;
        toast.innerText = msg;
        toast.style.display = 'block';
        setTimeout(function() { toast.style.display = 'none'; }, 2500);
    }

    function submitFeeRevise() {
        var form = document.getElementById('feeReviseUpdateForm');
        if (!form) return;

        var saveBtn = document.getElementById('btnSaveRevise');
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';
        }

        var formData = new FormData(form);

        fetch("{{ url('admin/account/studentfee/feereviseUpdate') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status === 'success') {
                showToast(data.message || 'Fees updated successfully!');
                setTimeout(function() {
                    window.location.reload();
                }, 1200);
            } else {
                var errStr = 'Failed to update fees.';
                if (data.error) {
                    errStr = Object.values(data.error).join('\n');
                }
                alert(errStr);
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fa fa-save"></i> Save';
                }
            }
        })
        .catch(function(err) {
            console.error(err);
            alert('Error updating fee revision.');
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fa fa-save"></i> Save';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var initialClassId = "{{ $class_post }}";
        var initialSectionId = "{{ $section_post }}";
        if (initialClassId) {
            loadSections(initialClassId, initialSectionId);
        }
    });
</script>
@endpush
@endsection