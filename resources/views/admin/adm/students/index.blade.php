@extends('admin.layouts.app')

@section('title', 'Student Search')

@section('content')
    @include('admin.adm.partials.nav')

    <section class="mt-4 overflow-hidden rounded-xl border border-[#d8d8d8] bg-white shadow-[0_2px_8px_rgba(15,23,42,.12)]">
        <div class="border-b border-[#d8d8d8] px-4 py-3"><h1 class="text-[21px] font-normal text-[#313131]"><i class="fa-solid fa-user-plus mr-2"></i>Select Criteria</h1></div>
        <div class="px-4 py-4">
            <div class="grid gap-6 lg:grid-cols-2">
                <form method="GET" action="{{ route('admin.adm.students.search') }}" class="grid gap-3 sm:grid-cols-3">
                    <input type="hidden" name="session_id" value="{{ $selectedSession }}">
                    <label class="space-y-1"><span class="font-semibold">Branch <b class="text-red-600">*</b></span><select name="brc_id" class="directory-control"><option value="">Select</option>@foreach ($branches as $branch)<option value="{{ $branch->id }}" @selected((string) $selectedBranch === (string) $branch->id)>{{ $branch->name }}</option>@endforeach</select></label>
                    <label class="space-y-1"><span class="font-semibold">Class <b class="text-red-600">*</b></span><select id="directory-class" name="class_id" class="directory-control"><option value="">Select</option>@foreach ($classes as $class)<option value="{{ $class->id }}" @selected((string) $selectedClass === (string) $class->id)>{{ $class->class }}</option>@endforeach</select></label>
                    <label class="space-y-1"><span class="font-semibold">Section</span><select id="directory-section" name="section_id" data-selected="{{ $selectedSection }}" class="directory-control"><option value="">Select</option></select></label>
                    <div class="sm:col-span-3 flex justify-end"><button class="directory-primary" type="submit"><i class="fa-solid fa-magnifying-glass mr-2"></i>Search</button></div>
                </form>

                <form method="GET" action="{{ route('admin.adm.students.search') }}" class="grid gap-3 sm:grid-cols-3">
                    <input type="hidden" name="session_id" value="{{ $selectedSession }}">
                    <label class="space-y-1 sm:col-span-1"><span class="font-semibold">Branch <b class="text-red-600">*</b></span><select name="brc_id" class="directory-control"><option value="">Select</option>@foreach ($branches as $branch)<option value="{{ $branch->id }}" @selected((string) $selectedBranch === (string) $branch->id)>{{ $branch->name }}</option>@endforeach</select></label>
                    <label class="space-y-1 sm:col-span-2"><span class="font-semibold">Search By Keyword <b class="text-red-600">*</b></span><select name="adm_student_id" class="directory-control"><option value="">Search By Student Name</option>@foreach ($studentOptions as $option)<option value="{{ $option->student_id }}" @selected((string) $selectedStudent === (string) $option->student_id)>{{ $option->admission_no }} - {{ trim($option->firstname.' '.$option->lastname) }} {{ $option->father_name }}</option>@endforeach</select></label>
                    <div class="sm:col-span-3 flex justify-end"><button class="directory-primary" type="submit"><i class="fa-solid fa-magnifying-glass mr-2"></i>Search</button></div>
                </form>
            </div>
        </div>
    </section>

    <section class="mt-1 overflow-hidden rounded-xl border border-[#d8d8d8] bg-white shadow-[0_2px_8px_rgba(15,23,42,.12)]">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#e6e6e6] px-4 py-3"><h2 class="text-[22px] font-normal text-[#313131]">{{ $searched ? 'Student Details' : 'Students Directory' }}</h2><div class="flex flex-wrap gap-2" data-directory-tools><button data-export="copy" class="directory-tool" title="Copy"><i class="fa-regular fa-copy"></i></button><button data-export="csv" class="directory-tool" title="CSV"><i class="fa-regular fa-file-lines"></i></button><button data-export="print" class="directory-tool" title="Print"><i class="fa-solid fa-print"></i></button><button data-export="columns" class="directory-tool" title="Column visibility"><i class="fa-solid fa-table-columns"></i></button></div></div>
        <div class="border-b border-[#e6e6e6] px-4 pt-3"><button type="button" data-tab="list" class="directory-tab directory-tab-active">List View</button><button type="button" data-tab="details" class="directory-tab">Details View</button></div>
        <div class="overflow-x-auto" data-directory-panel="list"><table id="student-directory-table" class="w-full min-w-[1300px] border-separate border-spacing-0 text-[14px]"><thead><tr class="bg-[#26408d] text-white"><th class="directory-th">Branch</th><th class="directory-th">Sibling Code</th><th class="directory-th">Admit No</th><th class="directory-th">Roll No</th><th class="directory-th">Class</th><th class="directory-th">Student</th><th class="directory-th">Father</th><th class="directory-th">Date of Birth</th><th class="directory-th">Gender</th><th class="directory-th">Category</th><th class="directory-th">Phone</th><th class="directory-th">Status</th><th class="directory-th text-right">Action</th></tr></thead><tbody>@forelse ($records as $record)<tr class="{{ $record->is_active !== 'yes' ? 'directory-disabled' : '' }}"><td class="directory-td">{{ $record->branch_name ?: 'N/A' }}</td><td class="directory-td">{{ $record->sibling_code ?: 'N/A' }}</td><td class="directory-td">{{ $record->admission_no ?: 'N/A' }}</td><td class="directory-td">{{ $record->roll_no ?: 'N/A' }}</td><td class="directory-td">{{ $record->class_name ?: 'N/A' }} ({{ $record->section_name ?: '-' }})</td><td class="directory-td"><a class="directory-link" href="{{ route('admin.adm.students.show', ['student' => $record->id, 'brc_id' => $record->session_brc_id]) }}">{{ trim($record->firstname.' '.$record->lastname) }}</a></td><td class="directory-td">{{ $record->father_name ?: 'N/A' }}</td><td class="directory-td">{{ $record->dob?->format('Y-m-d') ?: 'N/A' }}</td><td class="directory-td">{{ $record->gender ?: 'N/A' }}</td><td class="directory-td">{{ $record->category_name ?: 'N/A' }}</td><td class="directory-td">{{ $record->sibling_phone ?: ($record->mobileno ?: 'N/A') }}</td><td class="directory-td">{{ $record->is_active === 'yes' ? 'Active' : 'Disabled' }}</td><td class="directory-td whitespace-nowrap text-right"><a class="directory-action directory-view" title="Show" href="{{ route('admin.adm.students.show', ['student' => $record->id, 'brc_id' => $record->session_brc_id]) }}"><i class="fa-solid fa-reorder"></i></a><a class="directory-action directory-edit" title="Edit" href="{{ route('admin.adm.student-admissions.edit', $record->id) }}"><i class="fa-solid fa-pencil"></i></a><form class="inline" method="POST" action="{{ route('admin.adm.student-admissions.destroy', $record->id) }}" onsubmit="return confirm('Are you sure you want to delete this record.')">@csrf @method('DELETE')<button class="directory-action directory-delete" title="Delete"><i class="fa-solid fa-trash"></i></button></form></td></tr>@empty<tr><td colspan="13" class="px-4 py-10 text-center text-[#6b7280]">{{ $searched ? 'No record found.' : 'Select criteria and search to view students.' }}</td></tr>@endforelse</tbody></table></div>
        <div class="hidden px-4 py-4" data-directory-panel="details">@forelse ($records as $record)<article class="directory-card"><img src="{{ $record->image ? asset('storage/'.$record->image) : asset('assets/images/no_image.png') }}" alt="{{ $record->firstname }}" class="h-24 w-24 rounded border object-cover"><div class="flex-1"><h3><a class="directory-link" href="{{ route('admin.adm.students.show', $record->id) }}">{{ trim($record->firstname.' '.$record->lastname) }}</a></h3><p>Branch: {{ $record->branch_name }} · Class: {{ $record->class_name }} ({{ $record->section_name }})</p><p>Admission No: {{ $record->admission_no }} · Father: {{ $record->father_name }} · Phone: {{ $record->sibling_phone ?: $record->mobileno }}</p></div></article>@empty<p class="py-8 text-center text-[#6b7280]">{{ $searched ? 'No record found.' : 'Select criteria and search to view students.' }}</p>@endforelse</div>
        <div class="border-t border-[#e6e6e6] px-4 py-3">{{ $records->links() }}</div>
    </section>
