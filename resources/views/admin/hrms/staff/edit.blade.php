@extends('admin.layouts.app')

@section('title', 'Edit Staff')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style-main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/themes/default/ss-main.css') }}">
    <style>
        .legacy-staff-edit .box {
            border: 1px solid #d7dfe3;
            border-top: 0;
            box-shadow: none;
        }

        .legacy-staff-edit .box-header {
            border-bottom: 1px solid #e3e7eb;
        }

        .legacy-staff-edit .pagetitleh2 {
            margin: 0;
            padding: 10px 14px;
            border-bottom: 1px solid #e3e7eb;
            background: #f5f5f5;
            font-size: 14px;
            color: #111;
        }

        .legacy-staff-edit .around10 {
            padding: 14px;
        }

        .legacy-staff-edit label,
        .legacy-staff-edit td,
        .legacy-staff-edit th {
            font-size: 12px;
            color: #333;
        }

        .legacy-staff-edit .form-control {
            height: 34px;
            font-size: 12px;
            border-radius: 0;
        }

        .legacy-staff-edit textarea.form-control {
            height: auto;
        }

        .legacy-staff-edit .table > tbody > tr > td,
        .legacy-staff-edit .table > thead > tr > td,
        .legacy-staff-edit .table > tbody > tr > th,
        .legacy-staff-edit .table > thead > tr > th {
            vertical-align: middle;
        }

        .legacy-staff-edit .btn-xs {
            padding: 2px 7px;
            font-size: 11px;
        }

        .legacy-staff-edit .req {
            color: #f00;
        }

        .legacy-staff-edit .text-danger {
            display: block;
            margin-top: 4px;
            font-size: 11px;
        }

        .legacy-staff-edit .label-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .legacy-staff-edit .option-add-btn {
            border: 1px solid #264796;
            background: #264796;
            color: #fff;
            font-size: 11px;
            line-height: 1;
            padding: 2px 6px;
            border-radius: 2px;
        }

        .legacy-staff-edit .net-salary,
        .legacy-staff-edit .total-amount,
        .legacy-staff-edit .total-dedamount {
            font-weight: 700;
        }
    </style>
@endpush

