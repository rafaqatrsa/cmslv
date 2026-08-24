@extends('admin.layouts.app')

@section('title', 'Promote Students')

@section('content')
    @include('admin.adm.partials.nav')

    <section class="promotion-page overflow-hidden rounded-xl border border-[#d8d8d8] bg-white shadow-[0_2px_8px_rgba(15,23,42,.12)]">
        <div class="border-b border-[#d8d8d8] px-4 py-3"><h1 class="text-[21px] font-normal text-[#313131]"><i class="fa-solid fa-magnifying-glass mr-2"></i>Select Criteria</h1></div>
        <form method="GET" action="{{ route('admin.adm.student-promotions.index') }}" class="grid gap-4 px-4 py-5 md:grid-cols-4">
            <label class="promotion-field"><span>Branch <b>*</b></span><select name="brc_id" class="promotion-control"><option value="">Select</option>@foreach ($branches as $branch)<option value="{{ $branch->id }}" @selected((string) $branchId === (string) $branch->id)>{{ $branch->name }}</option>@endforeach</select></label>
            <input type="hidden" name="source_session_id" value="{{ $sourceSessionId }}">
            <label class="promotion-field"><span>Class <b>*</b></span><select name="source_class_id" id="promotion-source-class" class="promotion-control"><option value="">Select</option>@foreach ($classes as $class)<option value="{{ $class->id }}" @selected((string) $sourceClassId === (string) $class->id)>{{ $class->class }}</option>@endforeach</select></label>
            <label class="promotion-field"><span>Section</span><select name="source_section_id" id="promotion-source-section" class="promotion-control" data-selected="{{ $sourceSectionId }}"><option value="">Select</option>@foreach ($sections as $section)<option value="{{ $section->id }}" @selected((string) $sourceSectionId === (string) $section->id)>{{ $section->section }}</option>@endforeach</select></label>
            <div class="flex items-end"><button type="submit" class="promotion-button"><i class="fa-solid fa-search mr-2"></i>Search</button></div>
        </form>
    </section>

    <section id="promotion-table" class="mt-3 overflow-hidden rounded-xl border border-[#d8d8d8] bg-white shadow-[0_2px_8px_rgba(15,23,42,.12)]">
        <div class="border-b border-[#e6e6e6] px-4 py-3"><h2 class="text-[21px] font-normal text-[#313131]"><i class="fa-solid fa-list mr-2"></i>Promote Students in Next Session</h2></div>
        <form method="POST" action="{{ route('admin.adm.student-promotions.store') }}" data-promotion-form>
            @csrf
            <input type="hidden" name="brc_id_post" value="{{ $branchId }}"><input type="hidden" name="source_session_id" value="{{ $sourceSessionId }}"><input type="hidden" name="class_post" value="{{ $sourceClassId }}"><input type="hidden" name="section_post" value="{{ $sourceSectionId }}">
            <div class="grid gap-4 border-b px-4 py-5 md:grid-cols-3">
                <label class="promotion-field"><span>Promote in Session <b>*</b></span><select name="session_id" class="promotion-control"><option value="">Select</option>@foreach ($sessions as $session)<option value="{{ $session->id }}" @selected((string) $targetSessionId === (string) $session->id)>{{ $session->session }}</option>@endforeach</select></label>
                <label class="promotion-field"><span>Class <b>*</b></span><select name="class_promote_id" id="promotion-target-class" class="promotion-control"><option value="">Select</option>@foreach ($classes as $class)<option value="{{ $class->id }}" @selected((string) $targetClassId === (string) $class->id)>{{ $class->class }}</option>@endforeach</select></label>
                <label class="promotion-field"><span>Section</span><select name="section_promote_id" id="promotion-target-section" class="promotion-control" data-selected="{{ $targetSectionId }}"><option value="">Select</option>@foreach ($sections as $section)<option value="{{ $section->id }}" @selected((string) $targetSectionId === (string) $section->id)>{{ $section->section }}</option>@endforeach</select></label>
            </div>
            <div class="border-b px-4 py-4"><span class="font-semibold">Fee Promotion Option <b class="text-red-600">*</b></span><div class="mt-2 flex flex-wrap gap-4"><label><input type="radio" name="fee_promotion_mode" value="previous_discount" checked> Previous Disc.</label><label><input type="radio" name="fee_promotion_mode" value="full_fees"> Full Fees (Tuition Fees)</label><label><input type="radio" name="fee_promotion_mode" value="increment_previous_tuition_fee_amount"> Increment Amount</label><label><input type="radio" name="fee_promotion_mode" value="increment_previous_tuition_fee_percentage"> Increment %</label></div></div>
            <div class="hidden border-b px-4 py-4" data-promotion-amount><label class="promotion-field max-w-sm"><span>Increment Amount <b>*</b></span><input type="number" min="0" step="0.01" name="promotion_increment_amount" class="promotion-control" placeholder="Enter amount"></label></div>
            <div class="hidden border-b px-4 py-4" data-promotion-percentage><label class="promotion-field max-w-sm"><span>Increment Percentage <b>*</b></span><input type="number" min="0" step="0.01" name="promotion_increment_percentage" class="promotion-control" placeholder="Enter percentage"></label></div>
            <div class="overflow-x-auto px-4 py-4"><table class="promotion-table"><thead><tr><th><input type="checkbox" data-promotion-select-all checked></th><th>Admission No</th><th>Student Name</th><th>Father Name</th><th>Date of Birth</th><th>Current Result</th><th>Next Session Status</th></tr></thead><tbody>@forelse ($records as $record)<tr><td><input type="checkbox" class="promotion-check" name="check[]" value="{{ $record->student->id }}" checked></td><td>{{ $record->student->admission_no }}</td><td>{{ trim($record->student->firstname.' '.$record->student->lastname) }}</td><td>{{ $record->student->father_name }}</td><td>{{ $record->student->dob?->format('Y-m-d') }}</td><td><label><input type="radio" name="result[{{ $record->student->id }}]" value="pass" checked> Pass</label><label class="ml-3"><input type="radio" name="result[{{ $record->student->id }}]" value="fail"> Fail</label></td><td><label><input type="radio" name="next_working[{{ $record->student->id }}]" value="countinue" checked> Continue</label><label class="ml-3"><input type="radio" name="next_working[{{ $record->student->id }}]" value="leave"> Leave</label></td></tr>@empty<tr><td colspan="7" class="py-10 text-center text-red-600">No record found</td></tr>@endforelse</tbody></table><p class="promotion-error" data-promotion-error></p></div>
            @if ($records->isNotEmpty())<div class="flex justify-end border-t px-4 py-4"><button type="button" class="promotion-button" data-open-promotion><i class="fa-solid fa-person-walking-arrow-right mr-2"></i>Promote</button></div>@endif
        </form>
    </section>

    <div class="promotion-modal hidden" data-promotion-modal><div class="promotion-dialog"><div class="flex items-center justify-between border-b px-5 py-4"><h3 class="text-lg font-semibold">Promotion Confirmation</h3><button type="button" data-close-promotion class="text-2xl">&times;</button></div><div class="px-5 py-6">Are you sure you want to promote selected students?</div><div class="flex justify-end gap-2 border-t px-5 py-4"><button type="button" data-close-promotion class="promotion-cancel">Cancel</button><button type="submit" form="promotion-confirm-form" class="promotion-button" data-confirm-promotion>Save</button></div></div></div>