@endsection

@push('styles')
<style>
    .directory-control{width:100%;border:1px solid #cfcfcf;border-radius:4px;background:#fff;padding:.65rem .8rem;outline:0}.directory-control:focus{border-color:#26408d;box-shadow:0 0 0 2px #26408d22}.directory-primary,.directory-tool,.directory-action{transition:transform .15s ease,filter .15s ease,background .15s ease;cursor:pointer}.directory-primary{border:0;border-radius:4px;background:#26408d;padding:.6rem 1rem;color:#fff}.directory-primary:hover,.directory-tool:hover,.directory-action:hover{filter:brightness(1.12);transform:translateY(-1px)}.directory-tool{border:0;border-radius:4px;background:#26408d;padding:.55rem .7rem;color:#fff}.directory-th{padding:.8rem;border-right:1px solid #ffffff2e;text-align:left;white-space:nowrap}.directory-td{border-right:1px solid #e7cfcf;border-bottom:1px solid #e7cfcf;background:#f7e3e3;padding:.75rem}.directory-disabled .directory-td{background:#f9d6d5;color:#8a1f18}.directory-link{color:#26408d;font-weight:600;text-decoration:none}.directory-action{display:inline-flex;width:30px;height:30px;margin-left:4px;align-items:center;justify-content:center;border:0;border-radius:4px;color:#fff;text-decoration:none}.directory-view{background:#26408d}.directory-edit{background:#26408d}.directory-delete{background:#ef5b4c}.directory-tab{border:0;border-bottom:2px solid transparent;background:transparent;padding:.65rem 1rem;font-weight:600}.directory-tab-active{border-color:#26408d;color:#26408d}.directory-card{display:flex;gap:1rem;border-bottom:1px solid #e7cfcf;padding:1rem}.directory-card h3{margin:0 0 .5rem;font-size:1.1rem}.directory-card p{margin:.25rem 0}
</style>
@endpush

@push('scripts')
<script>
(() => {
    const classSelect = document.querySelector('#directory-class');
    const sectionSelect = document.querySelector('#directory-section');
    const selectedSection = sectionSelect?.dataset.selected || '';
    const loadSections = async () => {
        if (!classSelect || !sectionSelect) return;
        sectionSelect.innerHTML = '<option value="">Select</option>';
        if (!classSelect.value) return;
        const response = await fetch(`{{ route('admin.adm.students.sections') }}?class_id=${encodeURIComponent(classSelect.value)}`, {headers: {'Accept': 'application/json'}});
        if (!response.ok) return;
        const sections = await response.json();
        sections.forEach(section => sectionSelect.add(new Option(section.section, section.id, false, String(section.id) === selectedSection)));
    };
    classSelect?.addEventListener('change', loadSections);
    loadSections();

    document.querySelectorAll('[data-tab]').forEach(tab => tab.addEventListener('click', () => {
        document.querySelectorAll('[data-tab]').forEach(item => item.classList.toggle('directory-tab-active', item === tab));
        document.querySelectorAll('[data-directory-panel]').forEach(panel => panel.classList.toggle('hidden', panel.dataset.directoryPanel !== tab.dataset.tab));
    }));

    const table = document.querySelector('#student-directory-table');
    const rows = () => [...(table?.querySelectorAll('tbody tr') || [])].filter(row => row.querySelector('td'));
    document.querySelectorAll('[data-export]').forEach(button => button.addEventListener('click', async () => {
        const action = button.dataset.export;
        if (action === 'columns') { table?.querySelectorAll('thead th').forEach((th, index) => { const hide = th.dataset.hidden !== '1'; th.dataset.hidden = hide ? '1' : '0'; table.querySelectorAll(`tr > *:nth-child(${index + 1})`).forEach(cell => cell.classList.toggle('hidden', hide)); }); return; }
        if (action === 'print') { window.print(); return; }
        const text = rows().map(row => [...row.cells].map(cell => cell.innerText.trim()).join('\t')).join('\n');
        if (action === 'copy') { await navigator.clipboard?.writeText(text); return; }
        const csv = rows().map(row => [...row.cells].map(cell => `"${cell.innerText.replaceAll('"', '""').trim()}"`).join(',')).join('\n');
        const link = document.createElement('a'); link.href = URL.createObjectURL(new Blob([csv], {type: 'text/csv'})); link.download = 'students-directory.csv'; link.click(); URL.revokeObjectURL(link.href);
    }));
})();
</script>
@endpush
