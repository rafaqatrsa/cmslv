@extends('admin.layouts.app')

@section('title', $editing ? 'Edit Student Registration' : 'Student Registration')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style-main.css') }}">
    <style>
        .legacy-student-regd .content-wrapper {
            min-height: auto;
        }

        .legacy-student-regd .box {
            border: 1px solid #cbc8c8;
            border-radius: 3px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 2px 3px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .legacy-student-regd .box-header {
            align-items: center;
            border-bottom: 1px solid #f4f4f4;
            display: flex;
            justify-content: space-between;
            padding: 10px 14px;
        }

        .legacy-student-regd .box-title {
            font-size: 18px;
            margin: 0;
        }

        .legacy-student-regd .box-tools {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .legacy-student-regd .box-tools .btn {
            font-size: 12px;
            font-weight: 600;
        }

        .legacy-student-regd .nav-tabs-custom {
            margin-bottom: 0;
            border: 1px solid #cbc8c8;
            border-radius: 3px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 2px 3px rgba(0, 0, 0, 0.08);
        }

        .legacy-student-regd .pagetitleh2 {
            margin: 0;
            padding: 10px 14px;
            border-bottom: 1px solid #e3e7eb;
            background: #f5f5f5;
            font-size: 14px;
            font-weight: 700;
            color: #111;
        }

        .legacy-student-regd .around10 {
            padding: 14px;
        }

        .legacy-student-regd .tshadow {
            overflow: hidden;
            border-radius: 3px;
            border: 1px solid #e0e0e0;
            background: #fff;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        }

        .legacy-student-regd .bozero {
            border: 0;
        }

        .legacy-student-regd .admission-enquiry-note {
            display: block;
            margin-top: 6px;
            color: #666;
        }

        .legacy-student-regd .regd-student-photo .form-control {
            height: 36px;
            padding: 6px 10px;
        }

        .legacy-student-regd .input-group-addon select {
            min-width: 72px;
            border: 0;
            background: transparent;
            outline: none;
        }

        .legacy-student-regd .fee-rows-table th,
        .legacy-student-regd .fee-rows-table td {
            vertical-align: top !important;
        }

        .legacy-student-regd .section-grid {
            display: grid;
            gap: 10px;
        }

        .legacy-student-regd .section-grid--two {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        @media (max-width: 1199px) {
            .legacy-student-regd .section-grid--two {
                grid-template-columns: 1fr;
            }
        }

        .legacy-student-regd .small-action {
            min-width: 30px;
            padding: 4px 8px;
        }

        .legacy-student-regd .registration-summary {
            margin-left: auto;
            font-weight: 700;
            color: #fff;
        }

        .legacy-student-regd .tab-pane {
            padding: 0;
        }

        .legacy-student-regd .table thead th {
            background: #ececec;
            color: #333;
        }

        .legacy-student-regd .table-bordered > thead > tr > th,
        .legacy-student-regd .table-bordered > tbody > tr > th,
        .legacy-student-regd .table-bordered > tfoot > tr > th,
        .legacy-student-regd .table-bordered > thead > tr > td,
        .legacy-student-regd .table-bordered > tbody > tr > td,
        .legacy-student-regd .table-bordered > tfoot > tr > td {
            border-color: #e5e5e5;
        }

        .legacy-student-regd .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #fff;
        }

        .legacy-student-regd .table-striped > tbody > tr:nth-of-type(even) {
            background-color: #fafafa;
        }

        .legacy-student-regd .student-regd-tabs > .nav-tabs > li.active > a {
            border-bottom: 1px solid #fff;
        }
    </style>
@endpush

@section('content')
    @php
        $currentRegistration = $registration ?? null;
        $value = fn (string $key, $default = null) => old($key, data_get($currentRegistration, $key, $default));
        $isEditing = (bool) ($editing ?? false);
        $selectedBranch = old('brc_id', data_get($currentRegistration, 'brc_id', $branchId));
        $selectedSession = old('session_id', data_get($currentRegistration, 'session_id', $current_session));
        $selectedAcademicYear = old('adcademicyear_id', data_get($currentRegistration, 'adcademicyear_id', $current_academic_year));
        $regdNoValue = old('regd_no', data_get($currentRegistration, 'regd_no', $regd_no ?? $generatedRegdNo));
        $studentNameValue = old('student_name', trim((string) data_get($currentRegistration, 'firstname', '') . ' ' . (string) data_get($currentRegistration, 'lastname', '')));
        $studentRegdList = $studentRegdList ?? $registrations ?? collect();
        $sessionList = $sessionlist ?? $sessions ?? collect();
        $classList = $classlist ?? $classes ?? collect();
        $academicYearList = $adcademicyearlist ?? $academicYears ?? collect();
        $religionList = $religionlist ?? $religions ?? collect();
        $mediumList = $mediumlist ?? $mediums ?? collect();
        $previousSchoolList = $perviousschoollist ?? $previousSchools ?? collect();
        $occupationList = $occuptionlist ?? $occupations ?? collect();
        $countryList = $countrylist ?? $countries ?? collect();
        $enquiryList = collect($enquiryDropdownList ?? []);
        $groupedEnquiries = $enquiryList->groupBy('enquiry_id');
        $feeRowsValue = old('fee_rows', $feeRows ?? [[
            'feetype_id' => null,
            'frequency' => '',
            'amount' => '',
            'note' => '',
        ]]);
        $defaultCountryCode = data_get($countryList->firstWhere('id', 178), 'telephonePrefix', '+92');
    @endphp

    <div class="legacy-student-regd">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Student Registration</h3>
                            <div class="box-tools pull-right">
                                <a href="{{ route('admin.adm.student-registrations.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add Student Admission
                                </a>
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalFilledRegdFormSelect">
                                    <i class="fa fa-file-text-o"></i> Filled Reg Form
                                </button>
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalregdform">
                                    <i class="fa fa-cloud-download"></i> Registration Form
                                </button>
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalregdvoucher">
                                    <i class="fa fa-cloud-download"></i> Empty Voucher
                                </button>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="nav-tabs-custom student-regd-tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#add_std_regd" data-cmsc-tab-link="#add_std_regd">{{ $isEditing ? 'Edit Student Registration' : 'Add Student Registration' }}</a>
                                    </li>
                                    <li>
                                        <a href="#enquiry_list_tab" data-cmsc-tab-link="#enquiry_list_tab">Admission Enquiry</a>
                                    </li>
                                    <li>
                                        <a href="#std_regd_list" data-cmsc-tab-link="#std_regd_list">Student Registration List</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="add_std_regd">
                                        <form
                                            action="{{ $isEditing ? route('admin.adm.student-registrations.update', $currentRegistration) : route('admin.adm.student-registrations.store') }}"
                                            method="POST"
                                            accept-charset="utf-8"
                                            enctype="multipart/form-data"
                                            id="studentregdformadd"
                                            name="studentregdformadd"
                                        >
                                            @csrf
                                            @if ($isEditing)
                                                @method('PUT')
                                            @endif

                                            <input type="hidden" name="data_setting_id" value="{{ $id ?? '' }}">
                                            <input type="hidden" name="data_setting_regd_auto_insert" value="">
                                            <input type="hidden" name="data_setting_regd_update_status" value="">
                                            <input type="hidden" id="registration_enquiry_kid_id" name="registration_enquiry_kid_id" value="{{ old('registration_enquiry_kid_id', data_get($currentRegistration, 'registration_enquiry_kid_id')) }}">
                                            <input type="hidden" name="registration_enquiry_id" id="registration_enquiry_id_hidden" value="{{ old('registration_enquiry_id', data_get($currentRegistration, 'registration_enquiry_id')) }}">
                                            <input type="hidden" name="regd_date_current" value="{{ old('regd_date_current', data_get($currentRegistration, 'regd_date_current', $schoolDate ?? now()->toDateString())) }}">
                                            <input type="hidden" name="is_active" value="{{ old('is_active', data_get($currentRegistration, 'is_active', 'no')) }}">
                                            <input type="hidden" name="regd_status" value="{{ old('regd_status', data_get($currentRegistration, 'regd_status', 1)) }}">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="tshadow mb25 bozero">
                                                        <h4 class="pagetitleh2">Admission Enquiry Information</h4>
                                                        <div class="around10">
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label for="registration_enquiry_id">Admission Enquiry</label>
                                                                        <select id="registration_enquiry_id" name="registration_enquiry_id_select" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($groupedEnquiries as $enquiryId => $rows)
                                                                                @php $row = $rows->first(); @endphp
                                                                                <option value="{{ $enquiryId }}" data-enquiry-kid-id="{{ data_get($row, 'enquiry_kid_id') }}" data-class-id="{{ data_get($row, 'class_id') }}">
                                                                                    Enquiry #{{ data_get($row, 'enquiry_no') }} / Kid: {{ data_get($row, 'kid_name') }}{{ data_get($row, 'class') ? ' (' . data_get($row, 'class') . ')' : '' }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span class="admission-enquiry-note">Same enquiry number can appear for multiple kids when one enquiry has more than one child.</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6" style="padding-right:5px;">
                                                    <div class="tshadow mb25 bozero">
                                                        <h4 class="pagetitleh2">Student Information</h4>
                                                        <div class="around10">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="country_id">Country</label>
                                                                        <select id="country_id" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($countries as $country)
                                                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="province_id">Province</label>
                                                                        <select id="province_id" class="form-control selectval"><option value="">Select</option></select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="division_id">Division</label>
                                                                        <select id="division_id" class="form-control selectval"><option value="">Select</option></select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="brc_id">Branch <small class="req">*</small></label>
                                                                        <select id="brc_id" name="brc_id" class="form-control selectval brc_id">
                                                                            <option value="">Select</option>
                                                                            @foreach ($branches as $branch)
                                                                                <option value="{{ $branch->id }}" @selected((string) $selectedBranch === (string) $branch->id)>{{ $branch->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="session_id">Session <small class="req">*</small></label>
                                                                        <select id="session_id" name="session_id" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($sessionList as $session)
                                                                                <option value="{{ $session->id }}" @selected((string) $selectedSession === (string) $session->id)>{{ $session->session }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="adcademicyear_id">Academic Year <small class="req">*</small></label>
                                                                        <select id="adcademicyear_id" name="adcademicyear_id" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($academicYearList as $academicYear)
                                                                                <option value="{{ $academicYear->id }}" @selected((string) $selectedAcademicYear === (string) $academicYear->id)>{{ $academicYear->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="regd_no">Registration No <small class="req">*</small></label>
                                                                        <input id="regd_no" name="regd_no" type="text" class="form-control" value="{{ $regdNoValue }}" readonly="readonly">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="student_name">Student Name <small class="req">*</small></label>
                                                                        <input id="student_name" name="student_name" type="text" class="form-control" value="{{ $studentNameValue }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="class_id">Class <small class="req">*</small></label>
                                                                        <select id="class_id" name="class_id" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($classList as $class)
                                                                                <option value="{{ $class->id }}" @selected((string) $value('class_id') === (string) $class->id)>{{ $class->class }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="gender">Gender <small class="req">*</small></label>
                                                                        <select class="form-control selectval" name="gender" id="gender">
                                                                            <option value="">Select</option>
                                                                            @foreach ($genders as $gender)
                                                                                <option value="{{ $gender['value'] }}" @selected((string) $value('gender') === (string) $gender['value'])>{{ $gender['label'] }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="dob">Date of Birth <small class="req">*</small></label>
                                                                        <input id="dob" name="dob" type="text" class="form-control" value="{{ $value('dob') }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="b_form_no">B-Form No</label>
                                                                        <input id="b_form_no" name="b_form_no" type="text" class="form-control" value="{{ $value('bayformno') }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="religion_id">Religion</label>
                                                                        <select id="religion_id" name="religion_id" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($religionList as $religion)
                                                                                <option value="{{ $religion->id }}" @selected((string) $value('religion') === (string) $religion->id)>{{ $religion->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="medium_id">Medium</label>
                                                                        <select id="medium_id" name="medium_id" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($mediumList as $medium)
                                                                                <option value="{{ $medium->id }}" @selected((string) $value('medium_id') === (string) $medium->id)>{{ $medium->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mobileno">Mobile No</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <select id="mobile_country_code" name="mobile_country_code">
                                                                                    <option value="{{ $defaultCountryCode }}" @selected((string) $value('mobile_country_code') === (string) $defaultCountryCode)>{{ $defaultCountryCode }}</option>
                                                                                </select>
                                                                            </span>
                                                                            <input id="mobileno" name="mobileno" type="text" class="form-control" value="{{ $value('mobileno') }}" placeholder="300xxxxxxx" maxlength="10">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="pervious_school_id">Pervious School</label>
                                                                        <select id="pervious_school_id" name="pervious_school_id" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($previousSchoolList as $previousSchool)
                                                                                <option value="{{ $previousSchool->id }}" @selected((string) $value('previous_school_id') === (string) $previousSchool->id)>{{ $previousSchool->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="class_left">Class Left</label>
                                                                        <select id="class_left" name="class_left" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($classList as $class)
                                                                                <option value="{{ $class->class }}" @selected((string) $value('previous_class') === (string) $class->class)>{{ $class->class }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="leaving_date">Leaving Date</label>
                                                                        <input id="leaving_date" name="leaving_date" type="text" class="form-control" value="{{ $value('pervious_schl_leaving_date') }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="district_id">City</label>
                                                                        <select id="district_id" name="district_id" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($districts as $district)
                                                                                <option value="{{ $district->id }}" @selected((string) $value('district_id') === (string) $district->id)>{{ $district->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="tehsils_id">Block / Town</label>
                                                                        <select id="tehsils_id" name="tehsils_id" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($tehsils as $tehsil)
                                                                                <option value="{{ $tehsil->id }}" @selected((string) $value('tehsils_id') === (string) $tehsil->id)>{{ $tehsil->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="area_id">Area</label>
                                                                        <select id="area_id" name="area_id" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($areas as $area)
                                                                                <option value="{{ $area->id }}" @selected((string) $value('area_id') === (string) $area->id)>{{ $area->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group regd-student-photo">
                                                                        <label for="student_photo">Student Photo</label>
                                                                        <input class="filestyle form-control" type="file" name="student_photo" id="student_photo" accept=".jpg,.jpeg,.png,.webp">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="regd_date">Registration Date <small class="req">*</small></label>
                                                                        <input id="regd_date" name="regd_date" type="text" class="form-control" value="{{ $value('regd_date', $schoolDate ?? now()->toDateString()) }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6" style="padding-left:0;">
                                                    <div class="tshadow mb25 bozero">
                                                        <h4 class="pagetitleh2">Parent / Guardian Information</h4>
                                                        <div class="around10">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="father_name">Father Name</label>
                                                                        <input id="father_name" name="father_name" type="text" class="form-control" value="{{ $value('father_name') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="father_phone">Father Phone</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <select id="father_country_code" name="father_country_code">
                                                                                    <option value="{{ $defaultCountryCode }}" @selected((string) $value('father_country_code') === (string) $defaultCountryCode)>{{ $defaultCountryCode }}</option>
                                                                                </select>
                                                                            </span>
                                                                            <input id="father_phone" name="father_phone" type="text" class="form-control" value="{{ $value('father_phone') }}" placeholder="300xxxxxxx" maxlength="10">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="father_occupation">Father Occupation</label>
                                                                        <select id="father_occupation" name="father_occupation" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($occupationList as $occupation)
                                                                                <option value="{{ $occupation->id }}" @selected((string) $value('father_occupation') === (string) $occupation->id)>{{ $occupation->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mother_name">Mother Name</label>
                                                                        <input id="mother_name" name="mother_name" type="text" class="form-control" value="{{ $value('mother_name') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mother_phone">Mother Phone</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <select id="mother_country_code" name="mother_country_code">
                                                                                    <option value="{{ $defaultCountryCode }}" @selected((string) $value('mother_country_code') === (string) $defaultCountryCode)>{{ $defaultCountryCode }}</option>
                                                                                </select>
                                                                            </span>
                                                                            <input id="mother_phone" name="mother_phone" type="text" class="form-control" value="{{ $value('mother_phone') }}" placeholder="300xxxxxxx" maxlength="10">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="mother_occupation">Mother Occupation</label>
                                                                        <select id="mother_occupation" name="mother_occupation" class="form-control selectval">
                                                                            <option value="">Select</option>
                                                                            @foreach ($occupationList as $occupation)
                                                                                <option value="{{ $occupation->id }}" @selected((string) $value('mother_occupation') === (string) $occupation->id)>{{ $occupation->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="father_cnic">Father CNIC</label>
                                                                        <input id="father_cnic" name="father_cnic" type="text" class="form-control" value="{{ $value('father_cnic') }}" placeholder="34101xxxxxxxx" maxlength="13">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>If Guardian Is</label>
                                                                        <label class="radio-inline"><input type="radio" name="guardian_is" value="father" @checked((string) $value('guardian_is') === 'father')> Father</label>
                                                                        <label class="radio-inline"><input type="radio" name="guardian_is" value="mother" @checked((string) $value('guardian_is') === 'mother')> Mother</label>
                                                                        <label class="radio-inline"><input type="radio" name="guardian_is" value="other" @checked((string) $value('guardian_is') === 'other')> Other</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="guardian_name">Guardian Name</label>
                                                                        <input id="guardian_name" name="guardian_name" type="text" class="form-control" value="{{ $value('guardian_name') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="guardian_relation">Guardian Relation</label>
                                                                        <input id="guardian_relation" name="guardian_relation" type="text" class="form-control" value="{{ $value('guardian_relation') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="guardian_phone">Guardian Phone</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <select id="guardian_country_code" name="guardian_country_code">
                                                                                    <option value="{{ $defaultCountryCode }}" @selected((string) $value('guardian_country_code') === (string) $defaultCountryCode)>{{ $defaultCountryCode }}</option>
                                                                                </select>
                                                                            </span>
                                                                            <input id="guardian_phone" name="guardian_phone" type="text" class="form-control" value="{{ $value('guardian_phone') }}" placeholder="300xxxxxxx" maxlength="10">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="guardian_occupation">Guardian Occupation</label>
                                                                        <input id="guardian_occupation" name="guardian_occupation" type="text" class="form-control" value="{{ $value('guardian_occupation') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="guardian_email">Guardian Email</label>
                                                                        <input id="guardian_email" name="guardian_email" type="text" class="form-control" value="{{ $value('guardian_email') }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="address">Address</label>
                                                                        <textarea id="address" name="address" class="form-control" rows="3">{{ $value('address') }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="tshadow mb25 bozero">
                                                        <div class="col-md-12 pagetitleh2">
                                                            <h4 class="box-title titlefix pull-left" style="margin:7px 0 0 0;">Fee Information</h4>
                                                            <div class="box-tools pull-right">
                                                                <button id="btnAdd" style="margin-left:20px" class="btn btn-primary btn-sm checkbox-toggle pull-right" type="button">
                                                                    <i class="fa fa-plus"></i> Add
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="around10">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped fee-rows-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Fee Type</th>
                                                                            <th>Frequency</th>
                                                                            <th>School Fee</th>
                                                                            <th>Decided Fee</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="feesassigncontainer" id="fee-rows">
                                                                        @forelse ($feeRowsValue as $rowIndex => $row)
                                                                            <tr data-fee-row>
                                                                                <td>
                                                                                    <select name="fee_rows[{{ $rowIndex }}][feetype_id]" class="form-control selectval">
                                                                                        <option value="">Select</option>
                                                                                        @foreach ($feeHeads as $feeHead)
                                                                                            <option value="{{ $feeHead->id }}" @selected((string) data_get($row, 'feetype_id') === (string) $feeHead->id)>{{ $feeHead->name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input name="fee_rows[{{ $rowIndex }}][frequency]" type="text" class="form-control" value="{{ data_get($row, 'frequency') }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input name="fee_rows[{{ $rowIndex }}][amount]" type="text" class="form-control" value="{{ data_get($row, 'amount') }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input name="fee_rows[{{ $rowIndex }}][note]" type="text" class="form-control" value="{{ data_get($row, 'note') }}">
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button" class="btn btn-danger btn-xs remove-fee-row">
                                                                                        <i class="fa fa-trash"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @empty
                                                                            <tr data-fee-row>
                                                                                <td>
                                                                                    <select name="fee_rows[0][feetype_id]" class="form-control selectval">
                                                                                        <option value="">Select</option>
                                                                                        @foreach ($feeHeads as $feeHead)
                                                                                            <option value="{{ $feeHead->id }}">{{ $feeHead->name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </td>
                                                                                <td><input name="fee_rows[0][frequency]" type="text" class="form-control" value=""></td>
                                                                                <td><input name="fee_rows[0][amount]" type="text" class="form-control" value=""></td>
                                                                                <td><input name="fee_rows[0][note]" type="text" class="form-control" value=""></td>
                                                                                <td class="text-center"><button type="button" class="btn btn-danger btn-xs remove-fee-row"><i class="fa fa-trash"></i></button></td>
                                                                            </tr>
                                                                        @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="box-footer clearfix">
                                                <div class="pull-right">
                                                    <label style="margin:8px 15px 0 0;" class="checkbox-inline">
                                                        <input type="checkbox" name="notification" checked value="notification"> Student Regd Notification
                                                    </label>
                                                    <button type="submit" class="btn btn-primary pull-right addbtn">
                                                        {{ $isEditing ? 'Update' : 'Save' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="enquiry_list_tab">
                                        <div class="download_label" style="display:none;">Admission Enquiry</div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover example">
                                                <thead>
                                                    <tr>
                                                        <th>Admission Enquiry Information</th>
                                                        <th>Kid Details</th>
                                                        <th>Status</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($groupedEnquiries as $enquiryId => $rows)
                                                        @php $row = $rows->first(); @endphp
                                                        <tr>
                                                            <td>
                                                                <strong>Enquiry No:</strong> {{ data_get($row, 'enquiry_no') }}<br>
                                                                <strong>Name:</strong> {{ data_get($row, 'name') }}<br>
                                                                <strong>Father Name:</strong> {{ data_get($row, 'father_name') }}<br>
                                                                <strong>Phone:</strong> {{ data_get($row, 'contact') }}
                                                            </td>
                                                            <td>
                                                                <table class="table table-condensed table-bordered" style="margin-bottom:0;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Kid Name</th>
                                                                            <th>Class</th>
                                                                            <th class="text-right">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($rows as $kidRow)
                                                                            <tr>
                                                                                <td>{{ data_get($kidRow, 'kid_name') }}</td>
                                                                                <td>{{ data_get($kidRow, 'class') ?: '-' }}</td>
                                                                                <td class="text-right">
                                                                                    <button type="button" class="btn btn-primary btn-xs btn-use-enquiry" data-enquiry-id="{{ data_get($kidRow, 'enquiry_id') }}" data-enquiry-kid-id="{{ data_get($kidRow, 'enquiry_kid_id') }}">
                                                                                        <i class="fa fa-check"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td>{{ ucfirst((string) data_get($row, 'status', 'Active')) }}</td>
                                                            <td class="text-right">
                                                                <button type="button" class="btn btn-info btn-xs">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center">No record found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="std_regd_list">
                                        <div class="download_label" style="display:none;">Std Regd List</div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover example">
                                                <thead>
                                                    <tr>
                                                        <th>Branch</th>
                                                        <th>Regd No</th>
                                                        <th>Class</th>
                                                        <th>Student Name</th>
                                                        <th>Father Name</th>
                                                        <th>Father Phone</th>
                                                        <th class="text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($studentRegdList as $student)
                                                        <tr>
                                                            <td>{{ data_get($student, 'branch_name', data_get($branches->firstWhere('id', $student->brc_id), 'name', $student->brc_id)) }}</td>
                                                            <td>{{ $student->regd_no }}</td>
                                                            <td>{{ data_get($classList->firstWhere('id', $student->class_id), 'class', $student->class_id) }}</td>
                                                            <td>{{ trim(($student->firstname ?? '') . ' ' . ($student->lastname ?? '')) }}</td>
                                                            <td>{{ $student->father_name }}</td>
                                                            <td>{{ ($student->father_country_code ?? '') . ($student->father_phone ?? '') }}</td>
                                                            <td class="text-right">
                                                                <a href="{{ route('admin.adm.student-registrations.show', $student) }}" class="btn btn-info btn-xs">
                                                                    <i class="fa fa-reorder"></i>
                                                                </a>
                                                                @if (($student->is_active ?? 'yes') !== 'yes')
                                                                    <a href="{{ route('admin.adm.student-registrations.edit', $student) }}" class="btn btn-primary btn-xs">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                                    <form action="{{ route('admin.adm.student-registrations.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this student registration?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-xs">
                                                                            <i class="fa fa-remove"></i>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <span class="btn btn-success btn-xs">Admitted</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center">No student registration records found.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="text-right">
                                            {{ $studentRegdList->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="myModalregdform" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Registration Form</h4>
                    </div>
                    <div class="modal-body">
                        <p>Print the blank student registration form using the current Coordinator layout.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="print-registration-form">Generate Regd Form</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModalFilledRegdFormSelect" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Filled Registration Form</h4>
                    </div>
                    <div class="modal-body">
                        <select class="form-control" id="filled-registration-id">
                            <option value="">Select</option>
                            @foreach ($studentRegdList as $student)
                                <option value="{{ $student->id }}">{{ $student->regd_no }} / {{ trim($student->firstname.' '.$student->lastname) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="open-filled-registration">Open Registration</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModalregdvoucher" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Registration Voucher</h4>
                    </div>
                    <div class="modal-body">
                        <p>Print a blank registration fee voucher using the current Coordinator layout.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="print-empty-voucher">Generate Voucher</button>
                    </div>
                </div>
            </div>
        </div>

        <template id="fee-row-template">
            <tr data-fee-row>
                <td>
                    <select class="form-control selectval" data-fee-name="feetype_id">
                        <option value="">Select</option>
                        @foreach ($feeHeads as $feeHead)
                            <option value="{{ $feeHead->id }}">{{ $feeHead->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" class="form-control" data-fee-name="frequency"></td>
                <td><input type="text" class="form-control" data-fee-name="amount"></td>
                <td><input type="text" class="form-control" data-fee-name="note"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-xs remove-fee-row">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </template>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const tabLinks = document.querySelectorAll('[data-cmsc-tab-link]');
            const tabPanes = document.querySelectorAll('.legacy-student-regd .tab-pane');

            function activateTab(targetSelector) {
                tabLinks.forEach((link) => {
                    const isActive = link.getAttribute('data-cmsc-tab-link') === targetSelector;
                    link.parentElement.classList.toggle('active', isActive);
                });

                tabPanes.forEach((pane) => {
                    const isActive = `#${pane.id}` === targetSelector;
                    pane.classList.toggle('active', isActive);
                    pane.style.display = isActive ? 'block' : 'none';
                });
            }

            tabLinks.forEach((link) => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    activateTab(link.getAttribute('data-cmsc-tab-link'));
                });
            });

            const firstActive = document.querySelector('.legacy-student-regd .nav-tabs > li.active > a');
            if (firstActive) {
                activateTab(firstActive.getAttribute('data-cmsc-tab-link'));
            }

            const feeRows = document.getElementById('fee-rows');
            const feeRowTemplate = document.getElementById('fee-row-template');
            const addFeeRowButton = document.getElementById('btnAdd');
            const enquirySelect = document.getElementById('registration_enquiry_id');
            const enquiryHiddenId = document.getElementById('registration_enquiry_id_hidden');
            const enquiryKidHidden = document.getElementById('registration_enquiry_kid_id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            function refreshFeeRowNames() {
                feeRows.querySelectorAll('[data-fee-row]').forEach((row, index) => {
                    row.querySelectorAll('input, select').forEach((field) => {
                        const key = field.getAttribute('data-fee-name');
                        if (!key) {
                            return;
                        }
                        field.name = `fee_rows[${index}][${key}]`;
                    });
                });
            }

            function bindRemoveButtons() {
                feeRows.querySelectorAll('.remove-fee-row').forEach((button) => {
                    button.onclick = () => {
                        const rows = feeRows.querySelectorAll('[data-fee-row]');
                        if (rows.length === 1) {
                            rows[0].querySelectorAll('input, select').forEach((field) => {
                                if (field.tagName === 'SELECT') {
                                    field.selectedIndex = 0;
                                } else {
                                    field.value = '';
                                }
                            });
                            return;
                        }

                        button.closest('[data-fee-row]')?.remove();
                        refreshFeeRowNames();
                        bindRemoveButtons();
                    };
                });
            }

            addFeeRowButton?.addEventListener('click', () => {
                const clone = feeRowTemplate.content.cloneNode(true);
                feeRows.appendChild(clone);
                refreshFeeRowNames();
                bindRemoveButtons();
            });

            bindRemoveButtons();

            document.querySelectorAll('[data-toggle="modal"]').forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    const modal = document.querySelector(trigger.getAttribute('data-target'));
                    modal?.classList.add('in');
                    if (modal) {
                        modal.style.display = 'block';
                        modal.setAttribute('aria-hidden', 'false');
                    }
                });
            });

            document.querySelectorAll('[data-dismiss="modal"]').forEach((trigger) => {
                trigger.addEventListener('click', () => closeModal(trigger.closest('.modal')));
            });

            document.querySelectorAll('.modal').forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            function closeModal(modal) {
                if (!modal) {
                    return;
                }
                modal.classList.remove('in');
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
            }

            function printRegistrationForm(title) {
                const form = document.getElementById('studentregdformadd');
                if (!form) {
                    return;
                }

                const printable = form.cloneNode(true);
                printable.querySelectorAll('button, input[type="hidden"], input[type="file"]').forEach((field) => field.remove());
                const printWindow = window.open('', '_blank', 'width=1000,height=800');
                if (!printWindow) {
                    return;
                }
                printWindow.document.write(`<title>${title}</title><link rel="stylesheet" href="${@json(asset('assets/bootstrap/css/bootstrap.min.css'))}"><style>body{padding:24px;font-family:Arial}.box{border:1px solid #ddd;padding:12px}input,select,textarea{border:0!important;border-bottom:1px solid #999!important;box-shadow:none!important}</style>${printable.outerHTML}`);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
            }

            document.getElementById('print-registration-form')?.addEventListener('click', () => printRegistrationForm('Student Registration Form'));
            document.getElementById('print-empty-voucher')?.addEventListener('click', () => printRegistrationForm('Registration Voucher'));
            document.getElementById('open-filled-registration')?.addEventListener('click', () => {
                const id = document.getElementById('filled-registration-id')?.value;
                if (id) {
                    window.location.href = `${@json(url('/admin/adm/student_regd'))}/${id}`;
                }
            });

            enquirySelect?.addEventListener('change', () => {
                const option = enquirySelect.selectedOptions[0];
                enquiryHiddenId.value = option?.value || '';
                enquiryKidHidden.value = option?.getAttribute('data-enquiry-kid-id') || '';

                if (!enquiryHiddenId.value || !enquiryKidHidden.value) {
                    return;
                }

                fetch(@json(route('admin.adm.student-registrations.enquiry-detail')), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        enquiry_id: enquiryHiddenId.value,
                        enquiry_kid_id: enquiryKidHidden.value,
                        brc_id: document.getElementById('brc_id')?.value || '',
                    }),
                })
                    .then((response) => response.ok ? response.json() : {})
                    .then((detail) => {
                        if (!detail || !detail.enquiry_kid_id) {
                            return;
                        }

                        setFieldValue('student_name', detail.kid_name);
                        setFieldValue('class_id', detail.class_id);
                        setFieldValue('father_name', detail.father_name);
                        setFieldValue('father_phone', detail.contact);
                        setFieldValue('father_cnic', detail.id_card);
                        setFieldValue('guardian_email', detail.email);
                        setFieldValue('address', detail.address);
                        setFieldValue('father_occupation', detail.occupation_id);
                        setFieldValue('guardian_name', detail.father_name);
                        setFieldValue('guardian_phone', detail.contact);
                    });
            });

            document.querySelectorAll('.btn-use-enquiry').forEach((button) => {
                button.addEventListener('click', () => {
                    const enquiryId = button.getAttribute('data-enquiry-id');
                    const kidId = button.getAttribute('data-enquiry-kid-id');
                    if (!enquirySelect || !enquiryId || !kidId) {
                        return;
                    }

                    const option = Array.from(enquirySelect.options).find((item) => item.value === enquiryId && item.getAttribute('data-enquiry-kid-id') === kidId);
                    if (option) {
                        enquirySelect.value = enquiryId;
                        enquirySelect.dispatchEvent(new Event('change'));
                    }
                    activateTab('#add_std_regd');
                });
            });

            function setFieldValue(id, value) {
                const field = document.getElementById(id);
                if (field && value !== null && value !== undefined) {
                    field.value = value;
                    field.dispatchEvent(new Event('change'));
                }
            }

            const locationFields = [
                ['country_id', 'provinces', ['country_id']],
                ['province_id', 'divisions', ['country_id', 'province_id']],
                ['division_id', 'districts', ['country_id', 'province_id', 'division_id']],
                ['district_id', 'tehsils', ['country_id', 'province_id', 'division_id', 'district_id']],
                ['tehsils_id', 'areas', ['country_id', 'province_id', 'division_id', 'district_id', 'tehsils_id']],
            ];

            locationFields.forEach(([sourceId, resource, dependencies]) => {
                document.getElementById(sourceId)?.addEventListener('change', () => {
                    const targetIndex = locationFields.findIndex(([id]) => id === sourceId) + 1;
                    const target = locationFields[targetIndex]?.[0];
                    const select = document.getElementById(target);
                    if (!select) {
                        return;
                    }

                    const params = new URLSearchParams();
                    dependencies.forEach((dependency) => {
                        const value = document.getElementById(dependency)?.value;
                        if (value) {
                            params.set(dependency, value);
                        }
                    });

                    fetch(`${@json(url('/admin/adm/student_regd/location'))}/${resource}?${params}`)
                        .then((response) => response.json())
                        .then((options) => {
                            select.innerHTML = '<option value="">Select</option>';
                            options.forEach((option) => {
                                select.insertAdjacentHTML('beforeend', `<option value="${option.id}">${option.name}</option>`);
                            });
                            select.dispatchEvent(new Event('change'));
                        });
                });
            });

            const currentEnquiry = enquirySelect?.querySelector(`option[value="${enquiryHiddenId?.value || ''}"]`);
            if (currentEnquiry) {
                enquirySelect.value = currentEnquiry.value;
            }
        })();
    </script>
@endpush
