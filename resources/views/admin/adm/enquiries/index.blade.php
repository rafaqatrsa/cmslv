@extends('admin.layouts.app')

@section('title', 'Admission Enquiry')

@push('styles')
    <style>
        .adm-enquiry-page {
            color: #313131;
        }

        .adm-page-header {
            padding: 2px 0 0;
        }

        .adm-page-header h1 {
            margin: 0;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.2;
            color: #313131;
        }

        .adm-box {
            overflow: hidden;
            border: 1px solid #d8d8d8;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.12);
        }

        .adm-box + .adm-box {
            margin-top: 4px;
        }

        .adm-box-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            border-bottom: 1px solid #d8d8d8;
            padding: 10px 14px;
        }

        .adm-box-title {
            margin: 0;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 21px;
            font-weight: 400;
            line-height: 1.2;
            color: #313131;
        }

        .adm-box-tools {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .adm-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 0;
            border-radius: 4px;
            padding: 8px 14px;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            line-height: 1;
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.18);
            transition: background-color 150ms ease, transform 150ms ease;
        }

        .adm-btn:hover {
            transform: translateY(-1px);
        }

        .adm-btn-primary {
            background: #26408d;
        }

        .adm-btn-primary:hover {
            background: #213878;
        }

        .adm-btn-success {
            background: #11b256;
        }

        .adm-btn-success:hover {
            background: #0e9448;
        }

        .adm-filter-grid {
            display: grid;
            gap: 12px;
            padding: 14px;
        }

        @media (min-width: 768px) {
            .adm-filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .adm-filter-grid {
                grid-template-columns: repeat(6, minmax(0, 1fr));
            }
        }

        .adm-field {
            min-width: 0;
        }

        .adm-label {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.1;
            color: #1f2937;
        }

        .adm-control {
            width: 100%;
            min-height: 46px;
            border: 1px solid #cfcfcf;
            border-radius: 3px;
            background: #fff;
            padding: 10px 18px;
            font-size: 16px;
            color: #4b5563;
            outline: none;
        }

        .adm-control:focus {
            border-color: #7d95cb;
            box-shadow: 0 0 0 3px rgba(38, 64, 141, 0.1);
        }

        .adm-control.is-muted {
            background: #efefef;
        }

        .adm-filter-actions {
            display: flex;
            justify-content: flex-end;
            padding: 0 14px 14px;
        }

        .adm-search-strip {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px;
        }

        .adm-search-form {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .adm-search-input {
            min-width: 235px;
            border: 1px solid #bdbdbd;
            border-radius: 4px;
            padding: 8px 10px;
            font-size: 15px;
            color: #444;
            outline: none;
        }

        .adm-search-input:focus {
            border-color: #26408d;
            box-shadow: 0 0 0 3px rgba(38, 64, 141, 0.08);
        }

        .adm-icon-toolbar {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .adm-icon-btn {
            display: inline-flex;
            height: 32px;
            width: 32px;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 4px;
            background: #26408d;
            color: #fff;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.18);
            cursor: pointer;
            transition: background-color 150ms ease, transform 150ms ease, box-shadow 150ms ease;
        }

        .adm-icon-btn:hover,
        .adm-icon-btn:focus-visible {
            background: #213878;
            transform: translateY(-1px);
            box-shadow: 0 3px 7px rgba(15, 23, 42, 0.24);
            outline: none;
        }

        .adm-table-wrap {
            overflow-x: auto;
        }

        .adm-table {
            width: 100%;
            min-width: 1180px;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 15px;
        }

        .adm-table thead th {
            border-right: 1px solid rgba(255, 255, 255, 0.18);
            background: #26408d;
            padding: 14px 12px;
            color: #fff;
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
        }

        .adm-table thead th:last-child {
            border-right: 0;
        }

        .adm-table tbody td {
            border-right: 1px solid #e7cfcf;
            border-bottom: 1px solid #e7cfcf;
            background: #f7e3e3;
            padding: 14px 12px;
            color: #2f2f2f;
            vertical-align: middle;
            white-space: nowrap;
        }

        .adm-table tbody tr:nth-child(even) td {
            background: #f4dddd;
        }

        .adm-table tbody tr:hover td {
            background: #eed0d0;
        }

        .adm-table tbody td:last-child {
            border-right: 0;
        }

        .adm-badge {
            display: inline-flex;
            min-width: 68px;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            background: #20b7c6;
            padding: 6px 12px;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .adm-action-group {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
        }

        .adm-action-btn {
            display: inline-flex;
            height: 28px;
            width: 28px;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.18);
            cursor: pointer;
            transition: filter 150ms ease, transform 150ms ease, box-shadow 150ms ease;
        }

        .adm-action-btn:hover,
        .adm-action-btn:focus-visible {
            filter: brightness(0.9);
            transform: translateY(-1px);
            box-shadow: 0 3px 7px rgba(15, 23, 42, 0.24);
            outline: none;
        }

        .adm-action-call {
            background: #1ca64c;
        }

        .adm-action-view {
            background: #26408d;
        }

        .adm-action-download {
            background: #1cbf72;
        }

        .adm-action-edit {
            background: #26408d;
        }

        .adm-action-delete {
            background: #ef5b47;
        }

        .adm-empty-row td {
            background: #fff !important;
            text-align: center;
        }

        .adm-pagination {
            display: flex;
            justify-content: flex-end;
            gap: 4px;
            padding: 10px 12px 16px;
            color: #7b7b7b;
        }

        .adm-pagination-link,
        .adm-pagination-current {
            display: inline-flex;
            min-width: 26px;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            border-radius: 2px;
            padding: 2px 6px;
            font-size: 13px;
            line-height: 1.2;
            text-decoration: none;
        }

        .adm-pagination-current {
            border-color: #ddd;
            background: #efefef;
            color: #7b7b7b;
        }

        .adm-pagination-link {
            color: #7b7b7b;
        }

        .adm-pagination-link:hover {
            border-color: #cfcfcf;
            background: #f8f8f8;
        }
    </style>
@endpush

@section('content')
    <div class="adm-enquiry-page space-y-3">
        <section class="adm-page-header">
            <h1>
                <i class="fa fa-ioxhost"></i>
                <span>{{ __('Front Office') }}</span>
            </h1>
        </section>

        <section class="adm-box">
            <div class="adm-box-header">
                <h2 class="adm-box-title">
                    <i class="fa fa-search"></i>
                    <span>Select Criteria</span>
                </h2>

                <div class="adm-box-tools">
                    <button type="button" class="adm-btn adm-btn-primary" id="open-add-enquiry">
                        <i class="fa fa-plus"></i>
                        <span>Add</span>
                    </button>
                    <button type="button" class="adm-btn adm-btn-success" id="open-empty-enquiry-form">
                        <i class="fa fa-cloud-arrow-down"></i>
                        <span>Admission Enquiry Form</span>
                    </button>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.adm.enquiries.index') }}">
                <input type="hidden" name="search" value="{{ $selectedSearch }}">

                <div class="adm-filter-grid">
                    <label class="adm-field">
                        <span class="adm-label">
                            Branch <span class="text-red-600">*</span>
                        </span>
                        <select name="brc_id" class="adm-control">
                            <option value="">Select</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected((string) $selectedBranch === (string) $branch->id)>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="adm-field">
                        <span class="adm-label">Class</span>
                        <select name="class_id" class="adm-control">
                            <option value="">Select</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected((string) $selectedClass === (string) $class->id)>{{ $class->class }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="adm-field">
                        <span class="adm-label">Source</span>
                        <select name="source" class="adm-control">
                            <option value="">Select</option>
                            @foreach ($sources as $source)
                                <option value="{{ data_get($source, 'source') }}" @selected((string) $selectedSource === (string) data_get($source, 'source'))>{{ data_get($source, 'source') }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="adm-field">
                        <span class="adm-label">Date From</span>
                        <input type="date" name="date_from" value="{{ $selectedDateFrom }}" class="adm-control is-muted">
                    </label>

                    <label class="adm-field">
                        <span class="adm-label">Date To</span>
                        <input type="date" name="date_to" value="{{ $selectedDateTo }}" class="adm-control is-muted">
                    </label>

                    <label class="adm-field">
                        <span class="adm-label">Status</span>
                        <select name="status" class="adm-control">
                            <option value="">Select</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected((string) $selectedStatus === (string) $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div class="adm-filter-actions">
                    <button type="submit" class="adm-btn adm-btn-primary px-4 py-2.5">
                        <i class="fa fa-search"></i>
                        <span>Search</span>
                    </button>
                </div>
            </form>
        </section>

        <section class="adm-box">
            <div class="adm-box-header">
                <h2 class="adm-box-title">Admission Enquiry</h2>
            </div>

            <div class="adm-search-strip">
                <form method="GET" action="{{ route('admin.adm.enquiries.index') }}" class="adm-search-form">
                    <input type="hidden" name="brc_id" value="{{ $selectedBranch }}">
                    <input type="hidden" name="class_id" value="{{ $selectedClass }}">
                    <input type="hidden" name="source" value="{{ $selectedSource }}">
                    <input type="hidden" name="status" value="{{ $selectedStatus }}">
                    <input type="hidden" name="date_from" value="{{ $selectedDateFrom }}">
                    <input type="hidden" name="date_to" value="{{ $selectedDateTo }}">

                    <input
                        type="text"
                        name="search"
                        value="{{ $selectedSearch }}"
                        placeholder="Search..."
                        class="adm-search-input"
                        id="enquiry-search"
                        data-enquiry-ajax-search
                    >
                </form>

                <div class="adm-icon-toolbar" id="enquiry-export-toolbar">
                    @foreach ([
                        'copy' => 'fa-regular fa-copy',
                        'excel' => 'fa-solid fa-file-excel',
                        'csv' => 'fa-regular fa-file-lines',
                        'pdf' => 'fa-regular fa-file-pdf',
                        'print' => 'fa-solid fa-print',
                        'columns' => 'fa-solid fa-table-columns',
                    ] as $label => $icon)
                        <button type="button" class="adm-icon-btn" data-enquiry-export="{{ $label }}" aria-label="{{ ucfirst($label) }}" title="{{ ucfirst($label) }}">
                            <i class="{{ $icon }}"></i>
                        </button>
                    @endforeach
                    <div id="enquiry-column-menu" class="adm-column-menu hidden">
                        @foreach (['Sr.#', 'Date', 'Visitor Name', 'Visitor Phone', 'Visitor Relation', 'Source', 'Reference', 'Assigned To', 'Follow Up', 'Status'] as $index => $heading)
                            <label><input type="checkbox" data-enquiry-column="{{ $index }}" checked> {{ $heading }}</label>
                        @endforeach
                        <button type="button" data-enquiry-columns-reset>Restore columns</button>
                    </div>
                </div>
            </div>

            <div class="adm-table-wrap">
                <table class="adm-table" id="enquirytable">
                    <thead>
                        <tr>
                            @foreach (['Sr.#', 'Date', 'Visitor Name', 'Visitor Phone', 'Visitor Relation', 'Source', 'Reference', 'Assigned To', 'Follow Up', 'Status', 'Action'] as $heading)
                                <th @if (in_array($heading, ['Date', 'Visitor Name', 'Source', 'Reference', 'Status'], true)) data-enquiry-sort="{{ ['Date' => 'date', 'Visitor Name' => 'name', 'Source' => 'source', 'Reference' => 'reference', 'Status' => 'status'][$heading] }}" @endif>
                                    <span class="inline-flex items-center gap-1">
                                        {{ $heading }}
                                        @if (in_array($heading, ['Sr.#', 'Date', 'Visitor Name', 'Visitor Phone', 'Visitor Relation', 'Source', 'Reference', 'Assigned To', 'Follow Up', 'Status'], true))
                                            <i class="fa-solid fa-caret-down text-[11px] opacity-80"></i>
                                        @endif
                                    </span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            @php
                                $followUpDate = $record->follow_up_date ?? null;
                                $isOverdue = $followUpDate && \Illuminate\Support\Carbon::parse($followUpDate)->lt(now()->startOfDay());
                            @endphp
                            <tr class="{{ $isOverdue ? 'bg-[#f3d7d7]' : '' }}">
                                <td>{{ $records->firstItem() + $loop->index }}</td>
                                <td>{{ $record->formatted_date }}</td>
                                <td>{{ $record->name ?: 'N/A' }}</td>
                                <td>{{ $record->contact ?: $record->phone ?: 'N/A' }}</td>
                                <td>{{ $record->visitor_relation_label }}</td>
                                <td>{{ $record->source_label }}</td>
                                <td>{{ $record->reference_label }}</td>
                                <td>{{ $record->assigned_to_label }}</td>
                                <td class="text-center">
                                    <span class="adm-badge" data-follow-up-count="{{ $record->id }}">{{ $record->follow_up_count ?? 0 }} times</span>
                                </td>
                                <td>{{ $record->status_label }}</td>
                                <td>
                                    <div class="adm-action-group">
                                        <button type="button" class="adm-action-btn adm-action-call" data-enquiry-id="{{ $record->id }}" aria-label="Follow Up">
                                            <i class="fa-solid fa-phone"></i>
                                        </button>
                                        <button type="button" class="adm-action-btn adm-action-view" data-enquiry-id="{{ $record->id }}" aria-label="View">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                        <button type="button" class="adm-action-btn adm-action-download" data-enquiry-id="{{ $record->id }}" aria-label="Download">
                                            <i class="fa-solid fa-cloud-arrow-down"></i>
                                        </button>
                                        <button type="button" class="adm-action-btn adm-action-edit" data-enquiry-id="{{ $record->id }}" aria-label="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button type="button" class="adm-action-btn adm-action-delete" data-enquiry-id="{{ $record->id }}" aria-label="Delete">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="adm-empty-row">
                                <td colspan="11" class="py-10 text-sm text-[#6b7280]">No admission enquiries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="adm-pagination" id="enquiry-pagination" @if (! $records->hasPages()) style="display:none" @endif>
                    @if ($records->onFirstPage())
                        <span class="adm-pagination-link opacity-50">‹</span>
                    @else
                        <a href="{{ $records->previousPageUrl() }}" class="adm-pagination-link">‹</a>
                    @endif

                    <span class="adm-pagination-current">{{ $records->currentPage() }}</span>

                    @if ($records->hasMorePages())
                        <a href="{{ $records->nextPageUrl() }}" class="adm-pagination-link">›</a>
                    @else
                        <span class="adm-pagination-link opacity-50">›</span>
                    @endif
            </div>
        </section>
</div>

<div id="add-enquiry-modal" class="enquiry-modal-backdrop hidden" aria-hidden="true">
    <div class="enquiry-modal enquiry-add-modal" role="dialog" aria-modal="true" aria-labelledby="add-enquiry-modal-title">
        <div class="enquiry-modal-header">
            <h3 id="add-enquiry-modal-title">Add Admission Enquiry</h3>
            <button type="button" data-add-enquiry-close aria-label="Close">&times;</button>
        </div>
        <div class="enquiry-modal-body">
            @include('admin.adm.enquiries._form', ['formId' => 'admission-enquiry-modal-form'])
        </div>
    </div>
</div>

<div id="empty-enquiry-form-modal" class="enquiry-modal-backdrop hidden" aria-hidden="true">
    <div class="enquiry-modal enquiry-small-modal" role="dialog" aria-modal="true">
        <div class="enquiry-modal-header">
            <h3>Admission Enquiry Form</h3>
            <button type="button" data-empty-enquiry-close aria-label="Close">×</button>
        </div>
        <div class="enquiry-modal-body">
            <label class="adm-field">
                <span class="adm-label">Class</span>
                <select id="empty-enquiry-class" class="adm-control">
                    <option value="">Select</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class }}</option>
                    @endforeach
                </select>
            </label>
            <p id="empty-enquiry-error" class="mt-2 text-sm text-red-600"></p>
            <button type="button" class="adm-btn adm-btn-primary mt-4" id="download-empty-enquiry-form">
                <i class="fa fa-download"></i> Download
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .adm-column-menu { position: absolute; z-index: 40; right: 0; top: 38px; width: 210px; border: 1px solid #d1d5db; border-radius: 4px; background: #fff; padding: 8px; box-shadow: 0 8px 24px rgba(15, 23, 42, .18); }
        .enquiry-small-modal { width: min(460px, 100%); }
        .adm-column-menu label { display: block; padding: 5px; font-size: 12px; color: #374151; }
        .adm-column-menu button { width: 100%; margin-top: 5px; border: 0; background: #26408d; padding: 6px; color: #fff; font-size: 12px; }
        #enquiry-export-toolbar { position: relative; }
        .adm-hidden-column { display: none !important; }
    </style>
@endpush

@push('scripts')
    @php($followUpDestroyUrl = route('admin.adm.enquiries.follow-ups.destroy', ['id' => 0, 'followUpId' => 0], absolute: false))
    <script src="{{ asset('assets/fullcalendar/sweetalert2.js') }}"></script>
    <script>
        (() => {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const urls = {
                details: @json(route('admin.adm.enquiries.details', ['id' => 0], absolute: false)),
                followUps: @json(route('admin.adm.enquiries.follow-ups', ['id' => 0], absolute: false)),
                update: @json(route('admin.adm.enquiries.update', ['id' => 0], absolute: false)),
                destroy: @json(route('admin.adm.enquiries.destroy', ['id' => 0], absolute: false)),
                status: @json(route('admin.adm.enquiries.status', ['id' => 0], absolute: false)),
                followUpStore: @json(route('admin.adm.enquiries.follow-ups.store', ['id' => 0], absolute: false)),
                followUpDestroy: @json($followUpDestroyUrl),
            };
            const searchInput = document.querySelector('[data-enquiry-ajax-search]');

            const escapeHtml = (value) => String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');

            const modal = (title, content) => {
                let element = document.querySelector('#enquiry-action-modal');
                if (!element) {
                    element = document.createElement('div');
                    element.id = 'enquiry-action-modal';
                    element.className = 'enquiry-modal-backdrop';
                    document.body.appendChild(element);
                }
                element.innerHTML = `<div class="enquiry-modal" role="dialog" aria-modal="true"><div class="enquiry-modal-header"><h3>${title}</h3><button type="button" data-enquiry-modal-close aria-label="Close">×</button></div><div class="enquiry-modal-body">${content}</div></div>`;
                element.classList.remove('hidden');
                element.querySelector('[data-enquiry-modal-close]').addEventListener('click', () => element.classList.add('hidden'));
                element.addEventListener('click', (event) => { if (event.target === element) element.classList.add('hidden'); }, { once: true });
                return element;
            };

            const request = async (url, options = {}) => {
                const response = await fetch(url, {
                    credentials: 'same-origin',
                    ...options,
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', ...(options.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }), 'X-CSRF-TOKEN': token, ...(options.headers || {}) },
                });
                const payload = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(payload.message || Object.values(payload.errors || {}).flat().join(' ') || 'Request failed.');
                return payload;
            };

            const notify = (message, type = 'success') => {
                if (window.Swal) return window.Swal.fire({ icon: type, title: type === 'success' ? 'Success' : 'Error', text: message, timer: 1800, showConfirmButton: false });
                window.alert(message);
            };

            const replaceId = (template, id, second = null) => second === null
                ? template.replace('/0', `/${id}`)
                : template.replace('/0', `/${id}`).replace('/0', `/${second}`);
            const refreshTable = () => searchInput?.dispatchEvent(new Event('input', { bubbles: true }));
            const value = (record, key) => record[key] ?? '';

            const followUpHtml = (payload) => `<div class="enquiry-followup-layout"><div><form id="enquiry-followup-form" class="enquiry-form-grid">
                <input type="hidden" name="_token" value="${token}">
                <label>Date*<input type="date" name="date" value="${new Date().toISOString().slice(0, 10)}" required></label>
                <label>Next Follow Up Date*<input type="date" name="follow_up_date" required></label>
                <label class="enquiry-span-2">Response*<textarea name="response" required></textarea></label>
                <label class="enquiry-span-2">Note<textarea name="note"></textarea></label>
                <button class="adm-btn adm-btn-primary" type="submit">Save</button>
            </form><h4>Follow Up History</h4><div id="enquiry-followup-history">${historyHtml(payload)}</div></div>
            <aside class="enquiry-summary"><strong>Status</strong><select id="enquiry-status-select">${['Active', 'Inactive', 'Won', 'Lost', 'Passive'].map((status) => `<option value="${status}" ${String(payload.enquiry.status).toLowerCase() === status.toLowerCase() ? 'selected' : ''}>${status}</option>`).join('')}</select><p><b>Visitor:</b> ${escapeHtml(payload.enquiry.name)}</p><p><b>Follow-ups:</b> <span data-modal-follow-up-count>${payload.count} times</span></p></aside></div>`;

            const historyHtml = (payload) => payload.follow_ups?.length ? `<div class="enquiry-history">${payload.follow_ups.map((followUp) => `<div class="enquiry-history-row"><div><strong>${escapeHtml(followUp.date_label)}</strong> → ${escapeHtml(followUp.next_date_label)}<p>${escapeHtml(followUp.response)}</p><small>${escapeHtml(followUp.note || '')} — ${escapeHtml(followUp.followup_by_label)}</small></div><button type="button" class="adm-action-btn adm-action-delete" data-follow-up-delete="${followUp.id}">×</button></div>`).join('')}</div>` : '<p>No follow-up history found.</p>';

            const openFollowUp = async (id) => {
                try {
                    const payload = await request(replaceId(urls.followUps, id));
                    const element = modal(`Follow Up — ${escapeHtml(payload.enquiry.name)}`, followUpHtml(payload));
                    element.querySelector('#enquiry-followup-form').addEventListener('submit', async (event) => {
                        event.preventDefault();
                        try {
                            const response = await request(replaceId(urls.followUpStore, id), { method: 'POST', body: new FormData(event.currentTarget) });
                            element.querySelector('#enquiry-followup-history').innerHTML = historyHtml({ follow_ups: response.follow_ups });
                            element.querySelector('[data-modal-follow-up-count]').textContent = `${response.count} times`;
                            document.querySelector(`[data-follow-up-count="${id}"]`).textContent = `${response.count} times`;
                            event.currentTarget.reset();
                            notify(response.message);
                        } catch (error) { notify(error.message, 'error'); }
                    });
                    element.querySelector('#enquiry-status-select').addEventListener('change', async (event) => {
                        try { await request(replaceId(urls.status, id), { method: 'PATCH', body: JSON.stringify({ status: event.target.value }) }); notify('Status updated successfully.'); refreshTable(); } catch (error) { notify(error.message, 'error'); }
                    });
                    element.addEventListener('click', async (event) => {
                        const button = event.target.closest('[data-follow-up-delete]');
                        if (!button || !(await confirmAction('Delete this follow-up?'))) return;
                        try {
                            const response = await request(replaceId(urls.followUpDestroy, id, button.dataset.followUpDelete), { method: 'DELETE' });
                            element.querySelector('#enquiry-followup-history').innerHTML = historyHtml({ follow_ups: response.follow_ups });
                            element.querySelector('[data-modal-follow-up-count]').textContent = `${response.count} times`;
                            document.querySelector(`[data-follow-up-count="${id}"]`).textContent = `${response.count} times`;
                            notify(response.message);
                        } catch (error) { notify(error.message, 'error'); }
                    });
                } catch (error) { notify(error.message, 'error'); }
            };

            const openDetails = async (id) => {
                try {
                    const payload = await request(replaceId(urls.details, id));
                    const record = payload.record;
                    const fields = [['Enquiry No', 'enquiry_no'], ['Date', 'formatted_date'], ['Visitor Name', 'name'], ['Relation', 'visitor_relation_label'], ['Phone', 'contact'], ['Email', 'email'], ['ID Card', 'id_card'], ['Status', 'status_label'], ['Assigned To', 'assigned_to_label'], ['Reference', 'reference_label'], ['Source', 'source_label'], ['Father/Guardian', 'father_name'], ['Address', 'address'], ['Landline', 'landline_no'], ['WhatsApp', 'whatsapp'], ['Description', 'description'], ['Note', 'note']];
                    const kids = payload.kids?.length ? `<h4>Proposed Kids</h4><table class="enquiry-detail-table"><tr><th>Class</th><th>Kid Name</th><th>Number</th></tr>${payload.kids.map((kid) => `<tr><td>${escapeHtml(kid.class_name || '')}</td><td>${escapeHtml(kid.kid_name || '')}</td><td>${escapeHtml(kid.number_of_kids || '')}</td></tr>`).join('')}</table>` : '';
                    modal('Admission Enquiry Details', `<table class="enquiry-detail-table">${fields.map(([label, key]) => `<tr><th>${label}</th><td>${escapeHtml(value(record, key) || 'N/A')}</td></tr>`).join('')}</table>${kids}`);
                } catch (error) { notify(error.message, 'error'); }
            };

            const openEdit = async (id) => {
                try {
                    const payload = await request(replaceId(urls.details, id));
                    const record = payload.record;
                    const optionMarkup = (items, selected) => items.map((item) => '<option value="' + escapeHtml(item.id) + '" ' + (String(item.id) === String(selected ?? '') ? 'selected' : '') + '>' + escapeHtml(item.label) + '</option>').join('');
                    const input = (key, label, type = 'text', required = false) => '<label>' + label + '<input name="' + key + '" type="' + type + '" value="' + escapeHtml(value(record, key)) + '" ' + (required ? 'required' : '') + '></label>';
                    const select = (key, label, items, selected) => '<label>' + label + '<select name="' + key + '"><option value="">Select</option>' + optionMarkup(items, selected) + '</select></label>';
                    const fields = [
                        input('name', 'Visitor Name', 'text', true),
                        input('contact', 'Phone', 'text', true),
                        input('email', 'Email', 'email'),
                        input('id_card', 'ID Card'),
                        '<label>Visitor Relation<select name="visitor_relation"><option value="">Select</option><option value="1" ' + (String(record.visitor_relation) === '1' ? 'selected' : '') + '>Father</option><option value="2" ' + (String(record.visitor_relation) === '2' ? 'selected' : '') + '>Mother</option><option value="3" ' + (String(record.visitor_relation) === '3' ? 'selected' : '') + '>Other</option></select></label>',
                        input('father_name', 'Father/Guardian'),
                        select('occupation_id', 'Occupation', payload.options.occupations, record.occupation_id),
                        input('address', 'Address'),
                        input('landline_no', 'Landline'),
                        input('whatsapp', 'WhatsApp'),
                        select('reference', 'Reference', payload.options.references, record.reference),
                        select('source', 'Source', payload.options.sources, record.source),
                        input('date', 'Date', 'date', true),
                        input('follow_up_date', 'Next Follow Up Date', 'date', true),
                        select('assigned', 'Assigned To', payload.options.staff, record.assigned),
                        '<label class="enquiry-span-2">Description<textarea name="description">' + escapeHtml(value(record, 'description')) + '</textarea></label>',
                        '<label class="enquiry-span-2">Note<textarea name="note">' + escapeHtml(value(record, 'note')) + '</textarea></label>',
                    ];
                    const kids = payload.kids || [];
                    const kidsHtml = `<div class="enquiry-span-2"><h4>Proposed Kids for Admission</h4>${kids.map((kid) => `<div class="enquiry-form-grid enquiry-kid-edit-row"><input type="hidden" name="enkidallid[]" value="${kid.id}"><input type="hidden" name="enkidid[]" value="${kid.id}"><label>Class ID<input name="class_id[]" value="${escapeHtml(kid.class_id || '')}"></label><label>Kid Name<input name="kid_name[]" value="${escapeHtml(kid.kid_name || '')}"></label><label>Number of Kids<input name="number_of_kids[]" value="${escapeHtml(kid.number_of_kids || '')}"></label></div>`).join('') || '<p>No proposed kids recorded.</p>'}</div>`;
                    const content = '<form id="enquiry-edit-form" class="enquiry-form-grid">' + fields.join('') + kidsHtml + '<button type="submit" class="adm-btn adm-btn-primary">Update</button></form>';
                    const element = modal('Edit Admission Enquiry', content);
                    element.querySelector('form').addEventListener('submit', async (event) => {
                        event.preventDefault();
                        try {
                            const formData = new FormData(event.currentTarget);
                            formData.append('_method', 'PUT');
                            const response = await request(replaceId(urls.update, id), { method: 'POST', body: formData });
                            notify(response.message);
                            element.classList.add('hidden');
                            refreshTable();
                        } catch (error) { notify(error.message, 'error'); }
                    });
                } catch (error) { notify(error.message, 'error'); }
            };

            const downloadPdf = async (id) => {
                try {
                    const payload = await request(replaceId(urls.details, id));
                    const rows = Object.entries(payload.record).filter(([key, item]) => !['created_at', 'updated_at', 'formatted_date'].includes(key) && item !== null && typeof item !== 'object').map(([key, item]) => [key.replaceAll('_', ' '), String(item)]);
                    window.pdfMake?.createPdf({ content: [{ text: 'Admission Enquiry', style: 'title' }, { table: { widths: ['35%', '65%'], body: rows } }], styles: { title: { fontSize: 16, bold: true, margin: [0, 0, 0, 10] } } }).download(`admission-enquiry-${id}.pdf`);
                } catch (error) { notify(error.message, 'error'); }
            };

            const confirmAction = async (message) => window.Swal ? (await window.Swal.fire({ title: message, icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel' })).isConfirmed : window.confirm(message);

            document.addEventListener('click', async (event) => {
                const button = event.target.closest('[data-enquiry-id]');
                if (!button) return;
                const id = button.dataset.enquiryId;
                if (button.classList.contains('adm-action-call')) return openFollowUp(id);
                if (button.classList.contains('adm-action-view')) return openDetails(id);
                if (button.classList.contains('adm-action-download')) return downloadPdf(id);
                if (button.classList.contains('adm-action-edit')) return openEdit(id);
                if (button.classList.contains('adm-action-delete')) {
                    if (!await confirmAction('Are you sure you want to delete this enquiry?')) return;
                    try { const response = await request(replaceId(urls.destroy, id), { method: 'DELETE' }); notify(response.message); refreshTable(); } catch (error) { notify(error.message, 'error'); }
                }
            });
            document.addEventListener('click', (event) => { if (event.target.closest('[data-follow-up-count]')) openFollowUp(event.target.closest('[data-follow-up-count]').dataset.followUpCount); });
        })();
    </script>
@endpush

@push('scripts')
    <script>
        (() => {
            const modal = document.querySelector('#add-enquiry-modal');
            const openButton = document.querySelector('#open-add-enquiry');
            const form = document.querySelector('#admission-enquiry-modal-form');
            const closeButton = document.querySelector('[data-add-enquiry-close]');
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const errors = document.createElement('div');
            errors.className = 'enquiry-add-errors';
            form?.prepend(errors);

            const close = () => {
                modal?.classList.add('hidden');
                modal?.setAttribute('aria-hidden', 'true');
            };

            openButton?.addEventListener('click', () => {
                form?.reset();
                errors.innerHTML = '';
                modal?.classList.remove('hidden');
                modal?.setAttribute('aria-hidden', 'false');
                form?.querySelector('[name="name"]')?.focus();
            });
            closeButton?.addEventListener('click', close);
            modal?.addEventListener('click', (event) => { if (event.target === modal) close(); });
            document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && !modal?.classList.contains('hidden')) close(); });

            form?.addEventListener('submit', async (event) => {
                event.preventDefault();
                errors.innerHTML = '';
                const button = form.querySelector('[data-submit-button]');
                button.disabled = true;
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        credentials: 'same-origin',
                        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token },
                    });
                    const payload = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        errors.innerHTML = Object.values(payload.errors || {}).flat().map((message) => `<div>${message}</div>`).join('') || (payload.message || 'Unable to save enquiry.');
                        return;
                    }
                    if (window.Swal) {
                        await window.Swal.fire({ icon: 'success', title: 'Success', text: payload.message, timer: 1500, showConfirmButton: false });
                    }
                    close();
                    form.reset();
                    document.querySelector('[data-enquiry-ajax-search]')?.dispatchEvent(new Event('input', { bubbles: true }));
                } catch (error) {
                    errors.textContent = error.message || 'Unable to save enquiry.';
                } finally {
                    button.disabled = false;
                }
            });

            const kids = form?.querySelector('#kids');
            const classOptions = @json($classes->map(fn ($class) => ['id' => $class->id, 'name' => $class->class])->values());
            form?.querySelector('#add-kid')?.addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'kid-row grid gap-3 rounded border border-neutral-200 p-3 md:grid-cols-[1fr_1fr_160px_auto]';
                row.innerHTML = `<label class="field"><span>Class</span><select name="class_id[]" class="control kid-class"><option value="">Select</option>${classOptions.map((item) => `<option value="${item.id}">${item.name}</option>`).join('')}</select></label><label class="field"><span>Kid Name</span><input name="kid_name[]" class="control"></label><label class="field"><span>Number of Kids</span><input type="number" name="number_of_kids[]" value="1" min="1" class="control"></label><button type="button" class="remove-kid self-end rounded bg-red-500 px-3 py-2 text-xs font-bold text-white">Remove</button>`;
                kids.appendChild(row);
            });
            kids?.addEventListener('click', (event) => {
                const remove = event.target.closest('.remove-kid');
                if (remove) remove.closest('.kid-row').remove();
            });
        })();
    </script>
