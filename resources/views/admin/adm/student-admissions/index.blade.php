@extends('admin.layouts.app')

@section('title', $student ? 'Edit Student Admission' : 'Student Admission')

@section('content')
    @include('admin.adm.partials.nav')

    @php
        $sessionRecord = $student?->sessions?->sortByDesc('id')->first();
        $value = fn (string $key, mixed $default = null): mixed => old($key, data_get($student, $key, $default));
        $selectedSession = old('session_id', data_get($sessionRecord, 'session_id'));
        $selectedClass = old('class_id', data_get($sessionRecord, 'class_id'));
        $selectedSection = old('section_id', data_get($sessionRecord, 'section_id'));
        $selectedBranch = old('brc_id', data_get($student, 'brc_id', $branchId));
        $feeRows = old('fee_rows', $feeRows ?? []);
        $feeRows = $feeRows ?: [['feetype_id' => '', 'fee_amount' => '', 'current_amount' => '', 'frequency' => 'Monthly', 'note' => '']];
        $dateValue = fn (string $key, string $default = ''): string => ($raw = $value($key, $default)) ? \Carbon\Carbon::parse($raw)->toDateString() : '';
    @endphp

    <section class="mt-4 overflow-hidden rounded-xl border border-neutral-300 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-200 bg-[#26408d] px-4 py-3 text-white">
            <h1 class="text-xl font-semibold"><i class="fa fa-user-plus mr-2"></i>Student Admission</h1>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.adm.student-registrations.create', absolute: false) }}" class="rounded bg-white px-3 py-2 text-sm font-semibold text-[#26408d]">Add Student Registration</a>
                <button type="button" class="rounded bg-amber-500 px-3 py-2 text-sm font-semibold" id="print-admission-form">Admission Form</button>
            </div>
        </div>

        @if (session('status'))
            <div class="m-4 rounded border border-green-300 bg-green-50 px-4 py-3 text-green-800">{{ session('status') }}</div>
        @endif

        <div class="border-b border-neutral-200 px-4 pt-4">
            <nav class="flex flex-wrap gap-2">
                <a href="#admission-form" class="rounded-t bg-[#26408d] px-4 py-2 text-sm font-semibold text-white">{{ $student ? 'Edit Student Admission' : 'Add Student Admission' }}</a>
                <a href="#admission-list" class="rounded-t bg-neutral-100 px-4 py-2 text-sm font-semibold text-neutral-700">Recently Added Students</a>
            </nav>
        </div>

        <div id="admission-form" class="p-4">
            <form method="POST" enctype="multipart/form-data" action="{{ $student ? route('admin.adm.student-admissions.update', $student) : route('admin.adm.student-admissions.store') }}" class="space-y-5" id="student-admission-form">
                @csrf
                @if ($student) @method('PUT') @endif
                <input type="hidden" name="brc_id" value="{{ $selectedBranch }}">

                <div class="rounded border border-neutral-200 p-4">
                    <h2 class="mb-4 border-b border-neutral-200 pb-2 text-lg font-semibold text-neutral-800">Registration / Sibling Information</h2>
                    <div class="grid gap-3 md:grid-cols-3">
                        <label class="field"><span>Branch</span><select name="branch_display" disabled class="control"><option>Select</option>@foreach ($branches as $branch)<option value="{{ $branch->id }}" @selected((string) $selectedBranch === (string) $branch->id)>{{ $branch->name }}</option>@endforeach</select></label>
                        <label class="field"><span>Search by Registration No</span><select id="regd_id" name="regd_id" class="control"><option value="">Select</option>@foreach ($registrations as $registration)<option value="{{ $registration->id }}">{{ $registration->regd_no }} - {{ $registration->firstname }} {{ $registration->lastname }} - {{ $registration->father_cnic }}</option>@endforeach</select></label>
                        <label class="field"><span>Search Sibling by CNIC</span><input name="father_cnic" id="father_cnic" value="{{ $value('father_cnic') }}" class="control"></label>
                    </div>
                </div>

                <div class="grid gap-5 xl:grid-cols-2">
                    <div class="rounded border border-neutral-200 p-4">
                        <h2 class="mb-4 border-b border-neutral-200 pb-2 text-lg font-semibold text-neutral-800">Student Information</h2>
                        <div class="grid gap-3 md:grid-cols-2">
                            <label class="field"><span>Session *</span><select name="session_id" id="session_id" class="control"><option value="">Select</option>@foreach ($sessions as $session)<option value="{{ $session->id }}" @selected((string) $selectedSession === (string) $session->id)>{{ $session->session }}</option>@endforeach</select></label>
                            <label class="field"><span>Academic Year *</span><select name="adcademicyear_id" class="control"><option value="">Select</option>@foreach ($academicYears as $year)<option value="{{ $year->id }}" @selected((string) $value('adcademicyear_id') === (string) $year->id)>{{ $year->name }}</option>@endforeach</select></label>
                            <label class="field"><span>Admission No *</span><input name="admission_no" value="{{ $value('admission_no', 'ADM-'.str_pad((string) ((int) ($students->total() ?? 0) + 1), 4, '0', STR_PAD_LEFT)) }}" class="control"></label>
                            <label class="field"><span>Class *</span><select name="class_id" id="class_id" class="control"><option value="">Select</option>@foreach ($classes as $class)<option value="{{ $class->id }}" @selected((string) $selectedClass === (string) $class->id)>{{ $class->class }}</option>@endforeach</select></label>
                            <label class="field"><span>Section *</span><select name="section_id" id="section_id" class="control"><option value="">Select</option>@foreach ($sections as $section)<option value="{{ $section->id }}" @selected((string) $selectedSection === (string) $section->id)>{{ $section->section }}</option>@endforeach</select></label>
                            <label class="field"><span>First Name *</span><input name="firstname" id="firstname" value="{{ $value('firstname') }}" class="control"></label>
                            <label class="field"><span>Middle Name</span><input name="middlename" value="{{ $value('middlename') }}" class="control"></label>
                            <label class="field"><span>Last Name</span><input name="lastname" value="{{ $value('lastname') }}" class="control"></label>
                            <label class="field"><span>Date of Birth *</span><input type="date" name="dob" id="dob" value="{{ $dateValue('dob') }}" class="control"></label>
                            <label class="field"><span>Gender *</span><select name="gender" class="control"><option value="">Select</option>@foreach ($genders as $gender)<option value="{{ $gender }}" @selected($value('gender') === $gender)>{{ $gender }}</option>@endforeach</select></label>
                            <label class="field"><span>Admission Date *</span><input type="date" name="admission_date" value="{{ $dateValue('admission_date', now()->toDateString()) }}" class="control"></label>
                            <label class="field"><span>B-Form No</span><input name="b_form_no" value="{{ $value('b_form_no') }}" class="control"></label>
                            <label class="field"><span>Mobile</span><input name="mobileno" value="{{ $value('mobileno') }}" class="control"></label>
                            <label class="field"><span>Email</span><input type="email" name="email" value="{{ $value('email') }}" class="control"></label>
                            <label class="field"><span>Religion</span><select name="religion_id" class="control"><option value="">Select</option>@foreach ($religions as $item)<option value="{{ $item->id }}" @selected((string) $value('religion_id') === (string) $item->id)>{{ $item->name }}</option>@endforeach</select></label>
                            <label class="field"><span>Medium</span><select name="medium_id" class="control"><option value="">Select</option>@foreach ($mediums as $item)<option value="{{ $item->id }}" @selected((string) $value('medium_id') === (string) $item->id)>{{ $item->name }}</option>@endforeach</select></label>
                            <label class="field"><span>Previous School</span><input name="pervious_school_id" value="{{ $value('previous_school_id') }}" class="control"></label>
                            <label class="field"><span>Previous Class</span><input name="class_left" value="{{ $value('pervious_class') }}" class="control"></label>
                            <label class="field md:col-span-2"><span>Current Address</span><textarea name="current_address" class="control">{{ $value('current_address') }}</textarea></label>
                            <label class="field md:col-span-2"><span>Permanent Address</span><textarea name="permanent_address" class="control">{{ $value('permanent_address') }}</textarea></label>
                            <label class="field"><span>Student Photo</span><input type="file" name="file" accept="image/*" class="control"></label>
                        </div>
                    </div>

                    <div class="rounded border border-neutral-200 p-4">
                        <h2 class="mb-4 border-b border-neutral-200 pb-2 text-lg font-semibold text-neutral-800">Parent / Guardian Information</h2>
                        <div class="grid gap-3 md:grid-cols-2">
                            <label class="field"><span>Father Name *</span><input name="father_name" value="{{ $value('father_name') }}" class="control"></label>
                            <label class="field"><span>Father Phone *</span><input name="father_phone" value="{{ $value('father_phone') }}" class="control"></label>
                            <label class="field"><span>Father Occupation</span><input name="father_occupation" value="{{ $value('father_occupation') }}" class="control"></label>
                            <label class="field"><span>Father Education</span><input name="father_education_id" value="{{ $value('father_education_id') }}" class="control"></label>
                            <label class="field"><span>Mother Name</span><input name="mother_name" value="{{ $value('mother_name') }}" class="control"></label>
                            <label class="field"><span>Mother Phone</span><input name="mother_phone" value="{{ $value('mother_phone') }}" class="control"></label>
                            <label class="field"><span>Guardian Is *</span><select name="guardian_is" class="control"><option value="">Select</option><option value="father" @selected($value('guardian_is') === 'father')>Father</option><option value="mother" @selected($value('guardian_is') === 'mother')>Mother</option><option value="other" @selected($value('guardian_is') === 'other')>Other</option></select></label>
                            <label class="field"><span>Guardian Name *</span><input name="guardian_name" value="{{ $value('guardian_name') }}" class="control"></label>
                            <label class="field"><span>Guardian Relation</span><input name="guardian_relation" value="{{ $value('guardian_relation') }}" class="control"></label>
                            <label class="field"><span>Guardian Phone *</span><input name="guardian_phone" value="{{ $value('guardian_phone') }}" class="control"></label>
                            <label class="field"><span>Guardian Email</span><input type="email" name="guardian_email" value="{{ $value('guardian_email') }}" class="control"></label>
                            <label class="field md:col-span-2"><span>Guardian Address</span><textarea name="guardian_address" class="control">{{ $value('guardian_address') }}</textarea></label>
                            <label class="field"><span>Guardian Photo</span><input type="file" name="guardian_pic" accept="image/*" class="control"></label>
                        </div>
                    </div>
                </div>

                <div class="rounded border border-neutral-200 p-4">
                    <h2 class="mb-4 border-b border-neutral-200 pb-2 text-lg font-semibold text-neutral-800">Admission Fees</h2>
                    <div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="bg-neutral-100 text-left"><th class="p-2">Fee Type</th><th class="p-2">Frequency</th><th class="p-2">Fee Amount</th><th class="p-2">Current Amount</th><th class="p-2">Action</th></tr></thead><tbody id="fee-rows">@foreach ($feeRows as $index => $row)<tr data-fee-row><td class="p-2"><select name="fee_rows[{{ $index }}][feetype_id]" class="control"><option value="">Select</option>@foreach ($feeHeads as $head)<option value="{{ $head->id }}" @selected((string) data_get($row, 'feetype_id') === (string) $head->id)>{{ $head->name }}</option>@endforeach</select></td><td class="p-2"><input name="fee_rows[{{ $index }}][frequency]" value="{{ data_get($row, 'frequency', 'Monthly') }}" class="control"></td><td class="p-2"><input type="number" step="0.01" name="fee_rows[{{ $index }}][fee_amount]" value="{{ data_get($row, 'fee_amount') }}" class="control"></td><td class="p-2"><input type="number" step="0.01" name="fee_rows[{{ $index }}][current_amount]" value="{{ data_get($row, 'current_amount') }}" class="control"></td><td class="p-2"><button type="button" class="remove-fee rounded bg-red-600 px-2 py-1 text-white">×</button></td></tr>@endforeach</tbody></table></div>
                    <button type="button" id="add-fee" class="mt-3 rounded bg-[#26408d] px-3 py-2 text-sm font-semibold text-white">+ Add Fee</button>
                    <div class="mt-3"><label class="field"><span>Fee Mode</span><select name="fee_mode" class="control"><option value="monthly">Monthly</option><option value="installments">Installments</option></select></label></div>
                </div>

                    <div class="rounded border border-neutral-200 p-4"><h2 class="mb-4 border-b border-neutral-200 pb-2 text-lg font-semibold text-neutral-800">Dates and Documents</h2><div class="grid gap-3 md:grid-cols-3"><label class="field"><span>Issue Date *</span><input type="date" name="issue_date" value="{{ $dateValue('issue_date', now()->toDateString()) }}" class="control"></label><label class="field"><span>Due Date *</span><input type="date" name="due_date" value="{{ $dateValue('due_date', now()->toDateString()) }}" class="control"></label><label class="field"><span>Receiving Date *</span><input type="date" name="receiving_date" value="{{ $dateValue('receiving_date', now()->toDateString()) }}" class="control"></label><label class="field"><span>Document Title</span><input name="document_title" class="control"></label><label class="field"><span>Document</span><input type="file" name="document" class="control"></label></div></div>

                @if ($errors->any())<div class="rounded border border-red-300 bg-red-50 p-4 text-red-800"><ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                <div class="flex justify-end"><button type="submit" class="rounded bg-[#11a958] px-6 py-3 font-semibold text-white">{{ $student ? 'Update Admission' : 'Save Admission' }}</button></div>
            </form>
        </div>

        <div id="admission-list" class="border-t border-neutral-200 p-4">
            <div class="mb-3 flex items-center justify-between"><h2 class="text-lg font-semibold">Recently Added Students</h2><form method="GET"><input name="search" value="{{ request('search') }}" placeholder="Search admission/name/father" class="control"><button class="rounded bg-[#26408d] px-3 py-2 text-white">Search</button></form></div>
            <div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="bg-[#26408d] text-left text-white"><th class="p-3">Admission No</th><th class="p-3">Name</th><th class="p-3">Father Name</th><th class="p-3">Mobile</th><th class="p-3">Action</th></tr></thead><tbody>@forelse ($students as $item)<tr class="border-b bg-red-50"><td class="p-3">{{ $item->admission_no }}</td><td class="p-3">{{ trim($item->firstname.' '.$item->middlename.' '.$item->lastname) }}</td><td class="p-3">{{ $item->father_name }}</td><td class="p-3">{{ $item->mobileno }}</td><td class="p-3"><a href="{{ route('admin.adm.student-admissions.edit', $item) }}" class="rounded bg-blue-700 px-2 py-1 text-white">Edit</a><form method="POST" action="{{ route('admin.adm.student-admissions.destroy', $item) }}" class="inline" onsubmit="return confirm('Delete this student?')">@csrf @method('DELETE')<button class="ml-1 rounded bg-red-600 px-2 py-1 text-white">Delete</button></form></td></tr>@empty<tr><td colspan="5" class="p-6 text-center">No student admission found.</td></tr>@endforelse</tbody></table></div><div class="mt-3">{{ $students->links() }}</div>
        </div>
    </section>