@endsection

@push('styles')
<style>
.promotion-field{display:grid;gap:.35rem;color:#333;font-size:15px;font-weight:600}.promotion-field b{color:#dc2626}.promotion-control{width:100%;border:1px solid #cfcfcf;border-radius:4px;background:#fff;padding:.68rem .8rem;font-size:15px;outline:0}.promotion-control:focus{border-color:#26408d;box-shadow:0 0 0 2px #26408d22}.promotion-button,.promotion-cancel{border:0;border-radius:4px;padding:.65rem 1rem;cursor:pointer;transition:transform .15s ease,filter .15s ease}.promotion-button{background:#26408d;color:#fff}.promotion-cancel{background:#e5e7eb;color:#333}.promotion-button:hover,.promotion-cancel:hover{filter:brightness(1.1);transform:translateY(-1px)}.promotion-table{width:100%;min-width:950px;border-collapse:separate;border-spacing:0;font-size:14px}.promotion-table th{background:#26408d;color:#fff;padding:.8rem;text-align:left}.promotion-table td{border-bottom:1px solid #e7cfcf;background:#f7e3e3;padding:.75rem;vertical-align:top}.promotion-table tr:nth-child(even) td{background:#f4dddd}.promotion-error{color:#dc2626}.promotion-modal{position:fixed;inset:0;z-index:50;display:flex;align-items:center;justify-content:center;background:#0008;padding:1rem}.promotion-modal.hidden{display:none}.promotion-dialog{width:min(500px,100%);border-radius:8px;background:#fff;box-shadow:0 12px 30px #0004}
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/student-promotion.js') }}"></script>
@endpush