@endpush

@push('scripts')
    <script>
        (() => {
            const modal = document.querySelector('#empty-enquiry-form-modal');
            const openButton = document.querySelector('#open-empty-enquiry-form');
            const closeButton = document.querySelector('[data-empty-enquiry-close]');
            const downloadButton = document.querySelector('#download-empty-enquiry-form');
            const classSelect = document.querySelector('#empty-enquiry-class');
            const error = document.querySelector('#empty-enquiry-error');

            openButton?.addEventListener('click', () => {
                error.textContent = '';
                classSelect.value = '';
                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');
            });

            const close = () => {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
            };

            closeButton?.addEventListener('click', close);
            modal?.addEventListener('click', (event) => {
                if (event.target === modal) close();
            });

            downloadButton?.addEventListener('click', () => {
                if (!classSelect.value) {
                    error.textContent = 'Please select a class.';
                    return;
                }

                if (!window.pdfMake) {
                    error.textContent = 'PDF service is not available.';
                    return;
                }

                const className = classSelect.options[classSelect.selectedIndex].text;
                window.pdfMake.createPdf({
                    content: [
                        { text: 'Admission Enquiry Form', style: 'title' },
                        { text: 'Class: ' + className, margin: [0, 0, 0, 16] },
                        {
                            table: {
                                widths: ['35%', '65%'],
                                body: [
                                    ['Visitor Name', ''],
                                    ['Phone', ''],
                                    ['Visitor Relation', ''],
                                    ['Email', ''],
                                    ['Father/Guardian', ''],
                                    ['Occupation', ''],
                                    ['Address', ''],
                                    ['Reference', ''],
                                    ['Source', ''],
                                    ['Date', ''],
                                    ['Next Follow Up Date', ''],
                                    ['Description', ''],
                                    ['Note', ''],
                                ],
                            },
                        },
                    ],
                    styles: { title: { fontSize: 16, bold: true, margin: [0, 0, 0, 10] } },
                }).download('admission-enquiry-empty-form.pdf');
                close();
            });
        })();
    </script>