@endsection

@push('styles')
<style>.field{display:flex;flex-direction:column;gap:.35rem;font-size:.875rem;font-weight:600;color:#374151}.control{width:100%;border:1px solid #d1d5db;border-radius:.25rem;background:white;padding:.65rem .75rem;font-weight:400;color:#374151}.field textarea{min-height:76px}</style>
@endpush

@push('scripts')
<script>
(() => {
    const form = document.getElementById('student-admission-form');
    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section_id');
    const regdSelect = document.getElementById('regd_id');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const setValue = (id, value) => { const field = document.getElementById(id); if (field && value !== null && value !== undefined) field.value = value; };

    classSelect?.addEventListener('change', () => {
        if (!classSelect.value) return;
        fetch(`{{ route('admin.adm.student-admissions.class-sections', absolute: false) }}?class_id=${encodeURIComponent(classSelect.value)}`, {headers: {'Accept': 'application/json'}})
            .then(response => response.json()).then(options => { sectionSelect.innerHTML = '<option value="">Select</option>'; options.forEach(option => sectionSelect.insertAdjacentHTML('beforeend', `<option value="${option.id}">${option.section}</option>`)); });
    });

    regdSelect?.addEventListener('change', () => {
        if (!regdSelect.value) return;
        fetch(`{{ route('admin.adm.student-admissions.registration-detail', absolute: false) }}?regd_id=${encodeURIComponent(regdSelect.value)}`, {headers: {'Accept': 'application/json'}})
            .then(response => response.json()).then(record => { if (!record.id) return; setValue('firstname', record.firstname); setValue('father_name', record.father_name); setValue('father_phone', record.father_phone); setValue('father_cnic', record.father_cnic); setValue('guardian_name', record.guardian_name || record.father_name); setValue('guardian_phone', record.guardian_phone || record.father_phone); setValue('dob', record.dob); setValue('gender', record.gender); setValue('class_id', record.class_id); setValue('session_id', record.session_id); });
    });

    document.getElementById('add-fee')?.addEventListener('click', () => { const rows = document.querySelectorAll('[data-fee-row]').length; const row = document.querySelector('[data-fee-row]').cloneNode(true); row.querySelectorAll('input,select').forEach(field => { field.name = field.name.replace(/fee_rows\[\d+\]/, `fee_rows[${rows}]`); if (field.tagName === 'INPUT') field.value = ''; }); document.getElementById('fee-rows').appendChild(row); });
    document.addEventListener('click', event => { if (event.target.matches('.remove-fee')) { const rows = document.querySelectorAll('[data-fee-row]'); if (rows.length > 1) event.target.closest('[data-fee-row]').remove(); } });
    document.getElementById('print-admission-form')?.addEventListener('click', () => { const copy = form.cloneNode(true); copy.querySelectorAll('button,input[type=file]').forEach(element => element.remove()); const win = window.open('', '_blank'); win.document.write(`<title>Student Admission Form</title><style>body{font:14px Arial;padding:20px}input,select,textarea{border:0;border-bottom:1px solid #999;margin:4px;padding:4px;width:45%}</style>${copy.outerHTML}`); win.document.close(); win.print(); });
})();
</script>
@endpush
