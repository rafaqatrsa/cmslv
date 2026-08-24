@extends('admin.layouts.app')

@section('title', 'Transfer/Class-section')

@section('content')
    @include('admin.adm.partials.nav')

    <section class="transfer-page mt-4 overflow-hidden rounded-xl border border-[#d8d8d8] bg-white shadow-[0_2px_8px_rgba(15,23,42,.12)]">
        <div class="border-b border-[#d8d8d8] px-4 py-3"><h1 class="text-[21px] font-normal text-[#313131]"><i class="fa-solid fa-magnifying-glass mr-2"></i>Select Criteria</h1></div>
        <form method="GET" action="{{ route('admin.adm.student-transfers.index') }}" class="grid gap-4 px-4 py-5 md:grid-cols-3">
            <label class="transfer-field"><span>Branch <b>*</b></span><select name="brc_id" class="transfer-control"><option value="">Select</option>@foreach ($branches as $branch)<option value="{{ $branch->id }}" @selected((string) $selectedBranch === (string) $branch->id)>{{ $branch->name }}</option>@endforeach</select></label>
            <label class="transfer-field"><span>Class <b>*</b></span><select name="class_id" id="source-class" class="transfer-control"><option value="">Select</option>@foreach ($classes as $class)<option value="{{ $class->id }}" @selected((string) $selectedClass === (string) $class->id)>{{ $class->class }}</option>@endforeach</select></label>
            <label class="transfer-field"><span>Section <b>*</b></span><select name="section_id" id="source-section" class="transfer-control" data-selected="{{ $selectedSection }}"><option value="">Select</option></select></label>
            <div class="flex justify-end md:col-span-3"><button type="submit" class="transfer-button"><i class="fa-solid fa-search mr-2"></i>Search</button></div>
        </form>
    </section>

    @if ($searched)
        <section class="transfer-page mt-3 overflow-hidden rounded-xl border border-[#d8d8d8] bg-white shadow-[0_2px_8px_rgba(15,23,42,.12)]">
            <div class="border-b border-[#e6e6e6] px-4 py-3"><h2 class="text-[21px] font-normal text-[#313131]"><i class="fa-solid fa-list mr-2"></i>Transfer/Class-section</h2></div>
            <form class="transfer-form" data-transfer-form method="POST" action="{{ route('admin.adm.student-transfers.transfer') }}">
                @csrf
                <input type="hidden" name="source_brc_id" value="{{ $selectedBranch }}"><input type="hidden" name="source_session_id" value="{{ $sourceSessionId }}"><input type="hidden" name="source_class_id" value="{{ $selectedClass }}"><input type="hidden" name="source_section_id" value="{{ $selectedSection }}">
                <div class="grid gap-4 border-b px-4 py-5 md:grid-cols-4">
                    <label class="transfer-field"><span>Transfer Branch <b>*</b></span><select name="brc_transfer_id" class="transfer-control"><option value="">Current branch</option>@foreach ($branches as $branch)<option value="{{ $branch->id }}" @selected((string) $branchId === (string) $branch->id)>{{ $branch->name }}</option>@endforeach</select></label>
                    <label class="transfer-field"><span>Transfer in Session <b>*</b></span><select name="session_id" class="transfer-control"><option value="">Select</option>@foreach ($sessions as $session)<option value="{{ $session->id }}" @selected((int) $sourceSessionId === (int) $session->id)>{{ $session->session }}</option>@endforeach</select></label>
                    <label class="transfer-field"><span>Class <b>*</b></span><select name="class_transfer_id" id="target-class" class="transfer-control"><option value="">Select</option>@foreach ($classes as $class)<option value="{{ $class->id }}">{{ $class->class }}</option>@endforeach</select></label>
                    <label class="transfer-field"><span>Section <b>*</b></span><select name="section_transfer_id" id="target-section" class="transfer-control"><option value="">Select</option></select></label>
                </div>
                <div class="border-b px-4 py-4"><span class="font-semibold">Fee Mode <b class="text-red-600">*</b></span><label class="ml-5"><input type="radio" name="fee_transfer_mode" value="old_fees" checked> Old Fees</label><label class="ml-5"><input type="radio" name="fee_transfer_mode" value="next_class_fee"> Next Class Fee</label></div>
                <div class="overflow-x-auto px-4 py-4"><table class="transfer-table"><thead><tr><th><input type="checkbox" data-select-all></th><th>Admission No</th><th>Class</th><th>Student Name</th><th>Father Name</th><th>Date of Birth</th><th>Gender</th></tr></thead><tbody>@forelse ($students as $student)<tr><td><input type="checkbox" class="student-check" name="check[]" value="{{ $student->student_session_id }}"></td><td>{{ $student->admission_no }}</td><td>{{ $student->class }} ({{ $student->section }})</td><td><a href="{{ route('admin.adm.students.show', $student->id) }}">{{ trim($student->firstname.' '.$student->lastname) }}</a></td><td>{{ $student->father_name }}</td><td>{{ $student->dob?->format('Y-m-d') }}</td><td>{{ $student->gender }}</td></tr>@empty<tr><td colspan="7" class="py-8 text-center text-red-600">No record found</td></tr>@endforelse</tbody></table><p class="transfer-error" data-transfer-error></p></div>
                @if ($students->isNotEmpty())<div class="flex justify-end border-t px-4 py-4"><button type="button" class="transfer-button" data-open-transfer><i class="fa-solid fa-right-left mr-2"></i>Transfer</button></div>@endif
            </form>
        </section>
    @endif

    <div class="transfer-modal hidden" data-transfer-modal><div class="transfer-dialog"><div class="flex items-center justify-between border-b px-5 py-4"><h3 class="text-lg font-semibold">Transfer Confirmation</h3><button type="button" data-close-transfer class="text-2xl">&times;</button></div><div class="px-5 py-6">Are you sure you want to transfer selected students?</div><div class="flex justify-end gap-2 border-t px-5 py-4"><button type="button" data-close-transfer class="transfer-cancel">Cancel</button><button type="button" data-confirm-transfer class="transfer-button">Save</button></div></div></div>