@endpush

@push('styles')
    <style>
        .enquiry-modal-backdrop{position:fixed;inset:0;z-index:1000;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.45);padding:20px}.enquiry-modal-backdrop.hidden{display:none}.enquiry-modal{width:min(1000px,100%);max-height:90vh;overflow:auto;border-radius:8px;background:#fff;box-shadow:0 15px 45px rgba(0,0,0,.25)}.enquiry-modal-header{display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #ddd;padding:14px 18px}.enquiry-modal-header h3{margin:0;font-size:18px}.enquiry-modal-header button{border:0;background:transparent;font-size:26px}.enquiry-modal-body{padding:18px}.enquiry-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.enquiry-form-grid label{display:flex;flex-direction:column;gap:4px;font-size:13px;font-weight:600;color:#374151}.enquiry-form-grid input,.enquiry-form-grid select,.enquiry-form-grid textarea{border:1px solid #cbd5e1;border-radius:4px;padding:8px;font-weight:400}.enquiry-form-grid textarea{min-height:75px}.enquiry-span-2{grid-column:span 2}.enquiry-detail-table{width:100%;border-collapse:collapse;margin-bottom:16px}.enquiry-detail-table th,.enquiry-detail-table td{border:1px solid #ddd;padding:8px;text-align:left}.enquiry-detail-table th{background:#f3f4f6}.enquiry-followup-layout{display:grid;grid-template-columns:minmax(0,2fr) minmax(220px,1fr);gap:22px}.enquiry-summary{border-left:1px solid #ddd;padding-left:18px}.enquiry-summary select{display:block;margin:7px 0 15px;padding:7px;width:100%}.enquiry-history-row{display:flex;justify-content:space-between;gap:10px;border-bottom:1px solid #e5e7eb;padding:10px 0}.enquiry-history-row p{margin:5px 0}.enquiry-history-row small{color:#6b7280}@media(max-width:700px){.enquiry-form-grid,.enquiry-followup-layout{grid-template-columns:1fr}.enquiry-span-2{grid-column:auto}.enquiry-summary{border-left:0;padding-left:0}}
    </style>
@endpush

@push('scripts')
    <script>
        (() => {
            const table = document.querySelector('#enquirytable');
            const searchForm = document.querySelector('.adm-search-form');
            const searchInput = document.querySelector('[data-enquiry-ajax-search]');
            const pagination = document.querySelector('#enquiry-pagination');
            const dataUrl = @json(route('admin.adm.enquiries.data', absolute: false));
            const storageKey = 'admin.admission-enquiry.visible-columns';
            let timer;
            let requestController;

            if (!table || !searchForm || !searchInput || !pagination) return;

            const initialParams = new URLSearchParams(window.location.search);
            table.dataset.sort = initialParams.get('sort') || 'date';
            table.dataset.direction = initialParams.get('direction') || 'desc';

            const escapeHtml = (value) => String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');

            const actionMarkup = (record) => `
                <div class="adm-action-group">
                    <button type="button" class="adm-action-btn adm-action-call" data-enquiry-id="${record.id}" aria-label="Follow Up"><i class="fa-solid fa-phone"></i></button>
                    <button type="button" class="adm-action-btn adm-action-view" data-enquiry-id="${record.id}" aria-label="View"><i class="fa-regular fa-eye"></i></button>
                    <button type="button" class="adm-action-btn adm-action-download" data-enquiry-id="${record.id}" aria-label="Download"><i class="fa-solid fa-cloud-arrow-down"></i></button>
                    <button type="button" class="adm-action-btn adm-action-edit" data-enquiry-id="${record.id}" aria-label="Edit"><i class="fa-solid fa-pen"></i></button>
                    <button type="button" class="adm-action-btn adm-action-delete" data-enquiry-id="${record.id}" aria-label="Delete"><i class="fa-solid fa-xmark"></i></button>
                </div>`;

            const applyColumnVisibility = () => {
                let indexes = JSON.parse(localStorage.getItem(storageKey) || 'null');
                if (!Array.isArray(indexes)) indexes = [...Array(10).keys()];
                table.querySelectorAll('tr').forEach((row) => row.querySelectorAll(':scope > *').forEach((cell, index) => {
                    cell.classList.toggle('adm-hidden-column', !indexes.includes(index));
                }));
            };

            const renderRows = (records, currentPage) => {
                const body = table.tBodies[0];
                if (!records.length) {
                    body.innerHTML = '<tr class="adm-empty-row"><td colspan="11" class="py-10 text-sm text-[#6b7280]">No admission enquiries found.</td></tr>';
                    return;
                }

                body.innerHTML = records.map((record, index) => `<tr>
                    <td>${((currentPage - 1) * 10) + index + 1}</td>
                    <td>${escapeHtml(record.date)}</td>
                    <td>${escapeHtml(record.name)}</td>
                    <td>${escapeHtml(record.contact)}</td>
                    <td>${escapeHtml(record.relation)}</td>
                    <td>${escapeHtml(record.source)}</td>
                    <td>${escapeHtml(record.reference)}</td>
                    <td>${escapeHtml(record.assigned_to)}</td>
                    <td class="text-center"><span class="adm-badge" data-follow-up-count="${record.id}">${escapeHtml(record.follow_up)}</span></td>
                    <td>${escapeHtml(record.status)}</td>
                    <td>${actionMarkup(record)}</td>
                </tr>`).join('');
                applyColumnVisibility();
            };

            const renderPagination = (currentPage, lastPage, total) => {
                pagination.style.display = lastPage > 1 ? 'flex' : 'none';
                pagination.innerHTML = `
                    <button type="button" class="adm-pagination-link ${currentPage === 1 ? 'opacity-50' : ''}" data-enquiry-page="${Math.max(1, currentPage - 1)}" ${currentPage === 1 ? 'disabled' : ''}>‹</button>
                    <span class="adm-pagination-current">${currentPage}</span>
                    <button type="button" class="adm-pagination-link ${currentPage === lastPage ? 'opacity-50' : ''}" data-enquiry-page="${Math.min(lastPage, currentPage + 1)}" ${currentPage === lastPage ? 'disabled' : ''}>›</button>
                    <span class="sr-only">${total} results</span>`;
            };

            const loadRecords = async (page = 1) => {
                requestController?.abort();
                requestController = new AbortController();
                const params = new URLSearchParams(new FormData(searchForm));
                params.set('page', page);
                params.set('sort', table.dataset.sort || 'date');
                params.set('direction', table.dataset.direction || 'desc');
                const url = `${dataUrl}?${params.toString()}`;
                searchInput.setAttribute('aria-busy', 'true');

                try {
                    const response = await fetch(url, {
                        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                        signal: requestController.signal,
                    });
                    if (!response.ok) throw new Error('Unable to load admission enquiries.');
                    const payload = await response.json();
                    renderRows(payload.data || [], payload.current_page || 1);
                    renderPagination(payload.current_page || 1, payload.last_page || 1, payload.total || 0);
                    window.history.replaceState({}, '', `${window.location.pathname}?${params.toString()}`);
                } catch (error) {
                    if (error.name !== 'AbortError') window.alert(error.message);
                } finally {
                    searchInput.removeAttribute('aria-busy');
                }
            };

            searchInput.addEventListener('input', () => {
                window.clearTimeout(timer);
                timer = window.setTimeout(() => loadRecords(1), 350);
            });
            searchForm.addEventListener('submit', (event) => event.preventDefault());
            pagination.addEventListener('click', (event) => {
                const page = event.target.closest('[data-enquiry-page]')?.dataset.enquiryPage;
                if (page) loadRecords(Number(page));
            });
            table.querySelector('thead').addEventListener('click', (event) => {
                const header = event.target.closest('[data-enquiry-sort]');
                if (!header) return;
                const sort = header.dataset.enquirySort;
                table.dataset.direction = table.dataset.sort === sort && table.dataset.direction === 'asc' ? 'desc' : 'asc';
                table.dataset.sort = sort;
                loadRecords(1);
            });
        })();
    </script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/dist/datatables/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/dist/datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/dist/datatables/js/vfs_fonts.js') }}"></script>
    <script>
        (() => {
            const table = document.querySelector('#enquirytable');
            const toolbar = document.querySelector('#enquiry-export-toolbar');
            const storageKey = 'admin.admission-enquiry.visible-columns';

            if (!table || !toolbar) return;

            const visibleIndexes = () => [...table.querySelectorAll('thead th')]
                .map((_, index) => index)
                .filter((index) => !table.querySelector(`thead th:nth-child(${index + 1})`)?.classList.contains('adm-hidden-column'));

            const exportRows = (includeAction = false) => {
                const indexes = visibleIndexes().filter((index) => includeAction || index !== 10);
                const headers = indexes.map((index) => table.tHead.rows[0].cells[index].innerText.trim());
                const rows = [...table.tBodies[0].rows]
                    .filter((row) => !row.classList.contains('adm-empty-row'))
                    .map((row) => indexes.map((index) => row.cells[index]?.innerText.trim() ?? ''));
                return { headers, rows };
            };

            const download = (content, name, type) => {
                const link = document.createElement('a');
                link.href = URL.createObjectURL(new Blob([content], { type }));
                link.download = name;
                link.click();
                URL.revokeObjectURL(link.href);
            };

            const csv = (data) => [data.headers, ...data.rows]
                .map((row) => row.map((value) => `"${String(value).replaceAll('"', '""')}"`).join(','))
                .join('\r\n');

            const copy = async (data) => {
                const text = [data.headers, ...data.rows].map((row) => row.join('\t')).join('\n');
                await navigator.clipboard.writeText(text);
                window.alert('Admission enquiry data copied.');
            };

            const excel = (data) => {
                if (!window.JSZip) return download(csv(data), 'admission-enquiry.csv', 'text/csv;charset=utf-8');
                const escapeXml = (value) => String(value).replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;');
                const rows = [data.headers, ...data.rows].map((row, rowIndex) => `<row r="${rowIndex + 1}">${row.map((value, columnIndex) => `<c r="${String.fromCharCode(65 + columnIndex)}${rowIndex + 1}" t="inlineStr"><is><t>${escapeXml(value)}</t></is></c>`).join('')}</row>`).join('');
                const zip = new JSZip();
                zip.file('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');
                zip.file('_rels/.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
                zip.file('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Admission Enquiry" sheetId="1" r:id="rId1"/></sheets></workbook>');
                zip.file('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>');
                zip.file('xl/worksheets/sheet1.xml', `<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>${rows}</sheetData></worksheet>`);
                zip.generateAsync({ type: 'blob' }).then((blob) => download(blob, 'admission-enquiry.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'));
            };

            const pdf = (data) => window.pdfMake?.createPdf({ content: [{ text: 'Admission Enquiry', style: 'title' }, { table: { headerRows: 1, widths: Array(data.headers.length).fill('*'), body: [data.headers, ...data.rows] } }], styles: { title: { fontSize: 16, bold: true, margin: [0, 0, 0, 10] } } }).download('admission-enquiry.pdf');

            const print = (data) => {
                const win = window.open('', '_blank');
                win.document.write(`<html><head><title>Admission Enquiry</title><style>body{font:10pt Arial}table{border-collapse:collapse;width:100%}th,td{border:1px solid #777;padding:5px;text-align:left}th{background:#eee}</style></head><body><h2>Admission Enquiry</h2><table><thead><tr>${data.headers.map((header) => `<th>${header}</th>`).join('')}</tr></thead><tbody>${data.rows.map((row) => `<tr>${row.map((cell) => `<td>${cell}</td>`).join('')}</tr>`).join('')}</tbody></table></body></html>`);
                win.document.close(); win.focus(); win.print();
            };

            const applyColumns = (indexes) => {
                table.querySelectorAll('tr').forEach((row) => row.querySelectorAll(':scope > *').forEach((cell, index) => cell.classList.toggle('adm-hidden-column', !indexes.includes(index))));
                localStorage.setItem(storageKey, JSON.stringify(indexes));
                table.querySelectorAll('[data-enquiry-column]').forEach((checkbox) => checkbox.checked = indexes.includes(Number(checkbox.dataset.enquiryColumn)));
            };

            const saved = JSON.parse(localStorage.getItem(storageKey) || 'null');
            applyColumns(Array.isArray(saved) ? saved : [...Array(10).keys()]);

            toolbar.addEventListener('click', (event) => {
                const button = event.target.closest('[data-enquiry-export]');
                if (button) {
                    const data = exportRows();
                    const action = button.dataset.enquiryExport;
                    if (action === 'copy') copy(data).catch(() => window.alert('Clipboard permission was denied.'));
                    if (action === 'excel') excel(data);
                    if (action === 'csv') download('\ufeff' + csv(data), 'admission-enquiry.csv', 'text/csv;charset=utf-8');
                    if (action === 'pdf') pdf(data);
                    if (action === 'print') print(data);
                    if (action === 'columns') document.querySelector('#enquiry-column-menu').classList.toggle('hidden');
                }
                const checkbox = event.target.closest('[data-enquiry-column]');
                if (checkbox) applyColumns([...table.querySelectorAll('[data-enquiry-column]:checked')].map((item) => Number(item.dataset.enquiryColumn)));
                if (event.target.closest('[data-enquiry-columns-reset]')) applyColumns([...Array(10).keys()]);
            });
        })();
    </script>
@endpush