@section('content')
    <div class="legacy-staff-edit">
        <script src="{{ asset('assets/dist/js/webcam.min.js') }}"></script>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Edit Staff</h3>
                        <div class="box-tools pull-right">
                            <a class="btn btn-sm btn-primary" href="{{ route('admin.hrms.staff.index', ['brc_id' => $selectedBranchId], false) }}"><i class="fa fa-plus"></i> Import Staff</a>
                        </div>
                    </div>

                    <form id="form1" action="{{ route('admin.hrms.staff.update', $staff->id, false) }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="box-body">
                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Basic Information</h4>
                                <div class="around10">
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Branch</label><small class="req"> *</small>
                                                <select id="brc_id" name="brc_id" class="form-control selectval brc_id">
                                                    <option value="">Select</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}" @selected((int) old('brc_id', $staff->brc_id) === (int) $branch->id)>{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">{{ $errors->first('brc_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Staff ID</label><small class="req"> *</small>
                                                <input autofocus id="employee_id" name="employee_id" type="text" class="form-control" value="{{ old('employee_id', $staff->employee_id) }}" readonly>
                                                <span class="text-danger">{{ $errors->first('employee_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Category</label><small class="req"> *</small>
                                                <select id="category" name="category" class="form-control selectval">
                                                    <option value="">Select</option>
                                                    <option value="1" @selected((string) old('category', $staff->category) === '1')>Administration</option>
                                                    <option value="2" @selected((string) old('category', $staff->category) === '2')>Teaching</option>
                                                    <option value="3" @selected((string) old('category', $staff->category) === '3')>Allied</option>
                                                </select>
                                                <span class="text-danger">{{ $errors->first('category') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="label-tools">
                                                    <label>Role <small class="req">*</small></label>
                                                    <button type="button" class="option-add-btn" data-option-target="role" data-option-type="role">+</button>
                                                </div>
                                                <select id="role" name="role" class="form-control selectval">
                                                    <option value="">Select</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role['id'] }}" @selected((string) old('role', old('role_id', $staff->role_id)) === (string) $role['id'])>{{ $role['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">{{ $errors->first('role_id') ?: $errors->first('role') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><div class="label-tools"><label>Designation</label><button type="button" class="option-add-btn" data-option-target="designation" data-option-type="designation">+</button></div><select id="designation" name="designation" class="form-control selectval"><option value="">Select</option>@foreach ($designations as $designation)<option value="{{ $designation->id }}" @selected((string) old('designation', $staff->designation) === (string) $designation->id)>{{ $designation->name }}</option>@endforeach</select><span class="text-danger">{{ $errors->first('designation') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><div class="label-tools"><label>Department</label><button type="button" class="option-add-btn" data-option-target="department" data-option-type="department">+</button></div><select id="department" name="department" class="form-control selectval"><option value="">Select</option>@foreach ($departments as $department)<option value="{{ $department->id }}" @selected((string) old('department', $staff->department) === (string) $department->id)>{{ $department->name }}</option>@endforeach</select><span class="text-danger">{{ $errors->first('department') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>First Name</label><small class="req"> *</small><input id="name" name="name" type="text" class="form-control" value="{{ old('name', $staff->name) }}"><span class="text-danger">{{ $errors->first('name') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Last Name</label><input id="surname" name="surname" type="text" class="form-control" value="{{ old('surname', $staff->surname) }}"><span class="text-danger">{{ $errors->first('surname') }}</span></div></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><label>Father Name</label><input id="father_name" name="father_name" type="text" class="form-control" value="{{ old('father_name', $staff->father_name) }}"><span class="text-danger">{{ $errors->first('father_name') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>CNIC No</label><small class="req"> *</small><input id="cnic" name="cnic" type="text" class="form-control" value="{{ old('cnic', $staff->cnic) }}"><span class="text-danger">{{ $errors->first('cnic') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Email</label><sup>( Username For Login )</sup><small class="req"> *</small><input id="email" name="username" type="text" class="form-control" value="{{ old('username', old('email', $staff->email)) }}"><span class="text-danger">{{ $errors->first('email') ?: $errors->first('username') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Gender</label><small class="req"> *</small><select class="form-control" name="gender"><option value="">Select</option>@foreach ($genderOptions as $key => $value)<option value="{{ $key }}" @selected(old('gender', $staff->gender) === $key)>{{ $value }}</option>@endforeach</select><span class="text-danger">{{ $errors->first('gender') }}</span></div></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><label>Date of Birth</label><small class="req"> *</small><input id="dob" name="dob" type="date" class="form-control date" value="{{ old('dob', optional($staff->dob)->format('Y-m-d')) }}"><span class="text-danger">{{ $errors->first('dob') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Date of Joining</label><input id="date_of_joining" name="date_of_joining" type="date" class="form-control date" value="{{ old('date_of_joining', optional($staff->date_of_joining)->format('Y-m-d')) }}"><span class="text-danger">{{ $errors->first('date_of_joining') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Phone</label><input id="mobileno" name="contactno" type="text" class="form-control" value="{{ old('contactno', old('contact_no', $staff->contact_no)) }}"><span class="text-danger">{{ $errors->first('contact_no') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Emergency Contact Number</label><small class="req"> *</small><input id="emergency_no" name="emergency_no" type="text" class="form-control" value="{{ old('emergency_no', old('emergency_contact_no', $staff->emergency_contact_no)) }}"><span class="text-danger">{{ $errors->first('emergency_contact_no') }}</span></div></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><label>Whatsapp No</label><small class="req"> *</small><input id="whatsapp_no" name="whatsapp_no" type="text" class="form-control" value="{{ old('whatsapp_no', $staff->whatsapp_no) }}"><span class="text-danger">{{ $errors->first('whatsapp_no') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Marital Status</label><select class="form-control" name="marital_status"><option value="">Select</option>@foreach ($maritalStatuses as $status)<option value="{{ $status }}" @selected(old('marital_status', $staff->marital_status) === $status)>{{ $status }}</option>@endforeach</select><span class="text-danger">{{ $errors->first('marital_status') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Current Address</label><textarea name="address" class="form-control">{{ old('address', old('local_address', $staff->local_address)) }}</textarea></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Permanent Address</label><textarea name="permanent_address" class="form-control">{{ old('permanent_address', $staff->permanent_address) }}</textarea></div></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><label>Photo</label><div><input class="filestyle form-control" type="file" name="file" id="file"></div><span class="text-danger">{{ $errors->first('file') }}</span></div></div>
                                        <div class="col-md-3" style="padding:0 0 0 5px;">
                                            <div class="form-group">
                                                <label class="col-md-12">Photo Webcam</label>
                                                <button type="button" style="margin: 5px 0 0 11px;" class="btn btn-primary btn-xs showcam">Access Webcam</button>
                                                <div id="my_photo_booth">
                                                    <div id="my_camera" style="display: none; margin-top: 8px;"></div>
                                                    <div id="pre_take_buttons" style="display: none;"><button type="button" class="btn btn-primary btn-xs" onclick="preview_snapshot()">Take Photo</button></div>
                                                    <div id="post_take_buttons" style="display:none"><button type="button" class="btn btn-primary btn-xs" onclick="cancel_preview()">Take Another</button><button type="button" style="margin-top: 5px;" class="btn btn-primary btn-xs" onclick="save_photo()">Save Photo</button></div>
                                                </div>
                                                <input type="hidden" name="image" class="image-tag">
                                                <div id="results" style="display:none;margin-top: 8px;"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3"><div class="form-group"><label>Status</label><select name="is_active" class="form-control"><option value="1" @selected((int) old('is_active', $staff->is_active) === 1)>Active</option><option value="0" @selected((int) old('is_active', $staff->is_active) === 0)>Inactive</option></select><span class="text-danger">{{ $errors->first('is_active') }}</span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Note</label><textarea name="note" class="form-control">{{ old('note', $staff->note) }}</textarea></div></div>
                                    </div>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Academic Information</h4>
                                <div class="around10">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead><tr><td>University/Board</td><td>From</td><td>To</td><td>Degree/Certificate</td><td>Maximum Marks</td><td>Obtained Marks</td><td>Division/Grade</td><td><button id="btnAddedu" class="btn btn-primary btn-xs" type="button"><i class="fa fa-plus"></i></button></td></tr></thead>
                                        <tbody class="eduwarp">
                                            @forelse ($academicRows as $row)
                                                <tr class="remove_field_warp">
                                                    <td><select name="eduinst[]" class="form-control selectval"><option value="">Select</option>@foreach ($universityBoards as $board)<option value="{{ $board->id }}" @selected((int) $row['ints_id'] === (int) $board->id)>{{ $board->name }}</option>@endforeach</select></td>
                                                    <td><select name="edufrom[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}" @selected((int) $row['from'] === (int) $year->id)>{{ $year->name }}</option>@endforeach</select></td>
                                                    <td><select name="eduto[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}" @selected((int) $row['to'] === (int) $year->id)>{{ $year->name }}</option>@endforeach</select></td>
                                                    <td><select name="edudegree[]" class="form-control selectval"><option value="">Select</option>@foreach ($degreeCertificates as $degree)<option value="{{ $degree->id }}" @selected((int) $row['degree_id'] === (int) $degree->id)>{{ $degree->name }}</option>@endforeach</select></td>
                                                    <td><input name="edumaxmark[]" type="text" class="form-control" value="{{ $row['maxmarks'] }}"></td>
                                                    <td><input name="eduobtmark[]" type="text" class="form-control" value="{{ $row['obtmarks'] }}"></td>
                                                    <td><input name="edugrd[]" type="text" class="form-control" value="{{ $row['grade'] }}"></td>
                                                    <td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @empty
                                                <tr><td><select name="eduinst[]" class="form-control selectval"><option value="">Select</option>@foreach ($universityBoards as $board)<option value="{{ $board->id }}">{{ $board->name }}</option>@endforeach</select></td><td><select name="edufrom[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}">{{ $year->name }}</option>@endforeach</select></td><td><select name="eduto[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}">{{ $year->name }}</option>@endforeach</select></td><td><select name="edudegree[]" class="form-control selectval"><option value="">Select</option>@foreach ($degreeCertificates as $degree)<option value="{{ $degree->id }}">{{ $degree->name }}</option>@endforeach</select></td><td><input name="edumaxmark[]" type="text" class="form-control" value=""></td><td><input name="eduobtmark[]" type="text" class="form-control" value=""></td><td><input name="edugrd[]" type="text" class="form-control" value=""></td><td></td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Professional trainings/certifications/others, (If any)</h4>
                                <div class="around10">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead><tr><td>Institute</td><td>Type of training</td><td>From</td><td>To</td><td>Obtained Marks</td><td>Division/Grade</td><td><button id="btnAddcer" class="btn btn-primary btn-xs" type="button"><i class="fa fa-plus"></i></button></td></tr></thead>
                                        <tbody class="cerwarp">
                                            @forelse ($certificationRows as $row)
                                                <tr class="remove_field_warp">
                                                    <td><select name="cerinst[]" class="form-control selectval"><option value="">Select</option>@foreach ($institutes as $institute)<option value="{{ $institute->id }}" @selected((int) $row['inst_id'] === (int) $institute->id)>{{ $institute->name }}</option>@endforeach</select></td>
                                                    <td><select name="certrining[]" class="form-control selectval"><option value="">Select</option>@foreach ($trainings as $training)<option value="{{ $training->id }}" @selected((int) $row['trining_id'] === (int) $training->id)>{{ $training->name }}</option>@endforeach</select></td>
                                                    <td><select name="cerfrom[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}" @selected((int) $row['from'] === (int) $year->id)>{{ $year->name }}</option>@endforeach</select></td>
                                                    <td><select name="certo[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}" @selected((int) $row['to'] === (int) $year->id)>{{ $year->name }}</option>@endforeach</select></td>
                                                    <td><input name="cerobtmark[]" type="text" class="form-control" value="{{ $row['obtmarks'] }}"></td>
                                                    <td><input name="cergrd[]" type="text" class="form-control" value="{{ $row['grade'] }}"></td>
                                                    <td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @empty
                                                <tr><td><select name="cerinst[]" class="form-control selectval"><option value="">Select</option>@foreach ($institutes as $institute)<option value="{{ $institute->id }}">{{ $institute->name }}</option>@endforeach</select></td><td><select name="certrining[]" class="form-control selectval"><option value="">Select</option>@foreach ($trainings as $training)<option value="{{ $training->id }}">{{ $training->name }}</option>@endforeach</select></td><td><select name="cerfrom[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}">{{ $year->name }}</option>@endforeach</select></td><td><select name="certo[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}">{{ $year->name }}</option>@endforeach</select></td><td><input name="cerobtmark[]" type="text" class="form-control" value=""></td><td><input name="cergrd[]" type="text" class="form-control" value=""></td><td></td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Employment record/professional experience (In reverse chronological order please)</h4>
                                <div class="around10">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead><tr><td>Organization</td><td>Position held</td><td>Contact no</td><td>From</td><td>To</td><td>Salary</td><td>Reason of leaving</td><td><button id="btnAddexp" class="btn btn-primary btn-xs" type="button"><i class="fa fa-plus"></i></button></td></tr></thead>
                                        <tbody class="expwarp">
                                            @forelse ($experienceRows as $row)
                                                <tr class="remove_field_warp">
                                                    <td><select name="exporg[]" class="form-control selectval"><option value="">Select</option>@foreach ($organizations as $organization)<option value="{{ $organization->id }}" @selected((int) $row['org_id'] === (int) $organization->id)>{{ $organization->name }}</option>@endforeach</select></td>
                                                    <td><select name="exppost[]" class="form-control selectval"><option value="">Select</option>@foreach ($designations as $designation)<option value="{{ $designation->id }}" @selected((int) $row['postion_id'] === (int) $designation->id)>{{ $designation->name }}</option>@endforeach</select></td>
                                                    <td><input name="expcontact[]" type="text" class="form-control" value="{{ $row['contactno'] }}"></td>
                                                    <td><select name="expfrom[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}" @selected((int) $row['from'] === (int) $year->id)>{{ $year->name }}</option>@endforeach</select></td>
                                                    <td><select name="expto[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}" @selected((int) $row['to'] === (int) $year->id)>{{ $year->name }}</option>@endforeach</select></td>
                                                    <td><input name="expsalary[]" type="text" class="form-control" value="{{ $row['salary'] }}"></td>
                                                    <td><input name="explereason[]" type="text" class="form-control" value="{{ $row['reason'] }}"></td>
                                                    <td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @empty
                                                <tr><td><select name="exporg[]" class="form-control selectval"><option value="">Select</option>@foreach ($organizations as $organization)<option value="{{ $organization->id }}">{{ $organization->name }}</option>@endforeach</select></td><td><select name="exppost[]" class="form-control selectval"><option value="">Select</option>@foreach ($designations as $designation)<option value="{{ $designation->id }}">{{ $designation->name }}</option>@endforeach</select></td><td><input name="expcontact[]" type="text" class="form-control" value=""></td><td><select name="expfrom[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}">{{ $year->name }}</option>@endforeach</select></td><td><select name="expto[]" class="form-control selectval"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}">{{ $year->name }}</option>@endforeach</select></td><td><input name="expsalary[]" type="text" class="form-control" value=""></td><td><input name="explereason[]" type="text" class="form-control" value=""></td><td></td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Contract</h4>
                                <div class="around10">
                                    <div class="row">
                                        <div class="col-md-4"><div class="form-group"><label>Contract Type</label><select class="form-control" name="contract_type"><option value="">Select</option>@foreach ($contractTypes as $key => $value)<option value="{{ $key }}" @selected(old('contract_type', $staff->contract_type) === $key)>{{ $value }}</option>@endforeach</select><span class="text-danger">{{ $errors->first('contract_type') }}</span></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Work Shift</label><select class="form-control" name="shift"><option value="">Select</option>@foreach ($shiftOptions as $key => $value)<option value="{{ $key }}" @selected(old('shift', $staff->shift) === $key)>{{ $value }}</option>@endforeach</select><span class="text-danger">{{ $errors->first('shift') }}</span></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Work Location</label><input id="location" name="location" type="text" class="form-control" value="{{ old('location', $staff->location) }}"><span class="text-danger">{{ $errors->first('location') }}</span></div></div>
                                    </div>
                                </div>
                            </div>

                            @php
                                $totalPayAmount = collect($payRows)->sum(fn ($row) => (float) ($row['amount'] ?? 0));
                                $totalDeductionAmount = collect($payDeductionRows)->sum(fn ($row) => (float) ($row['amount'] ?? 0));
                            @endphp
                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Payroll</h4>
                                <div class="around10">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="col-md-6"><div class="form-group"><label>Total Security</label><input id="total_security" name="total_security" type="text" class="form-control" value="{{ old('total_security', $staff->total_security) }}"><span class="text-danger">{{ $errors->first('total_security') }}</span></div></div>
                                            <div class="col-md-6"><div class="form-group"><label>Per Month Security</label><input id="per_month_security" name="per_month_security" type="text" class="form-control" value="{{ old('per_month_security', $staff->month_security) }}"><span class="text-danger">{{ $errors->first('month_security') }}</span></div></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4>Salary Details</h4>
                                            <table class="table">
                                                <thead><tr><td>Salary Type</td><td>Frequency</td><td>Amount</td><td><button id="btnAddsalary" class="btn btn-primary btn-xs" type="button"><i class="fa fa-plus"></i></button></td></tr></thead>
                                                <tbody class="salarywarp">
                                                    @forelse ($payRows as $row)
                                                        <tr class="salary_remove_field_warp">
                                                            <td><select name="salary_type[]" class="form-control selectval"><option value="">Select</option>@foreach ($payTypes as $payType)<option value="{{ $payType->id }}" @selected((int) $row['type_id'] === (int) $payType->id)>{{ $payType->name }}</option>@endforeach</select></td>
                                                            <td><select name="frequency[]" class="form-control"><option value="">Select</option><option value="Basic Pay" @selected(($row['frequency'] ?? '') === 'Basic Pay')>Basic Pay</option><option value="Allowance" @selected(($row['frequency'] ?? '') === 'Allowance')>Allowance</option><option value="Increment" @selected(($row['frequency'] ?? '') === 'Increment')>Increment</option></select></td>
                                                            <td><input type="text" name="salary_amount[]" class="form-control salary_amount" onkeyup="getsalarytotal()" value="{{ (float) ($row['amount'] ?? 0) }}"></td>
                                                            <td><button class="btn btn-danger btn-xs btndetlsalary" type="button"><i class="fa fa-trash"></i></button></td>
                                                        </tr>
                                                    @empty
                                                        <tr><td><select name="salary_type[]" class="form-control selectval"><option value="">Select</option>@foreach ($payTypes as $payType)<option value="{{ $payType->id }}">{{ $payType->name }}</option>@endforeach</select></td><td><select name="frequency[]" class="form-control"><option value="">Select</option><option value="Basic Pay">Basic Pay</option><option value="Allowance">Allowance</option><option value="Increment">Increment</option></select></td><td><input type="text" name="salary_amount[]" class="form-control salary_amount" onkeyup="getsalarytotal()" value=""></td><td></td></tr>
                                                    @endforelse
                                                </tbody>
                                                <tbody><tr><td colspan="2" style="text-align:right;"><b>Total Amount</b></td><td class="total-amount">{{ $totalPayAmount }}</td></tr></tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h4>Salary Deduction Details</h4>
                                            <table class="table">
                                                <thead><tr><td>Salary Deduction Type</td><td>Amount</td><td><button id="btnAddsalaryded" class="btn btn-primary btn-xs" type="button"><i class="fa fa-plus"></i></button></td></tr></thead>
                                                <tbody class="salarydedwarp">
                                                    @forelse ($payDeductionRows as $row)
                                                        <tr class="salary_ded_remove_field_warp">
                                                            <td><select name="salary_ded_type[]" class="form-control selectval"><option value="">Select</option>@foreach ($payDeductionTypes as $payType)<option value="{{ $payType->id }}" @selected((int) $row['type_id'] === (int) $payType->id)>{{ $payType->name }}</option>@endforeach</select></td>
                                                            <td><input type="text" name="salary_ded_amount[]" class="form-control salary_ded_amount" onkeyup="getsalarydedtotal()" value="{{ (float) ($row['amount'] ?? 0) }}"></td>
                                                            <td><button class="btn btn-danger btn-xs btndetlsalaryded" type="button"><i class="fa fa-trash"></i></button></td>
                                                        </tr>
                                                    @empty
                                                        <tr><td><select name="salary_ded_type[]" class="form-control selectval"><option value="">Select</option>@foreach ($payDeductionTypes as $payType)<option value="{{ $payType->id }}">{{ $payType->name }}</option>@endforeach</select></td><td><input type="text" name="salary_ded_amount[]" class="form-control salary_ded_amount" onkeyup="getsalarydedtotal()" value=""></td><td></td></tr>
                                                    @endforelse
                                                </tbody>
                                                <tbody><tr><td style="text-align:right;"><b>Total Amount</b></td><td class="total-dedamount">{{ $totalDeductionAmount }}</td></tr></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row"><div class="col-md-12"><div class="col-md-4"></div><div class="col-md-4"><h5><b>Net Salary :</b> <span class="net-salary">{{ $totalPayAmount - $totalDeductionAmount }}</span></h5></div><div class="col-md-4"></div></div></div>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Bank Account Details</h4>
                                <div class="around10">
                                    <div class="row">
                                        <div class="col-md-4"><div class="form-group"><label>Account Title</label><input id="account_title" name="account_title" type="text" class="form-control" value="{{ old('account_title', $staff->account_title) }}"><span class="text-danger">{{ $errors->first('account_title') }}</span></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Bank Account No</label><input id="bank_account_no" name="bank_account_no" type="text" class="form-control" value="{{ old('bank_account_no', $staff->bank_account_no) }}"><span class="text-danger">{{ $errors->first('bank_account_no') }}</span></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Bank Name</label><input id="bank_name" name="bank_name" type="text" class="form-control" value="{{ old('bank_name', $staff->bank_name) }}"><span class="text-danger">{{ $errors->first('bank_name') }}</span></div></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4"><div class="form-group"><label>IBAN Code</label><input id="IBAN_code" name="IBAN_code" type="text" class="form-control" value="{{ old('IBAN_code', old('iban_code', $staff->IBAN_code)) }}"><span class="text-danger">{{ $errors->first('iban_code') }}</span></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Bank Branch Name</label><input id="bank_branch" name="bank_branch" type="text" class="form-control" value="{{ old('bank_branch', $staff->bank_branch) }}"><span class="text-danger">{{ $errors->first('bank_branch') }}</span></div></div>
                                    </div>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Social Media</h4>
                                <div class="row around10">
                                    <div class="col-md-6"><div class="form-group"><label>Facebook URL</label><input name="facebook" type="text" class="form-control" value="{{ old('facebook', $staff->facebook) }}"><span class="text-danger">{{ $errors->first('facebook') }}</span></div></div>
                                    <div class="col-md-6"><div class="form-group"><label>Twitter URL</label><input name="twitter" type="text" class="form-control" value="{{ old('twitter', $staff->twitter) }}"><span class="text-danger">{{ $errors->first('twitter') }}</span></div></div>
                                    <div class="col-md-6"><div class="form-group"><label>Linkedin URL</label><input name="linkedin" type="text" class="form-control" value="{{ old('linkedin', $staff->linkedin) }}"><span class="text-danger">{{ $errors->first('linkedin') }}</span></div></div>
                                    <div class="col-md-6"><div class="form-group"><label>Instagram URL</label><input id="instagram" name="instagram" type="text" class="form-control" value="{{ old('instagram', $staff->instagram) }}"></div></div>
                                </div>
                            </div>

                            <div class="tshadow bozero">
                                <h4 class="pagetitleh2">Upload Documents</h4>
                                <div class="around10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table">
                                                <tbody>
                                                    <tr><th style="width: 10px">#</th><th>Title</th><th>Documents</th></tr>
                                                    <tr><td>1.</td><td>Resume @if($staff->resume)<small>({{ $staff->resume }})</small>@endif</td><td><input class="filestyle form-control" type="file" name="first_doc" id="doc1"><span class="text-danger">{{ $errors->first('first_doc') }}</span></td></tr>
                                                    <tr><td>3.</td><td>Other Documents @if($staff->other_document_file)<small>({{ $staff->other_document_file }})</small>@endif<input type="hidden" name="fourth_title" value="Other Documents"></td><td><input class="filestyle form-control" type="file" name="fourth_doc" id="doc4"><span class="text-danger">{{ $errors->first('fourth_doc') }}</span></td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table">
                                                <tbody>
                                                    <tr><th style="width: 10px">#</th><th>Title</th><th>Documents</th></tr>
                                                    <tr><td>2.</td><td>Joining Letter @if($staff->joining_letter)<small>({{ $staff->joining_letter }})</small>@endif</td><td><input class="filestyle form-control" type="file" name="second_doc" id="doc2"><span class="text-danger">{{ $errors->first('second_doc') }}</span></td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="pull-right">
                                <a href="{{ $cancelUrl }}" class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-primary pull-right">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @php
        $buildOptionHtml = static function ($items): string {
            $html = '<option value="">Select</option>';
            foreach ($items as $item) {
                $html .= '<option value="'.$item['id'].'">'.e($item['name']).'</option>';
            }
            return $html;
        };
    @endphp
    <script>
        (() => {
            const csrfToken = @json(csrf_token());
            const optionStoreUrlTemplate = @json(route('admin.hrms.staff.options.store', ['type' => '__TYPE__'], false));
            const universityBoardOptions = `{!! addslashes($buildOptionHtml($universityBoards->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())) !!}`;
            const academicYearOptions = `{!! addslashes($buildOptionHtml($academicYears->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())) !!}`;
            const degreeOptions = `{!! addslashes($buildOptionHtml($degreeCertificates->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())) !!}`;
            const instituteOptions = `{!! addslashes($buildOptionHtml($institutes->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())) !!}`;
            const trainingOptions = `{!! addslashes($buildOptionHtml($trainings->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())) !!}`;
            const organizationOptions = `{!! addslashes($buildOptionHtml($organizations->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())) !!}`;
            const designationOptions = `{!! addslashes($buildOptionHtml($designations->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())) !!}`;
            const payTypeOptions = `{!! addslashes($buildOptionHtml($payTypes->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())) !!}`;
            const payDeductionOptions = `{!! addslashes($buildOptionHtml($payDeductionTypes->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())) !!}`;

            $(document).on('click', '#btnAddedu', function () {
                $('.eduwarp').append(`<tr class="remove_field_warp"><td><select name="eduinst[]" class="form-control selectval">${universityBoardOptions}</select></td><td><select name="edufrom[]" class="form-control selectval">${academicYearOptions}</select></td><td><select name="eduto[]" class="form-control selectval">${academicYearOptions}</select></td><td><select name="edudegree[]" class="form-control selectval">${degreeOptions}</select></td><td><input name="edumaxmark[]" type="text" class="form-control" value=""></td><td><input name="eduobtmark[]" type="text" class="form-control" value=""></td><td><input name="edugrd[]" type="text" class="form-control" value=""></td><td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td></tr>`);
            });
            $(document).on('click', '#btnAddcer', function () {
                $('.cerwarp').append(`<tr class="remove_field_warp"><td><select name="cerinst[]" class="form-control selectval">${instituteOptions}</select></td><td><select name="certrining[]" class="form-control selectval">${trainingOptions}</select></td><td><select name="cerfrom[]" class="form-control selectval">${academicYearOptions}</select></td><td><select name="certo[]" class="form-control selectval">${academicYearOptions}</select></td><td><input name="cerobtmark[]" type="text" class="form-control" value=""></td><td><input name="cergrd[]" type="text" class="form-control" value=""></td><td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td></tr>`);
            });
            $(document).on('click', '#btnAddexp', function () {
                $('.expwarp').append(`<tr class="remove_field_warp"><td><select name="exporg[]" class="form-control selectval">${organizationOptions}</select></td><td><select name="exppost[]" class="form-control selectval">${designationOptions}</select></td><td><input name="expcontact[]" type="text" class="form-control" value=""></td><td><select name="expfrom[]" class="form-control selectval">${academicYearOptions}</select></td><td><select name="expto[]" class="form-control selectval">${academicYearOptions}</select></td><td><input name="expsalary[]" type="text" class="form-control" value=""></td><td><input name="explereason[]" type="text" class="form-control" value=""></td><td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td></tr>`);
            });
            $(document).on('click', '#btnAddsalary', function () {
                $('.salarywarp').append(`<tr class="salary_remove_field_warp"><td><select name="salary_type[]" class="form-control selectval">${payTypeOptions}</select></td><td><select name="frequency[]" class="form-control"><option value="">Select</option><option value="Basic Pay">Basic Pay</option><option value="Allowance">Allowance</option><option value="Increment">Increment</option></select></td><td><input type="text" name="salary_amount[]" class="form-control salary_amount" onkeyup="getsalarytotal()" value=""></td><td><button class="btn btn-danger btn-xs btndetlsalary" type="button"><i class="fa fa-trash"></i></button></td></tr>`);
            });
            $(document).on('click', '#btnAddsalaryded', function () {
                $('.salarydedwarp').append(`<tr class="salary_ded_remove_field_warp"><td><select name="salary_ded_type[]" class="form-control selectval">${payDeductionOptions}</select></td><td><input type="text" name="salary_ded_amount[]" class="form-control salary_ded_amount" onkeyup="getsalarydedtotal()" value=""></td><td><button class="btn btn-danger btn-xs btndetlsalaryded" type="button"><i class="fa fa-trash"></i></button></td></tr>`);
            });
            $(document).on('click', '.remove-row', function () { $(this).closest('tr').remove(); });
            $(document).on('click', '.btndetlsalary', function () { $(this).closest('.salary_remove_field_warp').remove(); getsalarytotal(); getnetsalarytotal(); });
            $(document).on('click', '.btndetlsalaryded', function () { $(this).closest('.salary_ded_remove_field_warp').remove(); getsalarydedtotal(); getnetsalarytotal(); });

            $(document).on('click', '[data-option-type]', async function () {
                const button = this;
                const type = button.dataset.optionType;
                const target = document.getElementById(button.dataset.optionTarget);
                const name = window.prompt(`Enter ${type.replace('_', ' ')} name`);

                if (!name || !target) {
                    return;
                }

                const branchId = document.getElementById('brc_id')?.value || '';
                button.disabled = true;

                try {
                    const response = await fetch(optionStoreUrlTemplate.replace('__TYPE__', type), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            name,
                            branch_id: branchId,
                        }),
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        window.alert(data.message || 'Unable to add option right now.');
                        return;
                    }

                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = data.name;
                    option.selected = true;
                    target.appendChild(option);
                    target.value = String(data.id);
                } catch (error) {
                    window.alert('Unable to add option right now.');
                } finally {
                    button.disabled = false;
                }
            });
        })();

        function getsalarytotal() {
            let salarysum = 0;
            $('.salary_amount').each(function () { salarysum += Number($(this).val() || 0); });
            $('.total-amount').html(salarysum);
            getnetsalarytotal();
        }

        function getsalarydedtotal() {
            let salarydedsum = 0;
            $('.salary_ded_amount').each(function () { salarydedsum += Number($(this).val() || 0); });
            $('.total-dedamount').html(salarydedsum);
            getnetsalarytotal();
        }

        function getnetsalarytotal() {
            let salarysum = 0;
            let salarydedsum = 0;
            $('.salary_amount').each(function () { salarysum += Number($(this).val() || 0); });
            $('.salary_ded_amount').each(function () { salarydedsum += Number($(this).val() || 0); });
            $('.net-salary').html(salarysum - salarydedsum);
        }

        document.querySelector('.showcam')?.addEventListener('click', () => {
            document.getElementById('my_camera').style.display = 'block';
            document.getElementById('pre_take_buttons').style.display = 'block';
            Webcam.set({ width: 220, height: 180, image_format: 'jpeg', jpeg_quality: 90 });
            Webcam.attach('#my_camera');
        });

        function preview_snapshot() {
            Webcam.freeze();
            document.getElementById('post_take_buttons').style.display = 'block';
            document.getElementById('pre_take_buttons').style.display = 'none';
        }

        function cancel_preview() {
            Webcam.unfreeze();
            document.getElementById('post_take_buttons').style.display = 'none';
            document.getElementById('pre_take_buttons').style.display = 'block';
        }

        function save_photo() {
            Webcam.snap(function (dataUri) {
                document.querySelector('.image-tag').value = dataUri;
                document.getElementById('results').style.display = 'block';
                document.getElementById('results').innerHTML = `<img src="${dataUri}" class="img-responsive img-thumbnail" />`;
                document.getElementById('post_take_buttons').style.display = 'none';
                document.getElementById('pre_take_buttons').style.display = 'block';
                Webcam.reset();
                document.getElementById('my_camera').style.display = 'none';
            });
        }
    </script>
@endpush
