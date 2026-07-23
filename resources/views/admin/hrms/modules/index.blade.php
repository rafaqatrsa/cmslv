@extends('admin.layouts.app')

@section('title', $module['label'])

@section('content')
    @if ($moduleKey !== 'staff')
        @include('admin.hrms.partials.nav')
    @endif

    @if ($moduleKey === 'staff')
        <section class="mb-4 overflow-hidden rounded-md border border-[#d9d9d9] bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-[#d9d9d9] bg-white px-4 py-2.5">
                <h2 class="text-[15px] font-medium text-[#333]">Select Criteria</h2>
                <a
                    href="#"
                    class="inline-flex items-center gap-1.5 bg-[#264796] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1f3b7e]"
                >
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    Add Staff
                </a>
            </div>

            <div class="p-4">
                <form method="GET" action="{{ route($module['route']) }}" class="grid gap-3 lg:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <label class="mb-1 block text-[10px] font-semibold text-[#333]">
                            Branch <span class="text-red-500">*</span>
                        </label>
                        <select class="h-7 w-full border border-[#d2d6de] bg-white px-2.5 text-[10px] text-[#555] focus:outline-hidden">
                            <option>Main Campus</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-[10px] font-semibold text-[#333]">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select class="h-7 w-full border border-[#d2d6de] bg-white px-2.5 text-[10px] text-[#555] focus:outline-hidden">
                            <option>Select</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-[10px] font-semibold text-[#333]">
                            Branch <span class="text-red-500">*</span>
                        </label>
                        <select class="h-7 w-full border border-[#d2d6de] bg-white px-2.5 text-[10px] text-[#555] focus:outline-hidden">
                            <option>Main Campus</option>
                        </select>
                    </div>

                    <div>
                        <label for="hrms-search" class="mb-1 block text-[10px] font-semibold text-[#333]">
                            Search By Keyword <span class="text-red-500">*</span>
                        </label>
                        <div class="flex">
                            <select class="h-7 border border-r-0 border-[#d2d6de] bg-[#f8f8f8] px-2 text-[10px] text-[#555] focus:outline-hidden">
                                <option>Staff ID</option>
                            </select>
                            <input
                                id="hrms-search"
                                name="search"
                                value="{{ request('search') }}"
                                class="h-7 min-w-0 flex-1 border border-[#d2d6de] px-2.5 text-[10px] text-[#555] placeholder:text-[#9ca3af] focus:outline-hidden"
                                placeholder="Search By Staff ID, Name, Role etc..."
                            >
                            <button class="h-7 shrink-0 bg-[#264796] px-2.5 text-[10px] font-semibold text-white transition hover:bg-[#1f3b7e]">
                                <i class="fa-solid fa-magnifying-glass mr-1 text-[10px]"></i>Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="overflow-hidden rounded-md border border-[#d9d9d9] bg-white shadow-sm">
            <div class="border-b border-[#d9d9d9] bg-white">
                <div class="flex flex-wrap items-center">
                    <button
                        type="button"
                        data-hrms-view-toggle="cards"
                        class="hrms-view-toggle border-r border-[#d9d9d9] border-b-2 border-b-transparent bg-white px-3 py-2 text-[10px] text-[#333]"
                    >
                        <i class="fa-regular fa-image mr-1.5"></i>View
                    </button>
                    <button
                        type="button"
                        data-hrms-view-toggle="table"
                        class="hrms-view-toggle border-r border-[#d9d9d9] border-b-2 border-b-[#264796] bg-white px-3 py-2 text-[10px] text-[#264796]"
                    >
                        <i class="fa-solid fa-list mr-1.5"></i>List View
                    </button>
                </div>
            </div>

            @if ($records->count() > 0)
                <div data-hrms-view="cards" class="hidden grid gap-2 p-2.5 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($records as $record)
                        @php
                            $staffName = trim((string) data_get($record, 'name').' '.(string) data_get($record, 'surname'));
                            $staffStatus = (int) data_get($record, 'is_active', 0) === 1 ? 'Active' : 'Inactive';
                            $departmentName = data_get($record, 'departmentDetail.name') ?: '-';
                            $designationName = data_get($record, 'designationDetail.name') ?: 'Staff';
                        @endphp
                        <article class="border border-[#e5e5e5] bg-white transition hover:shadow-sm">
                            <div class="flex gap-3 p-2.5">
                                <div class="flex h-16 w-16 shrink-0 flex-col items-center justify-center border border-[#e5e5e5] bg-linear-to-br from-[#f6f6f6] to-[#ebebeb] text-center text-[#b0b0b0]">
                                    <i class="fa-solid fa-users text-xl"></i>
                                    <span class="mt-1 text-[9px] font-semibold leading-tight">NO IMAGE<br>AVAILABLE</span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h4 class="truncate text-[11px] font-semibold text-[#222]">{{ $staffName !== '' ? $staffName : '-' }}</h4>
                                    <p class="mt-1 text-[11px] text-[#555]">{{ data_get($record, 'employee_id') ?: '-' }}</p>
                                    <p class="text-[10px] text-[#555]">{{ $departmentName }}</p>
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        <span class="border border-[#cfcfcf] bg-[#efefef] px-1.5 py-0.5 text-[10px] text-[#444]">
                                            {{ $designationName }}
                                        </span>
                                        <span class="border border-[#cfcfcf] bg-[#efefef] px-1.5 py-0.5 text-[10px] text-[#444]">
                                            {{ $staffStatus }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div data-hrms-view="table" class="block p-2.5">
                    <div class="mb-2 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <input
                            type="text"
                            value="Search..."
                            class="h-7 w-full max-w-[100px] border border-[#d2d6de] px-2 text-[10px] text-[#666] focus:outline-hidden"
                            readonly
                        >

                        <div class="flex flex-wrap items-center justify-end gap-1">
                            @foreach (['copy', 'file-excel', 'file-csv', 'file-pdf', 'print', 'columns'] as $icon)
                                <button
                                    type="button"
                                    class="inline-flex h-6 w-6 items-center justify-center rounded-sm bg-[#264796] text-[10px] text-white transition hover:bg-[#1f3b7e]"
                                >
                                    <i class="fa-solid fa-{{ $icon }}"></i>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-[#d2d6de]">
                        <table class="min-w-full text-left text-[10px] text-[#444]">
                            <thead class="bg-[#2d4a91] text-white">
                                <tr>
                                    <th class="border-r border-[#8da1d0] px-2 py-1.5 font-semibold">Branch <i class="fa-solid fa-caret-down ml-1 text-[9px]"></i></th>
                                    <th class="border-r border-[#8da1d0] px-2 py-1.5 font-semibold">Staff ID <i class="fa-solid fa-caret-down ml-1 text-[9px]"></i></th>
                                    <th class="border-r border-[#8da1d0] px-2 py-1.5 font-semibold">Role <i class="fa-solid fa-caret-down ml-1 text-[9px]"></i></th>
                                    <th class="border-r border-[#8da1d0] px-2 py-1.5 font-semibold">Name <i class="fa-solid fa-caret-down ml-1 text-[9px]"></i></th>
                                    <th class="border-r border-[#8da1d0] px-2 py-1.5 font-semibold">Father Name <i class="fa-solid fa-caret-down ml-1 text-[9px]"></i></th>
                                    <th class="border-r border-[#8da1d0] px-2 py-1.5 font-semibold">Date of Birth <i class="fa-solid fa-caret-down ml-1 text-[9px]"></i></th>
                                    <th class="border-r border-[#8da1d0] px-2 py-1.5 font-semibold">Date of Joining <i class="fa-solid fa-caret-down ml-1 text-[9px]"></i></th>
                                    <th class="border-r border-[#8da1d0] px-2 py-1.5 font-semibold">Department <i class="fa-solid fa-caret-down ml-1 text-[9px]"></i></th>
                                    <th class="border-r border-[#8da1d0] px-2 py-1.5 font-semibold">Designation <i class="fa-solid fa-caret-down ml-1 text-[9px]"></i></th>
                                    <th class="border-r border-[#8da1d0] px-2 py-1.5 font-semibold">Category <i class="fa-solid fa-caret-down ml-1 text-[9px]"></i></th>
                                    <th class="border-r border-[#8da1d0] px-2 py-1.5 font-semibold">Mobile No <i class="fa-solid fa-caret-down ml-1 text-[9px]"></i></th>
                                    <th class="px-2 py-1.5 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    @php
                                        $categoryLabel = match ((int) data_get($record, 'category')) {
                                            1 => 'Administration',
                                            2 => 'Teaching',
                                            3 => 'Allied',
                                            default => '-',
                                        };
                                    @endphp
                                    <tr class="bg-white align-top hover:bg-[#fafcff]">
                                        <td class="border-r border-b border-[#d2d6de] px-2 py-2">{{ data_get($record, 'branch.name') ?: 'Main Campus' }}</td>
                                        <td class="border-r border-b border-[#d2d6de] px-2 py-2">{{ data_get($record, 'employee_id') ?: '-' }}</td>
                                        <td class="border-r border-b border-[#d2d6de] px-2 py-2">{{ data_get($record, 'role.name') ?: '-' }}</td>
                                        <td class="border-r border-b border-[#d2d6de] px-2 py-2 text-[#1380c9]">{{ trim((string) data_get($record, 'name').' '.(string) data_get($record, 'surname')) ?: '-' }}</td>
                                        <td class="border-r border-b border-[#d2d6de] px-2 py-2">{{ data_get($record, 'father_name') ?: '-' }}</td>
                                        <td class="border-r border-b border-[#d2d6de] px-2 py-2">{{ data_get($record, 'dob')?->format('d/m/Y') ?: '-' }}</td>
                                        <td class="border-r border-b border-[#d2d6de] px-2 py-2">{{ data_get($record, 'date_of_joining')?->format('d/m/Y') ?: '-' }}</td>
                                        <td class="border-r border-b border-[#d2d6de] px-2 py-2">{{ data_get($record, 'departmentDetail.name') ?: '-' }}</td>
                                        <td class="border-r border-b border-[#d2d6de] px-2 py-2">{{ data_get($record, 'designationDetail.name') ?: '-' }}</td>
                                        <td class="border-r border-b border-[#d2d6de] px-2 py-2">{{ $categoryLabel }}</td>
                                        <td class="border-r border-b border-[#d2d6de] px-2 py-2">{{ data_get($record, 'contact_no') ?: '-' }}</td>
                                        <td class="border-b border-[#d2d6de] px-2 py-1.5">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach (['download', 'download', 'list', 'pencil'] as $icon)
                                                    <button
                                                        type="button"
                                                        class="inline-flex h-4 w-4 items-center justify-center rounded-[2px] bg-[#f0a31a] text-[9px] text-white {{ in_array($icon, ['list', 'pencil'], true) ? 'bg-[#2d4a91]' : '' }}"
                                                    >
                                                        <i class="fa-solid fa-{{ $icon }}"></i>
                                                    </button>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col gap-2 pt-2 text-[9px] text-[#333] md:flex-row md:items-center md:justify-between">
                        <p>
                            Record: {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() }}
                        </p>

                        <div class="flex items-center gap-1.5 text-[#999]">
                            <a
                                href="{{ $records->previousPageUrl() ?: '#' }}"
                                class="inline-flex h-4 w-4 items-center justify-center {{ $records->onFirstPage() ? 'pointer-events-none opacity-40' : '' }}"
                            >
                                <i class="fa-solid fa-angle-left"></i>
                            </a>
                            <span class="inline-flex min-w-4 items-center justify-center bg-[#f1f1f1] px-1 py-0.5 text-[#555]">
                                {{ $records->currentPage() }}
                            </span>
                            <a
                                href="{{ $records->hasMorePages() ? $records->nextPageUrl() : '#' }}"
                                class="inline-flex h-4 w-4 items-center justify-center {{ $records->hasMorePages() ? '' : 'pointer-events-none opacity-40' }}"
                            >
                                <i class="fa-solid fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="px-5 py-12">
                    <div class="flex flex-col items-center justify-center border border-dashed border-[#d9d9d9] bg-[#fafafa] px-6 py-10 text-center">
                        <div class="flex size-14 items-center justify-center bg-white text-[#264796] shadow-xs">
                            <i class="fa-solid fa-folder-open text-lg"></i>
                        </div>
                        <h4 class="mt-4 text-lg font-semibold text-[#222]">No staff records found</h4>
                        <p class="mt-2 max-w-lg text-sm text-[#666]">
                            The legacy table may be empty in this environment, or your current search did not match any staff members.
                        </p>
                    </div>
                </div>
            @endif
        </section>
    @else
        <section class="mb-5 overflow-hidden rounded-[28px] border border-sky-100 bg-linear-to-r from-slate-900 via-blue-900 to-sky-700 text-white shadow-lg shadow-blue-950/10">
            <div class="flex flex-col gap-6 px-5 py-6 lg:flex-row lg:items-end lg:justify-between lg:px-7">
                <div class="max-w-2xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-100/80">HRMS Module</p>
                    <h2 class="mt-3 text-3xl font-semibold tracking-tight">{{ $module['label'] }} Directory</h2>
                    <p class="mt-2 max-w-xl text-sm text-blue-100/80">
                        Review legacy {{ strtolower($module['label']) }} records, search quickly, and scan the current dataset from one place.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.3em] text-blue-100/70">Legacy Table</p>
                        <p class="mt-2 text-lg font-semibold">{{ $module['table'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.3em] text-blue-100/70">Visible Columns</p>
                        <p class="mt-2 text-lg font-semibold">{{ count($module['columns']) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.3em] text-blue-100/70">Total Records</p>
                        <p class="mt-2 text-lg font-semibold">{{ number_format($records->total()) }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5 rounded-[24px] border border-slate-200/80 bg-white p-4 shadow-sm shadow-slate-200/60 sm:p-5">
            <form method="GET" action="{{ route($module['route']) }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <label for="generic-hrms-search" class="sr-only">Search {{ strtolower($module['label']) }}</label>
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input
                        id="generic-hrms-search"
                        name="search"
                        value="{{ request('search') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 shadow-xs transition focus:border-sky-500 focus:bg-white focus:outline-hidden focus:ring-4 focus:ring-sky-100"
                        placeholder="Search {{ strtolower($module['label']) }} directory"
                    >
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <button class="inline-flex items-center justify-center rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700 focus:outline-hidden focus:ring-4 focus:ring-sky-100">
                        Search
                    </button>

                    @if (request()->filled('search'))
                        <a
                            href="{{ route($module['route']) }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-[24px] border border-slate-200/80 bg-white shadow-sm shadow-slate-200/60">
            <div class="flex flex-col gap-4 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">{{ $module['label'] }} records</h3>
                    <p class="mt-1 text-sm text-slate-500">Legacy table: {{ $module['table'] }}</p>
                </div>
                <div class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold tracking-wide text-slate-600">
                    {{ $records->count() }} shown
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            @foreach ($module['columns'] as $column)
                                <th class="px-5 py-4">{{ \Illuminate\Support\Str::headline($column) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80">
                        @forelse ($records as $record)
                            <tr class="transition hover:bg-sky-50/50">
                                @foreach ($module['columns'] as $column)
                                    <td class="max-w-sm px-5 py-4 align-top">
                                        <span class="block font-medium text-slate-700">
                                            {{ \Illuminate\Support\Str::limit(strip_tags((string) data_get($record, $column)), 120) ?: '-' }}
                                        </span>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($module['columns']) }}" class="px-5 py-12">
                                    <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">
                                        <div class="flex size-14 items-center justify-center rounded-full bg-white text-sky-600 shadow-xs">
                                            <i class="fa-solid fa-folder-open text-lg"></i>
                                        </div>
                                        <h4 class="mt-4 text-lg font-semibold text-slate-900">No {{ strtolower($module['label']) }} records found</h4>
                                        <p class="mt-2 max-w-lg text-sm text-slate-500">
                                            The legacy table may be empty in this environment, or your current search did not match any records.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    @if ($moduleKey === 'staff')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const toggles = document.querySelectorAll('[data-hrms-view-toggle]');
                const views = document.querySelectorAll('[data-hrms-view]');

                if (! toggles.length || ! views.length) {
                    return;
                }

                const activateView = (viewName) => {
                    views.forEach((view) => {
                        view.classList.toggle('hidden', view.dataset.hrmsView !== viewName);
                    });

                    toggles.forEach((toggle) => {
                        const isActive = toggle.dataset.hrmsViewToggle === viewName;

                        toggle.classList.toggle('border-b-[#264796]', isActive);
                        toggle.classList.toggle('text-[#264796]', isActive);
                        toggle.classList.toggle('border-b-transparent', ! isActive);
                        toggle.classList.toggle('text-[#333]', ! isActive);
                    });
                };

                toggles.forEach((toggle) => {
                    toggle.addEventListener('click', () => activateView(toggle.dataset.hrmsViewToggle));
                });

                activateView('table');
            });
        </script>
    @endif
@endpush
