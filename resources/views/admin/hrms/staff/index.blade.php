@extends('admin.layouts.app')

@section('title', 'Staff')

@php
    $categoryOptions = [
        'staff_id' => 'Staff ID',
        'name' => 'Name',
        'role' => 'Role',
    ];
@endphp

@push('styles')
    <style>
        .staffinfo-box {
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            border: 1px solid #e4e4e4;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            border-radius: 2px;
            min-height: 100px;
            padding: 10px;
            background: #fff;
        }

        .staffleft-box {
            float: left;
            padding-right: 10px;
        }

        .staffleft-box img {
            width: 100px;
            height: 100px;
            position: relative;
            z-index: 1;
            background: #fff;
            border: 1px solid #ececec;
        }

        .staffleft-content {
            overflow: hidden;
            position: relative;
            min-height: 100px;
        }

        .staffinfo-box h5 {
            display: block;
            margin-top: 5px;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 700;
            color: #111;
        }

        .staffinfo-box p {
            margin-bottom: 1px;
            display: block;
            font-size: 10px;
            line-height: normal;
            color: #222;
        }

        .staffsub {
            padding-top: 4px;
            display: inline-block;
        }

        .staffinfo-box p span {
            background-color: #e2e2e2;
            border: 1px solid #c3c3c3;
            border-radius: 2px;
            padding: 2px 3px;
            text-align: center;
            color: #424242;
            line-height: 18px;
            font-size: 10px;
        }

        .overlay3 {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 110px;
            right: 0;
            height: 100%;
            width: calc(100% - 110px);
            opacity: 0;
            transition: .4s ease-in-out;
            background-color: rgba(0, 0, 0, 0.69);
            z-index: 9;
        }

        .staffinfo-box:hover .overlay3 {
            opacity: 1;
        }

        .stafficons {
            display: block;
            text-align: center;
            padding-left: 0;
            line-height: 100px;
        }

        .stafficons a {
            display: inline-block;
            text-align: center;
            color: #fff;
            font-size: 18px;
            padding: 0 8px;
            text-decoration: none;
        }

        .stafficons a:hover {
            color: #d7d7d7;
        }

        @media (max-width: 767px) {
            .overlay3 {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <section class="mb-3 overflow-hidden rounded-md border border-[#d8dce5] bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-[#d8dce5] px-4 py-2.5">
            <h2 class="text-[14px] font-medium text-[#222]">Select Criteria</h2>
            <a href="{{ route('admin.hrms.staff.create', ['branchId' => $selectedBranchId], false) }}" class="inline-flex items-center gap-1 bg-[#264796] px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-[#1f3b7d]">
                <i class="fa-solid fa-plus text-[10px]"></i>
                Add Staff
            </a>
        </div>

        <div class="px-4 py-3">
            <form method="GET" action="{{ route('admin.hrms.staff.index', absolute: false) }}" class="grid gap-3 xl:grid-cols-[1.05fr_1.05fr_1fr_1.6fr]">
                <div>
                    <label class="mb-1 block text-[10px] font-semibold text-[#222]">Branch <span class="text-red-500">*</span></label>
                    <select name="brc_id" onchange="this.form.submit()" class="h-8 w-full border border-[#cfd6e0] px-2 text-[11px] text-[#333] outline-hidden">
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" @selected($selectedBranchId === (int) $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[10px] font-semibold text-[#222]">Role <span class="text-red-500">*</span></label>
                    <select name="role" class="h-8 w-full border border-[#cfd6e0] px-2 text-[11px] text-[#333] outline-hidden">
                        <option value="">Select</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role['id'] }}" @selected($selectedRoleId === (int) $role['id'])>{{ $role['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[10px] font-semibold text-[#222]">Branch <span class="text-red-500">*</span></label>
                    <select name="brc_id" onchange="this.form.submit()" class="h-8 w-full border border-[#cfd6e0] bg-white px-2 text-[11px] text-[#333] outline-hidden">
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" @selected($selectedBranchId === (int) $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[10px] font-semibold text-[#222]">Search By Keyword <span class="text-red-500">*</span></label>
                    <div class="flex">
                        <select name="selected_value_staff" class="h-8 border border-r-0 border-[#cfd6e0] bg-[#fbfbfc] px-2 text-[11px] text-[#333] outline-hidden">
                            @foreach ($categoryOptions as $value => $label)
                                <option value="{{ $value }}" @selected($selectedSearchField === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <input
                            type="text"
                            name="text_staff"
                            value="{{ $searchText }}"
                            class="h-8 min-w-0 flex-1 border border-[#cfd6e0] px-2 text-[11px] text-[#333] placeholder:text-[#9aa3b2] outline-hidden"
                            placeholder="Search By Staff ID, Name, Father Name..."
                        >
                        <button type="submit" class="h-8 shrink-0 bg-[#264796] px-3 text-[11px] font-semibold text-white hover:bg-[#1f3b7d]">
                            <i class="fa-solid fa-magnifying-glass mr-1 text-[10px]"></i>Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="overflow-hidden rounded-md border border-[#d8dce5] bg-white shadow-sm">
        <div class="border-b border-[#d8dce5]">
            <div class="flex items-center justify-between">
                <div class="flex">
                    <button type="button" data-view-toggle="cards" class="staff-view-toggle border-r border-[#d8dce5] border-b border-[#264796] bg-white px-3 py-2 text-[11px] text-[#264796]">
                        <i class="fa-regular fa-image mr-1"></i>View
                    </button>
                    <button type="button" data-view-toggle="table" class="staff-view-toggle border-r border-[#d8dce5] px-3 py-2 text-[11px] text-[#222]">
                        <i class="fa-solid fa-list mr-1"></i>List View
                    </button>
                </div>
            </div>
        </div>

        <div data-view-panel="cards" class="p-3">
            <div class="grid gap-2 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($records as $record)
                    @php
                        $fullName = trim(implode(' ', array_filter([(string) $record->name, (string) ($record->surname ?? '')]))) ?: '-';
                        $roleName = trim((string) ($record->role_name ?? '')) ?: '-';
                        $designationName = (string) (($record->designation_name ?? '') !== '' ? $record->designation_name : 'Staff');
                        $departmentName = (string) (($record->department_name ?? '') !== '' ? $record->department_name : '-');
                        $contactNo = (string) (($record->contact_no ?? '') !== '' ? $record->contact_no : '-');
                    @endphp
                    <article class="staffinfo-box">
                        <div class="staffleft-box">
                            <img src="{{ asset('uploads/staff_images/no_image.png') }}" alt="No image available">
                        </div>
                        <div class="staffleft-content">
                            <h5>
                                <a href="{{ route('admin.hrms.staff.profile', $record->id, false) }}" class="text-[#111827] hover:text-[#0d6efd] hover:underline">
                                    {{ $fullName }}
                                </a>
                            </h5>
                            <p>{{ $record->employee_id ?: '-' }}</p>
                            <p>{{ $contactNo }}</p>
                            <p>{{ $departmentName }}</p>
                            <p class="staffsub"><span>{{ $roleName }}</span> <span>{{ $designationName }}</span></p>
                            <div class="overlay3">
                                <div class="stafficons">
                                    <a title="Appointment Form" href="{{ route('admin.hrms.staff.appointment-form', $record->id, false) }}"><i class="fa fa-download"></i></a>
                                    <a title="Show" href="{{ route('admin.hrms.staff.profile', $record->id, false) }}"><i class="fa fa-navicon"></i></a>
                                    <a title="Edit" href="{{ route('admin.hrms.staff.edit', $record->id, false) }}"><i class="fa fa-pencil"></i></a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded border border-[#d8dce5] bg-[#f8fafc] px-3 py-4 text-[11px] text-[#475467]">No record found.</div>
                @endforelse
            </div>
        </div>

        <div data-view-panel="table" class="hidden p-3">
            <div class="mb-2 flex items-center justify-between gap-2">
                <input type="text" value="{{ $searchText }}" readonly class="h-7 w-[90px] border border-[#cfd6e0] px-2 text-[11px] text-[#333] outline-hidden" placeholder="Search...">
                <div class="flex items-center gap-1">
                    <a href="{{ route('admin.hrms.staff.appointment-form', $records->first()?->id ?? 0, false) }}" title="Appointment Form" class="inline-flex h-6 w-6 items-center justify-center rounded bg-[#294c9d] text-white {{ $records->count() === 0 ? 'pointer-events-none opacity-40' : '' }}">
                        <i class="fa-solid fa-file-circle-plus text-[10px]"></i>
                    </a>
                    <a href="{{ route('admin.hrms.staff.service-experience-certificate', $records->first()?->id ?? 0, false) }}" title="Service Experience Certificate" class="inline-flex h-6 w-6 items-center justify-center rounded bg-[#294c9d] text-white {{ $records->count() === 0 ? 'pointer-events-none opacity-40' : '' }}">
                        <i class="fa-solid fa-file-pdf text-[10px]"></i>
                    </a>
                    <span title="Export is not available yet" class="inline-flex h-6 w-6 items-center justify-center rounded bg-[#294c9d] text-white/70">
                        <i class="fa-solid fa-print text-[10px]"></i>
                    </span>
                    <span title="Grid options" class="inline-flex h-6 w-6 items-center justify-center rounded bg-[#294c9d] text-white/70">
                        <i class="fa-solid fa-table-cells text-[10px]"></i>
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-[#ccd5e3] text-left text-[11px] text-[#222]">
                    <thead class="bg-[#294996] text-white">
                        <tr>
                            <th class="border border-[#ccd5e3] px-2 py-2">Branch</th>
                            <th class="border border-[#ccd5e3] px-2 py-2">Staff ID</th>
                            <th class="border border-[#ccd5e3] px-2 py-2">Role</th>
                            <th class="border border-[#ccd5e3] px-2 py-2">Name</th>
                            <th class="border border-[#ccd5e3] px-2 py-2">Father Name</th>
                            <th class="border border-[#ccd5e3] px-2 py-2">Date of Birth</th>
                            <th class="border border-[#ccd5e3] px-2 py-2">Date of Joining</th>
                            <th class="border border-[#ccd5e3] px-2 py-2">Department</th>
                            <th class="border border-[#ccd5e3] px-2 py-2">Designation</th>
                            <th class="border border-[#ccd5e3] px-2 py-2">Category</th>
                            <th class="border border-[#ccd5e3] px-2 py-2">Mobile No</th>
                            <th class="border border-[#ccd5e3] px-2 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            @php
                                $fullName = trim(implode(' ', array_filter([(string) $record->name, (string) ($record->surname ?? '')]))) ?: '-';
                                $dob = blank($record->dob) || (string) $record->dob === '0000-00-00' ? '-' : \Carbon\Carbon::parse($record->dob)->format('d/m/Y');
                                $joining = blank($record->date_of_joining) || (string) $record->date_of_joining === '0000-00-00' ? '-' : \Carbon\Carbon::parse($record->date_of_joining)->format('d/m/Y');
                                $category = match ((int) ($record->category ?? 0)) { 1 => 'Administration', 2 => 'Teaching', 3 => 'Allied', default => '-' };
                            @endphp
                            <tr class="bg-white">
                                <td class="border border-[#ccd5e3] px-2 py-2">{{ $record->branch_name ?: '-' }}</td>
                                <td class="border border-[#ccd5e3] px-2 py-2">{{ $record->employee_id ?: '-' }}</td>
                                <td class="border border-[#ccd5e3] px-2 py-2">{{ trim((string) ($record->role_name ?? '')) ?: '-' }}</td>
                                <td class="border border-[#ccd5e3] px-2 py-2">
                                    <a href="{{ route('admin.hrms.staff.profile', $record->id, false) }}" class="text-[#0d6efd] hover:underline">{{ $fullName }}</a>
                                </td>
                                <td class="border border-[#ccd5e3] px-2 py-2">{{ $record->father_name ?: '-' }}</td>
                                <td class="border border-[#ccd5e3] px-2 py-2">{{ $dob }}</td>
                                <td class="border border-[#ccd5e3] px-2 py-2">{{ $joining }}</td>
                                <td class="border border-[#ccd5e3] px-2 py-2">{{ $record->department_name ?: '-' }}</td>
                                <td class="border border-[#ccd5e3] px-2 py-2">{{ $record->designation_name ?: '-' }}</td>
                                <td class="border border-[#ccd5e3] px-2 py-2">{{ $category }}</td>
                                <td class="border border-[#ccd5e3] px-2 py-2">{{ $record->whatsapp_no ?: ($record->contact_no ?: '-') }}</td>
                                <td class="border border-[#ccd5e3] px-2 py-2">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.hrms.staff.service-experience-certificate', $record->id, false) }}" title="Service Experience Certificate" class="inline-flex h-5 w-5 items-center justify-center rounded bg-[#f0a51a] text-white hover:bg-[#d89517]">
                                            <i class="fa-solid fa-download text-[9px]"></i>
                                        </a>
                                        <a href="{{ route('admin.hrms.staff.appointment-form', $record->id, false) }}" title="Appointment Form" class="inline-flex h-5 w-5 items-center justify-center rounded bg-[#f0a51a] text-white hover:bg-[#d89517]">
                                            <i class="fa-solid fa-file-signature text-[9px]"></i>
                                        </a>
                                        <a href="{{ route('admin.hrms.staff.profile', $record->id, false) }}" title="Show" class="inline-flex h-5 w-5 items-center justify-center rounded bg-[#264796] text-white hover:bg-[#1f3b7d]">
                                            <i class="fa-solid fa-bars text-[9px]"></i>
                                        </a>
                                        <a href="{{ route('admin.hrms.staff.edit', $record->id, false) }}" title="Edit" class="inline-flex h-5 w-5 items-center justify-center rounded bg-[#f0a51a] text-white hover:bg-[#d89517]">
                                            <i class="fa-solid fa-pen text-[9px]"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="border border-[#ccd5e3] px-3 py-4 text-center text-[11px] text-[#667085]">No record found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between px-1 py-2 text-[10px] text-[#344054]">
                <span>Record: {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() }}</span>
                <div>{{ $records->onEachSide(0)->links() }}</div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const buttons = document.querySelectorAll('[data-view-toggle]');
            const panels = {
                cards: document.querySelector('[data-view-panel="cards"]'),
                table: document.querySelector('[data-view-panel="table"]'),
            };

            const activateView = (view) => {
                Object.entries(panels).forEach(([key, panel]) => {
                    if (!panel) {
                        return;
                    }

                    panel.classList.toggle('hidden', key !== view);
                });

                buttons.forEach((button) => {
                    const active = button.dataset.viewToggle === view;
                    button.classList.toggle('text-[#264796]', active);
                    button.classList.toggle('border-b', active);
                    button.classList.toggle('border-[#264796]', active);
                    button.classList.toggle('text-[#222]', !active);
                });
            };

            buttons.forEach((button) => {
                button.addEventListener('click', () => activateView(button.dataset.viewToggle));
            });

            activateView('cards');
        })();
    </script>
@endpush
