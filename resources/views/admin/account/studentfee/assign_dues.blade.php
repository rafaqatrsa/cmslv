@extends('admin.layouts.app')

@section('title', 'Assign Dues')

@push('styles')
<style>
    .assigndues-container {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #333;
        font-size: 14px;
    }

    .main-box-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }

    .assigndues-grid {
        display: grid;
        grid-template-columns: 3fr 4fr 5fr;
        gap: 15px;
        align-items: start;
    }

    @media (max-width: 991px) {
        .assigndues-grid {
            grid-template-columns: 1fr;
        }
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

    .btn-cmsc-primary {
        background-color: #1e3a8a;
        color: #ffffff;
        border: 1px solid #1e3a8a;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-cmsc-primary:hover {
        background-color: #162c6d;
        border-color: #162c6d;
        color: #ffffff;
    }

    .btn-add-cmsc {
        background-color: #1e3a8a;
        color: #ffffff;
        border: 1px solid #1e3a8a;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-add-cmsc:hover {
        background-color: #162c6d;
    }

    .table-proceed {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-top: 10px;
    }

    .table-proceed th {
        background-color: #f8fafc;
        padding: 8px 10px;
        border: 1px solid #e2e8f0;
        font-weight: 600;
        font-size: 12px;
        text-align: center;
    }

    .table-proceed td {
        padding: 8px 10px;
        border: 1px solid #e2e8f0;
        font-size: 12px;
        vertical-align: middle;
    }

    .dues-row-grid {
        display: grid;
        grid-template-columns: 4fr 4fr 3fr 1fr;
        gap: 8px;
        align-items: end;
        margin-bottom: 10px;
    }

    .btn-remove-row {
        background-color: #dc2626;
        color: #ffffff;
        border: none;
        border-radius: 3px;
        height: 34px;
        width: 34px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .btn-remove-row:hover {
        background-color: #b91c1c;
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
<div class="assigndues-container">
    <h2 class="main-box-title">Assign Dues</h2>

    <div class="assigndues-grid">
        {{-- 1. Left Panel: Select Criteria --}}
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Select Criteria</h3>
            </div>
            <div class="box-body">
                {{-- Branch Wise --}}
                <div class="form-group">
                    <label for="brc_wise">Branch Wise <span class="req">*</span></label>
                    <select id="brc_wise" name="brc_wise" class="form-control-cmsc" onchange="handleBranchWise(this.value)">
                        <option value="">Select Branch</option>
                        @foreach ($branchlist as $brc)
                            <option value="{{ $brc->id }}">{{ $brc->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Classes Wise --}}
                <div class="form-group">
                    <label for="classes_wise">Classes Wise <span class="req">*</span></label>
                    <select id="classes_wise" name="classes_wise" class="form-control-cmsc" onchange="handleClassesWise(this.value)">
                        <option value="">Select Class</option>
                        <option value="all">All Classes</option>
                        @foreach ($classlist as $cls)
                            <option value="{{ $cls->id }}">{{ $cls->class }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Sections Wise --}}
                <div class="form-group">
                    <label for="sections_wise">Sections Wise <span class="req">*</span></label>
                    <select id="sections_wise" name="sections_wise" class="form-control-cmsc" onchange="handleSectionsWise(this.value)">
                        <option value="">Select Section</option>
                        <option value="all">All Sections</option>
                        @foreach ($sectionlist as $sec)
                            <option value="{{ $sec->id }}">{{ $sec->section }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Students Wise --}}
                <div class="form-group">
                    <label>Students Wise <span class="req">*</span></label>
                    <div>
                        <button type="button" id="btnStudentsWise" class="btn-cmsc-primary" onclick="handleStudentsWiseClick()">
                            Students Wise
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Center Panel: Select For Proceed --}}
        <div class="box">
            <div class="box-header">
                <h3 class="box-title" id="proceedTitle">
                    <i class="fa fa-spinner fa-spin" id="proceedSpinner" style="display:none; margin-right: 5px;"></i>
                    <span id="proceedTitleText">Select For Proceed</span>
                </h3>
            </div>
            <div class="box-body" id="proceedBody" style="min-height: 250px;">
                <div id="studentCriteriaSection" style="display: none; margin-bottom: 15px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                        <div>
                            <label style="font-size: 11px; font-weight: 600;">Branch <span class="req">*</span></label>
                            <select id="sw_brc_id" class="form-control-cmsc" style="font-size: 12px; height: 30px;">
                                <option value="">Select</option>
                                @foreach ($branchlist as $brc)
                                    <option value="{{ $brc->id }}">{{ $brc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 600;">Class <span class="req">*</span></label>
                            <select id="sw_class_id" class="form-control-cmsc" style="font-size: 12px; height: 30px;" onchange="loadStudentWiseSections(this.value)">
                                <option value="">Select</option>
                                @foreach ($classlist as $cls)
                                    <option value="{{ $cls->id }}">{{ $cls->class }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 600;">Section <span class="req">*</span></label>
                            <select id="sw_section_id" class="form-control-cmsc" style="font-size: 12px; height: 30px;" onchange="loadStudentsByClassSection()">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 11px; font-weight: 600;">Admission No (comma separated)</label>
                        <input type="text" id="sw_admission_no" class="form-control-cmsc" style="font-size: 12px; height: 30px;" placeholder="e.g. 101, 102" onchange="loadStudentsByAdmitNo(this.value)">
                    </div>
                </div>

                <div id="proceedTableWrapper">
                    {{-- Dynamically populated table --}}
                </div>
            </div>
        </div>

        {{-- 3. Right Panel: Add Dues --}}
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Add Dues</h3>
                <button type="button" class="btn-add-cmsc" onclick="addDuesRow()">
                    <i class="fa fa-plus"></i> Add
                </button>
            </div>

            <form id="addDuesForm">
                @csrf
                <input type="hidden" name="selectproceed" id="hiddenSelectProceed" value="">
                <input type="hidden" name="selec_barch" id="hiddenBranchId" value="">
                <input type="hidden" name="select_brc_id" id="hiddenClassBrcId" value="">
                <input type="hidden" name="sec_select_brc_id" id="hiddenSecBrcId" value="">

                <div class="box-body">
                    <div id="duesRowsContainer">
                        {{-- First Row --}}
                        <div class="dues-row-grid">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="font-size: 12px;">Dues Type <span class="req">*</span></label>
                                <select name="dues_type[]" class="form-control-cmsc dues-type-select" required>
                                    <option value="">Select</option>
                                    @foreach ($feetypeList as $ft)
                                        <option value="{{ $ft->id }}">{{ $ft->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="font-size: 12px;">School Amount(Rs.) <span class="req">*</span></label>
                                <input type="number" step="any" min="0" name="school_amount[]" class="form-control-cmsc" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="font-size: 12px;">Amount(Rs.) <span class="req">*</span></label>
                                <input type="number" step="any" min="0" name="dues_amount[]" class="form-control-cmsc" required>
                            </div>
                            <div></div>
                        </div>
                    </div>

                    {{-- Extra Rows Container --}}
                    <div id="extraDuesRows"></div>

                    <div style="margin-top: 15px;">
                        <div class="form-group">
                            <label for="issue_date">Issue Date <span class="req">*</span></label>
                            <input type="date" id="issue_date" name="issue_date" class="form-control-cmsc" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date <span class="req">*</span></label>
                            <input type="date" id="due_date" name="due_date" class="form-control-cmsc" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="dues_date">Assign Dues Date <span class="req">*</span></label>
                            <input type="date" id="dues_date" name="dues_date" class="form-control-cmsc" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control-cmsc" rows="2" style="height: auto;" placeholder="Enter notes..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div>
                        <label style="font-weight: normal; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="notification" value="1" checked> Notification
                        </label>
                    </div>
                    <button type="button" id="btnSaveDues" class="btn-cmsc-primary" onclick="submitAddDues()">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="ajaxToast">Dues assigned successfully!</div>

@push('scripts')
<script>
    var globalFeeTypes = @json($feetypeList);

    function showSpinner(show) {
        var spinner = document.getElementById('proceedSpinner');
        if (spinner) spinner.style.display = show ? 'inline-block' : 'none';
    }

    function showToast(msg) {
        var toast = document.getElementById('ajaxToast');
        if (!toast) return;
        toast.innerText = msg;
        toast.style.display = 'block';
        setTimeout(function() { toast.style.display = 'none'; }, 2500);
    }

    function handleBranchWise(brcId) {
        if (!brcId) return;
        document.getElementById('studentCriteriaSection').style.display = 'none';
        document.getElementById('hiddenSelectProceed').value = 'branch';
        document.getElementById('hiddenBranchId').value = brcId;
        document.getElementById('proceedTitleText').innerText = 'Select Branch(s) for Proceed';
        showSpinner(true);

        fetch("{{ url('admin/account/studentfee/getStudentByBranch') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ brc_id: brcId })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            showSpinner(false);
            var html = '<table class="table-proceed"><thead><tr>';
            html += '<th style="width:40px;"><input type="checkbox" checked disabled></th>';
            html += '<th style="text-align:left;">Branch Name</th>';
            html += '<th>Strength</th>';
            html += '</tr></thead><tbody><tr>';
            html += '<td style="text-align:center;"><input type="checkbox" name="selec_barch" value="' + data.student.brc_id + '" checked></td>';
            html += '<td>' + data.student.branch_name + '</td>';
            html += '<td style="text-align:center;">' + data.student.total_student + '</td>';
            html += '</tr></tbody></table>';
            document.getElementById('proceedTableWrapper').innerHTML = html;
        })
        .catch(function(e) { showSpinner(false); console.error(e); });
    }

    function handleClassesWise(classId) {
        if (!classId) return;
        document.getElementById('studentCriteriaSection').style.display = 'none';
        document.getElementById('hiddenSelectProceed').value = 'classes';
        var currentBrcId = document.getElementById('brc_wise').value || '{{ $brc_id }}';
        document.getElementById('hiddenClassBrcId').value = currentBrcId;
        document.getElementById('proceedTitleText').innerText = 'Select Class(s) for Proceed';
        showSpinner(true);

        fetch("{{ url('admin/account/studentfee/getClassesByBranch') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ brc_id: currentBrcId, class_id: classId === 'all' ? 0 : classId })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            showSpinner(false);
            if (data.status === 'fail' || !data.student || data.student.length === 0) {
                document.getElementById('proceedTableWrapper').innerHTML = '<div style="color:red; text-align:center; padding:20px;">No records found.</div>';
                return;
            }
            var html = '<table class="table-proceed"><thead><tr>';
            html += '<th style="width:40px;"><input type="checkbox" onchange="toggleProceedChecks(this)"></th>';
            html += '<th style="text-align:left;">Class</th>';
            html += '<th>Strength</th>';
            html += '</tr></thead><tbody>';
            data.student.forEach(function(item) {
                html += '<tr>';
                html += '<td style="text-align:center;"><input type="checkbox" class="proceed-cb" name="class_id[]" value="' + item.id + '" checked></td>';
                html += '<td>' + item.classname + '</td>';
                html += '<td style="text-align:center;">' + (item.classesstudent ? item.classesstudent[0] : 0) + '</td>';
                html += '</tr>';
            });
            html += '</tbody></table>';
            document.getElementById('proceedTableWrapper').innerHTML = html;
        })
        .catch(function(e) { showSpinner(false); console.error(e); });
    }

    function handleSectionsWise(sectionId) {
        if (!sectionId) return;
        document.getElementById('studentCriteriaSection').style.display = 'none';
        document.getElementById('hiddenSelectProceed').value = 'sections';
        var currentBrcId = document.getElementById('brc_wise').value || '{{ $brc_id }}';
        document.getElementById('hiddenSecBrcId').value = currentBrcId;
        document.getElementById('proceedTitleText').innerText = 'Select Section(s) for Proceed';
        showSpinner(true);

        fetch("{{ url('admin/account/studentfee/getClassesSectionsByBranch') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ brc_id: currentBrcId, section_id: sectionId === 'all' ? 0 : sectionId })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            showSpinner(false);
            if (data.status === 'fail' || !data.student || data.student.length === 0) {
                document.getElementById('proceedTableWrapper').innerHTML = '<div style="color:red; text-align:center; padding:20px;">No records found.</div>';
                return;
            }
            var html = '<table class="table-proceed"><thead><tr>';
            html += '<th style="width:40px;"><input type="checkbox" onchange="toggleProceedChecks(this)"></th>';
            html += '<th style="text-align:left;">Class - Section</th>';
            html += '<th>Strength</th>';
            html += '</tr></thead><tbody>';
            data.student.forEach(function(item) {
                html += '<tr>';
                html += '<td style="text-align:center;"><input type="checkbox" class="proceed-cb" name="class_id[]" value="' + item.class_id + '" checked>';
                html += '<input type="hidden" name="section_id[]" value="' + item.section_id + '"></td>';
                html += '<td>' + item.classname + ' - ' + item.sectionname + '</td>';
                html += '<td style="text-align:center;">' + (item.totalstudent ? item.totalstudent[0] : 0) + '</td>';
                html += '</tr>';
            });
            html += '</tbody></table>';
            document.getElementById('proceedTableWrapper').innerHTML = html;
        })
        .catch(function(e) { showSpinner(false); console.error(e); });
    }

    function handleStudentsWiseClick() {
        document.getElementById('studentCriteriaSection').style.display = 'block';
        document.getElementById('proceedTitleText').innerText = 'Select Student(s) for Proceed';
        document.getElementById('hiddenSelectProceed').value = 'students';
        document.getElementById('proceedTableWrapper').innerHTML = '';
    }

    function loadStudentWiseSections(classId) {
        var secSelect = document.getElementById('sw_section_id');
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

    function loadStudentsByClassSection() {
        var brcId = document.getElementById('sw_brc_id').value;
        var classId = document.getElementById('sw_class_id').value;
        var secId = document.getElementById('sw_section_id').value;
        if (!brcId || !classId || !secId) return;

        showSpinner(true);
        fetch("{{ url('admin/account/studentfee/getStudentClassSectionsByBranch') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ brc_id: brcId, class_id: classId, section_id: secId })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            showSpinner(false);
            renderStudentsTable(data.student);
        })
        .catch(function(e) { showSpinner(false); console.error(e); });
    }

    function loadStudentsByAdmitNo(admitNo) {
        var brcId = document.getElementById('sw_brc_id').value;
        if (!brcId || !admitNo) return;

        showSpinner(true);
        fetch("{{ url('admin/account/studentfee/getstdByBrcIDByAdmitNo') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ brc_id: brcId, admit_no: admitNo })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            showSpinner(false);
            renderStudentsTable(data.student);
        })
        .catch(function(e) { showSpinner(false); console.error(e); });
    }

    function renderStudentsTable(students) {
        if (!students || students.length === 0) {
            document.getElementById('proceedTableWrapper').innerHTML = '<div style="color:red; text-align:center; padding:20px;">No students found.</div>';
            return;
        }
        var html = '<table class="table-proceed"><thead><tr>';
        html += '<th style="width:40px;"><input type="checkbox" onchange="toggleProceedChecks(this)"></th>';
        html += '<th style="text-align:left;">Admit No</th>';
        html += '<th style="text-align:left;">Student Name</th>';
        html += '<th style="text-align:left;">Father Name</th>';
        html += '</tr></thead><tbody>';
        students.forEach(function(s) {
            html += '<tr>';
            html += '<td style="text-align:center;"><input type="checkbox" class="proceed-cb" name="students_session_id[]" value="' + s.student_session_id + '" checked></td>';
            html += '<td>' + s.admission_no + '</td>';
            html += '<td>' + s.firstname + ' ' + (s.lastname || '') + '</td>';
            html += '<td>' + (s.father_name || '') + '</td>';
            html += '</tr>';
        });
        html += '</tbody></table>';
        document.getElementById('proceedTableWrapper').innerHTML = html;
    }

    function toggleProceedChecks(master) {
        var cbs = document.querySelectorAll('.proceed-cb');
        cbs.forEach(function(cb) { cb.checked = master.checked; });
    }

    function addDuesRow() {
        var container = document.getElementById('extraDuesRows');
        var row = document.createElement('div');
        row.className = 'dues-row-grid';

        var optHtml = '<option value="">Select</option>';
        globalFeeTypes.forEach(function(ft) {
            optHtml += '<option value="' + ft.id + '">' + ft.type + '</option>';
        });

        row.innerHTML = '<div><select name="dues_type[]" class="form-control-cmsc dues-type-select" required>' + optHtml + '</select></div>' +
            '<div><input type="number" step="any" min="0" name="school_amount[]" class="form-control-cmsc" placeholder="School Amt" required></div>' +
            '<div><input type="number" step="any" min="0" name="dues_amount[]" class="form-control-cmsc" placeholder="Amount" required></div>' +
            '<div><button type="button" class="btn-remove-row" onclick="this.closest(\'.dues-row-grid\').remove()"><i class="fa fa-trash"></i></button></div>';

        container.appendChild(row);
    }

    function submitAddDues() {
        var form = document.getElementById('addDuesForm');
        var btn = document.getElementById('btnSaveDues');

        var proceedCbs = document.querySelectorAll('.proceed-cb:checked');
        var selProceed = document.getElementById('hiddenSelectProceed').value;

        if (!selProceed) {
            alert('Please select a criteria from the left panel first.');
            return;
        }

        btn.disabled = true;
        btn.innerText = 'Saving...';

        var formData = new FormData(form);

        // Also append all selected checkboxes from proceed table
        var allProceedInputs = document.querySelectorAll('#proceedTableWrapper input:checked');
        allProceedInputs.forEach(function(inp) {
            formData.append(inp.name, inp.value);
        });

        fetch("{{ url('admin/account/studentfee/addDues') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.status === 'success') {
                showToast(data.message || 'Dues assigned successfully!');
                setTimeout(function() { window.location.reload(); }, 1200);
            } else {
                var err = data.error ? Object.values(data.error).join('\n') : 'Error saving dues.';
                alert(err);
                btn.disabled = false;
                btn.innerText = 'Save';
            }
        })
        .catch(function(e) {
            console.error(e);
            alert('Error assigning dues.');
            btn.disabled = false;
            btn.innerText = 'Save';
        });
    }
</script>
@endpush
@endsection