@endsection

@push('styles')
<style>
.transfer-field{display:grid;gap:.35rem;color:#333;font-size:15px;font-weight:600}.transfer-field b{color:#dc2626}.transfer-control{width:100%;border:1px solid #cfcfcf;border-radius:4px;background:#fff;padding:.7rem .8rem;font-size:15px;outline:0}.transfer-control:focus{border-color:#26408d;box-shadow:0 0 0 2px #26408d22}.transfer-button,.transfer-cancel{border:0;border-radius:4px;padding:.65rem 1rem;cursor:pointer;transition:transform .15s ease,filter .15s ease}.transfer-button{background:#26408d;color:#fff}.transfer-cancel{background:#e5e7eb;color:#333}.transfer-button:hover,.transfer-cancel:hover{filter:brightness(1.1);transform:translateY(-1px)}.transfer-table{width:100%;min-width:850px;border-collapse:separate;border-spacing:0;font-size:14px}.transfer-table th{background:#26408d;color:#fff;padding:.8rem;text-align:left}.transfer-table td{border-bottom:1px solid #e7cfcf;background:#f7e3e3;padding:.75rem}.transfer-table a{color:#26408d;text-decoration:underline}.transfer-error{color:#dc2626}.transfer-modal{position:fixed;inset:0;z-index:50;display:flex;align-items:center;justify-content:center;background:#0008;padding:1rem}.transfer-modal.hidden{display:none}.transfer-dialog{width:min(500px,100%);border-radius:8px;background:#fff;box-shadow:0 12px 30px #0004}.transfer-page button,.transfer-page select,.transfer-page input,.transfer-modal button{cursor:pointer}
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/student-transfer.js') }}"></script>
@endpush
