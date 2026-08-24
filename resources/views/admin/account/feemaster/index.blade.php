@extends('admin.layouts.app')

@section('title', 'Fee Structure : ' . ($current_session_name ?? ''))

@push('styles')
<style>
    /* ===================================================
       CMSC Fee Master / Fee Structure Exact Styles
       =================================================== */

    .feemaster-container {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #333;
        font-size: 14px;
    }

    .feemaster-grid {
        display: grid;
        grid-template-columns: minmax(0, 4fr) minmax(0, 8fr);
        gap: 15px;
        align-items: start;
    }

    @media (max-width: 991px) {
        .feemaster-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Box Cards */
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
        flex-wrap: wrap;
        gap: 10px;
    }

    .box-title {
        display: inline-block;
        font-size: 17px;
        margin: 0;
        line-height: 1.2;
        font-weight: 600;
        color: #333333;
    }

    .box-body {
        padding: 15px;
        background: #fff;
    }

    .box-footer {
        border-top: 1px solid #f4f4f4;
        padding: 10px 15px;
        background-color: #fff;
        text-align: right;
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 13px;
        color: #333;
    }

    .form-group label .req {
        color: #ff0000;
        font-size: 14px;
        font-weight: bold;
    }

    .form-control-cmsc {
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        background-color: #fff;
        border: 1px solid #d2d6de;
        border-radius: 4px;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        font-size: 13px;
        color: #555;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        outline: none;
        box-sizing: border-box;
    }

    .form-control-cmsc:focus {
        border-color: #66afe9;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
    }

    textarea.form-control-cmsc {
        height: auto;
        min-height: 70px;
        resize: vertical;
    }

    /* Action Buttons */
    .btn-save-cmsc {
        background-color: #1e3a8a;
        color: #fff;
        border: 1px solid #1e3a8a;
        padding: 6px 20px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-save-cmsc:hover {
        background-color: #162c6d;
        border-color: #162c6d;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .btn-pdf-download {
        background-color: #f39c12;
        color: #ffffff;
        border: 1px solid #e08e0b;
        padding: 5px 12px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background-color 0.2s ease;
    }

    .btn-pdf-download:hover {
        background-color: #d68400;
        color: #ffffff;
    }

    /* Alerts */
    .alert-cmsc-success {
        background-color: #dff0d8;
        border: 1px solid #d6e9c6;
        color: #3c763d;
        padding: 10px 15px;
        border-radius: 4px;
        margin-bottom: 15px;
        font-size: 13px;
    }

    .alert-cmsc-danger {
        background-color: #f2dede;
        border: 1px solid #ebccd1;
        color: #a94442;
        padding: 10px 15px;
        border-radius: 4px;
        margin-bottom: 15px;
        font-size: 13px;
    }

    /* Toolbar above Table */
    .dt-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .dt-search-input {
        height: 32px;
        width: 190px;
        padding: 4px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .dt-search-input:focus {
        border-color: #1e3a8a;
    }

    .dt-buttons-group {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .dt-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background: #1e3a8a;
        border: 1px solid #1e3a8a;
        color: #ffffff;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
    }

    .dt-btn:hover {
        background: #162c6d;
        border-color: #162c6d;
        transform: translateY(-1px);
    }

    .dt-btn[data-tooltip]::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 115%;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: #fff;
        font-size: 11px;
        padding: 3px 6px;
        border-radius: 3px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
        z-index: 100;
    }

    .dt-btn[data-tooltip]:hover::after {
        opacity: 1;
    }

    /* Fee Table */
    .cmsc-table-wrap {
        overflow-x: auto;
        border: 1px solid #d2d6de;
    }

    .cmsc-fee-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        background: #fff;
    }

    .cmsc-fee-table thead th {
        background-color: #1e3a8a;
        color: #ffffff;
        font-weight: 600;
        padding: 10px 12px;
        text-align: left;
        border: 1px solid #162c6d;
        white-space: nowrap;
        user-select: none;
    }

    .cmsc-fee-table thead th .sort-arrow {
        font-size: 10px;
        margin-left: 4px;
        opacity: 0.8;
    }

    .cmsc-fee-table tbody td {
        padding: 8px 12px;
        border: 1px solid #e9ecef;
        vertical-align: middle;
        color: #333;
    }

    .cmsc-fee-table tbody tr.class-group-row td {
        background-color: #ffffff;
        font-weight: 600;
        color: #333;
    }

    .cmsc-fee-table tbody tr.fee-item-row td {
        background-color: #ffffff;
    }

    .cmsc-fee-table tbody tr.fee-item-row:hover td {
        background-color: #f8fafd;
    }

    .cmsc-fee-table tbody tr.class-total-row td {
        background-color: #ffffff;
        font-weight: bold;
    }

    /* Action Buttons in Table */
    .btn-action-edit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background-color: #1e3a8a;
        color: #ffffff !important;
        border-radius: 3px;
        font-size: 11px;
        text-decoration: none !important;
        transition: background-color 0.2s ease;
        margin-right: 4px;
        cursor: pointer;
        position: relative;
        z-index: 2;
    }

    .btn-action-edit:hover {
        background-color: #162c6d;
        color: #ffffff;
    }

    .btn-action-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background-color: #d9534f;
        color: #ffffff;
        border: none;
        border-radius: 3px;
        font-size: 11px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .btn-action-delete:hover {
        background-color: #c9302c;
    }

    /* Popover Tooltip */
    .fee-popover-trigger {
        color: #333;
        text-decoration: none;
        cursor: pointer;
        position: relative;
        display: inline-block;
    }

    .fee-popover-trigger:hover {
        color: #1e3a8a;
    }

    .fee-popover-box {
        position: fixed;
        display: none;
        background: #ffffff;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        padding: 10px 14px;
        font-size: 12px;
        z-index: 10000;
        max-width: 260px;
        pointer-events: none;
    }

    /* Table Footer & Pagination */
    .table-footer-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 10px;
        font-size: 12px;
        color: #666;
    }

    .pagination-pills {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
        padding: 0 6px;
        border: 1px solid #d2d6de;
        border-radius: 3px;
        background: #fff;
        color: #333;
        font-size: 11px;
        cursor: pointer;
        text-decoration: none;
    }

    .pagination-btn.active {
        background: #1e3a8a;
        color: #fff;
        border-color: #1e3a8a;
    }

    #exportToast {
        position: fixed;
        bottom: 25px;
        right: 25px;
        background: #1e3a8a;
        color: #fff;
        padding: 10px 18px;
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
<div class="feemaster-container">
    <div class="feemaster-grid">
        {{-- Left Side: Add Fee Structure Card --}}
        <div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Add Fee Structure : {{ $current_session_name }}</h3>
                </div>

                <form id="feemasterform" action="{{ url('admin/account/feemaster/' . $brc_id) }}" method="POST">
                    @csrf
                    <div class="box-body">
                        {{-- Flash Messages --}}
                        @if (session('success'))
                            <div class="alert-cmsc-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert-cmsc-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Branch Dropdown --}}
                        <div class="form-group">
                            <label for="brc_id">Branch <span class="req">*</span></label>
                            <select id="brc_id" name="brc_id" class="form-control-cmsc" onchange="getBranchByID(this.value)">
                                <option value="">Select</option>
                                @foreach ($branchlist as $brc)
                                    <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                        {{ $brc->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brc_id')
                                <span style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Class Dropdown --}}
                        <div class="form-group">
                            <label for="class_id">Class <span class="req">*</span></label>
                            <select id="class_id" name="class_id" class="form-control-cmsc" required>
                                <option value="">Select</option>
                                @foreach ($classlist as $class)
                                    <option value="{{ $class->id }}" {{ (string)old('class_id') === (string)$class->id ? 'selected' : '' }}>
                                        {{ $class->class }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <span style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Frequency Dropdown --}}
                        <div class="form-group">
                            <label for="frequency">Frequency <span class="req">*</span></label>
                            <select id="frequency" name="frequency" class="form-control-cmsc" required>
                                <option value="">Select</option>
                                <option value="One Time" {{ old('frequency') === 'One Time' ? 'selected' : '' }}>One Time</option>
                                <option value="Monthly" {{ old('frequency') === 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="Yearly" {{ old('frequency') === 'Yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            @error('frequency')
                                <span style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Month Count (shown when installments/both mode) --}}
                        <div class="form-group fee-month-count-group" id="monthCountGroup" style="{{ $show_month_count ? '' : 'display:none;' }}">
                            <label for="month_count">Month <span class="req">*</span></label>
                            <input id="month_count" name="month_count" type="number" min="0" class="form-control-cmsc" value="{{ old('month_count', 0) }}" />
                            @error('month_count')
                                <span style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Fee Type Dropdown --}}
                        <div class="form-group">
                            <label for="feetype_id">Fee Type <span class="req">*</span></label>
                            <select id="feetype_id" name="feetype_id" class="form-control-cmsc" required>
                                <option value="">Select</option>
                                @foreach ($feetypeList as $feetype)
                                    <option value="{{ $feetype->id }}" {{ (string)old('feetype_id') === (string)$feetype->id ? 'selected' : '' }}>
                                        {{ $feetype->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('feetype_id')
                                <span style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Amount Input --}}
                        <div class="form-group">
                            <label for="amount_display">Amount({{ $currency_symbol }}) <span class="req">*</span></label>
                            <input id="amount_display" type="number" step="any" placeholder="" class="form-control-cmsc" value="{{ old('amount_display', old('amount')) }}" required />
                            <input id="amount" name="amount" type="hidden" value="{{ old('amount') }}" />
                            @error('amount')
                                <span style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Description Textarea --}}
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control-cmsc" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <span style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn-save-cmsc">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Side: Fee Structure List Card --}}
        <div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Fee Structure List : {{ $current_session_name }}</h3>
                    <a href="{{ route('admin.account.fee-master.pdf', ['branch_id' => $brc_id]) }}" class="btn-pdf-download">
                        <i class="fa fa-download"></i> PDF Download
                    </a>
                </div>

                <div class="box-body">
                    {{-- Toolbar: Search on Left, Export Buttons on Right --}}
                    <div class="dt-toolbar">
                        <div>
                            <input type="text" id="tableSearch" placeholder="Search..." class="dt-search-input" onkeyup="filterFeeTable()" autocomplete="off" />
                        </div>

                        <div class="dt-buttons-group">
                            <button type="button" class="dt-btn" data-tooltip="Copy" onclick="exportFeeTable('copy')">
                                <i class="fa fa-copy"></i>
                            </button>
                            <button type="button" class="dt-btn" data-tooltip="Excel" onclick="exportFeeTable('excel')">
                                <i class="fa fa-file-excel"></i>
                            </button>
                            <button type="button" class="dt-btn" data-tooltip="CSV" onclick="exportFeeTable('csv')">
                                <i class="fa fa-file-csv"></i>
                            </button>
                            <button type="button" class="dt-btn" data-tooltip="PDF" onclick="exportFeeTable('pdf')">
                                <i class="fa fa-file-pdf"></i>
                            </button>
                            <button type="button" class="dt-btn" data-tooltip="Print" onclick="exportFeeTable('print')">
                                <i class="fa fa-print"></i>
                            </button>
                            <button type="button" class="dt-btn" data-tooltip="Columns" onclick="exportFeeTable('columns')">
                                <i class="fa fa-columns"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Table List --}}
                    @php
                        $totalFeeRecords = 0;
                    @endphp
                    <div class="cmsc-table-wrap">
                        <table class="cmsc-fee-table" id="feeStructureTable">
                            <thead>
                                <tr>
                                    <th class="sortable" data-sort-col="branch" onclick="sortFeeTable('branch')" style="width: 20%; cursor: pointer;" title="Sort by Branch">Branch <span class="sort-arrow">▾</span></th>
                                    <th class="sortable" data-sort-col="class" onclick="sortFeeTable('class')" style="width: 20%; cursor: pointer;" title="Sort by Class">Class <span class="sort-arrow">▾</span></th>
                                    <th class="sortable" data-sort-col="fee_head" onclick="sortFeeTable('fee_head')" style="width: 35%; cursor: pointer;" title="Sort by Fee Head">Fee Head <span class="sort-arrow">▾</span></th>
                                    <th class="sortable" data-sort-col="amount" onclick="sortFeeTable('amount')" style="width: 15%; text-align: right; cursor: pointer;" title="Sort by Amount">Amount <span class="sort-arrow">▾</span></th>
                                    <th style="width: 10%; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($feemasterList as $feegroup)
                                    {{-- Group Header Row: Branch & Class Name --}}
                                    <tr class="class-group-row" data-group-class="{{ strtolower($feegroup->class_name ?? '') }}" data-group-branch="{{ strtolower($feegroup->branch_name ?? '') }}">
                                        <td>{{ $feegroup->branch_name ?? 'Main Campus' }}</td>
                                        <td>{{ $feegroup->class_name ?? '' }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    {{-- Fee Types Under Class --}}
                                    @php
                                        $groupTotal = 0;
                                    @endphp
                                    @if (!empty($feegroup->feetypes))
                                        @foreach ($feegroup->feetypes as $item)
                                            @php
                                                $totalFeeRecords++;
                                                $groupTotal += (float) $item->amount;
                                                $monthLabel = !empty($item->month_count) ? ' - ' . $item->month_count . ' Month' : '';
                                                $feeTitle = $item->type . ' ( ' . $item->frequency . $monthLabel . ' )';
                                            @endphp
                                            <tr class="fee-item-row" data-search="{{ strtolower(($feegroup->branch_name ?? '') . ' ' . ($feegroup->class_name ?? '') . ' ' . $item->type . ' ' . $item->frequency) }}">
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <span class="fee-popover-trigger" onmouseenter="showFeePopover(event, '{{ addslashes($item->note ? $item->note : 'No description') }}')" onmouseleave="hideFeePopover()">
                                                        {{ $feeTitle }}
                                                    </span>
                                                </td>
                                                <td style="text-align: right;">
                                                    {{ number_format((float) $item->amount, 0, '.', '') }}
                                                </td>
                                                <td style="text-align: center; white-space: nowrap;">
                                                    {{-- Edit Button --}}
                                                    <a href="{{ url('admin/account/feemaster/edit/' . $item->id . '/' . ($item->brc_id ?? $brc_id)) }}" class="btn-action-edit" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>

                                                    {{-- Delete Button --}}
                                                    <form action="{{ url('admin/account/feemaster/deletegrp/' . $item->id . '/' . ($item->brc_id ?? $brc_id)) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this fee structure?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action-delete" title="Delete">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- Class Group Total Row --}}
                                        <tr class="class-total-row">
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: right;">Total Amount:</td>
                                            <td style="text-align: right;">{{ number_format($groupTotal, 0, '.', '') }}</td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 25px; color: #777;">
                                            No fee structure records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Table Footer Summary & Pagination --}}
                    <div class="table-footer-bar">
                        <div>
                            Records: 1 to {{ $totalFeeRecords }} of {{ $totalFeeRecords }}
                        </div>
                        <div class="pagination-pills">
                            <span class="pagination-btn"><</span>
                            <span class="pagination-btn active">1</span>
                            <span class="pagination-btn">></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Popover Tooltip Box --}}
<div id="feePopoverBox" class="fee-popover-box"></div>

{{-- Toast Notification --}}
<div id="exportToast">Table copied to clipboard!</div>

@push('scripts')
<script>
    function getBranchByID(val) {
        if (val) {
            window.location.href = "{{ url('admin/account/feemaster') }}/" + val;
        }
    }

    function getMonthCountByFrequency(frequency) {
        if (frequency === 'Monthly') {
            return 12;
        }
        if (frequency === 'Yearly') {
            return 1;
        }
        if (frequency === 'One Time') {
            return 1;
        }
        return 0;
    }

    function updateFeeStructureAmount() {
        var showMonthCount = {{ $show_month_count ? 'true' : 'false' }};
        var frequency = document.getElementById('frequency').value;
        var displayInput = document.getElementById('amount_display');
        var monthInput = document.getElementById('month_count');
        var amountHidden = document.getElementById('amount');

        var baseAmount = parseFloat(displayInput.value) || 0;
        var monthCount = showMonthCount ? (parseFloat(monthInput.value) || 0) : 0;

        if (showMonthCount && document.activeElement !== monthInput) {
            monthCount = getMonthCountByFrequency(frequency);
            monthInput.value = monthCount;
        }

        var finalAmount = (showMonthCount && monthCount > 0) ? (baseAmount * monthCount) : baseAmount;
        amountHidden.value = finalAmount > 0 ? finalAmount.toFixed(2) : (baseAmount > 0 ? baseAmount.toFixed(2) : '');
    }

    document.addEventListener('DOMContentLoaded', function() {
        var freqElem = document.getElementById('frequency');
        var dispElem = document.getElementById('amount_display');
        var monthElem = document.getElementById('month_count');

        if (freqElem) {
            freqElem.addEventListener('change', updateFeeStructureAmount);
        }
        if (dispElem) {
            dispElem.addEventListener('input', updateFeeStructureAmount);
            dispElem.addEventListener('keyup', updateFeeStructureAmount);
        }
        if (monthElem) {
            monthElem.addEventListener('input', updateFeeStructureAmount);
            monthElem.addEventListener('keyup', updateFeeStructureAmount);
        }

        updateFeeStructureAmount();
    });

    // Popover handler
    function showFeePopover(e, text) {
        var popover = document.getElementById('feePopoverBox');
        if (!popover) return;
        popover.innerHTML = '<strong style="color:#1e3a8a;">Note:</strong><br/>' + text;
        popover.style.display = 'block';
        popover.style.left = (e.clientX + 15) + 'px';
        popover.style.top = (e.clientY - 10) + 'px';
    }

    function hideFeePopover() {
        var popover = document.getElementById('feePopoverBox');
        if (popover) {
            popover.style.display = 'none';
        }
    }

    // Live Search Filter
    function filterFeeTable() {
        var input = document.getElementById('tableSearch');
        var filter = input.value.toLowerCase().trim();
        var table = document.getElementById('feeStructureTable');
        var rows = table.querySelectorAll('tbody tr');

        if (!filter) {
            rows.forEach(function(r) { r.style.display = ''; });
            return;
        }

        // Filter through rows
        var currentGroupRow = null;
        var groupHasVisibleChild = false;

        rows.forEach(function(row) {
            if (row.classList.contains('class-group-row')) {
                if (currentGroupRow && !groupHasVisibleChild) {
                    currentGroupRow.style.display = 'none';
                }
                currentGroupRow = row;
                groupHasVisibleChild = false;
                var gText = (row.getAttribute('data-group-class') || '') + ' ' + (row.getAttribute('data-group-branch') || '');
                if (gText.indexOf(filter) > -1) {
                    row.style.display = '';
                    groupHasVisibleChild = true;
                } else {
                    row.style.display = '';
                }
            } else if (row.classList.contains('fee-item-row')) {
                var search = row.getAttribute('data-search') || row.innerText.toLowerCase();
                if (search.indexOf(filter) > -1) {
                    row.style.display = '';
                    groupHasVisibleChild = true;
                    if (currentGroupRow) currentGroupRow.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            } else if (row.classList.contains('class-total-row')) {
                row.style.display = groupHasVisibleChild ? '' : 'none';
            }
        });

        if (currentGroupRow && !groupHasVisibleChild) {
            currentGroupRow.style.display = 'none';
        }
    }

    // Export Table Functions
    function showToast(msg) {
        var toast = document.getElementById('exportToast');
        toast.innerText = msg;
        toast.style.display = 'block';
        setTimeout(function() { toast.style.display = 'none'; }, 2200);
    }

    function exportFeeTable(type) {
        var table = document.getElementById('feeStructureTable');
        if (!table) return;

        if (type === 'copy') {
            var text = '';
            var rows = table.querySelectorAll('tr');
            rows.forEach(function(r) {
                if (r.style.display === 'none') return;
                var cols = r.querySelectorAll('th, td');
                var rowData = [];
                cols.forEach(function(c, idx) {
                    if (idx < cols.length - 1) {
                        rowData.push(c.innerText.trim());
                    }
                });
                text += rowData.join("\t") + "\n";
            });

            navigator.clipboard.writeText(text).then(function() {
                showToast('Table copied to clipboard!');
            });
        } else if (type === 'excel' || type === 'csv') {
            var csv = [];
            var rows = table.querySelectorAll('tr');
            rows.forEach(function(r) {
                if (r.style.display === 'none') return;
                var cols = r.querySelectorAll('th, td');
                var rowData = [];
                cols.forEach(function(c, idx) {
                    if (idx < cols.length - 1) {
                        rowData.push('"' + c.innerText.replace(/"/g, '""').trim() + '"');
                    }
                });
                csv.push(rowData.join(type === 'csv' ? ',' : "\t"));
            });

            var blob = new Blob([csv.join("\n")], { type: 'text/' + (type === 'csv' ? 'csv' : 'vnd.ms-excel') });
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'fee_structure_' + (new Date().toISOString().slice(0,10)) + '.' + (type === 'csv' ? 'csv' : 'xls');
            a.click();
            showToast('Exported to ' + type.toUpperCase() + '!');
        } else if (type === 'pdf') {
            window.location.href = "{{ route('admin.account.fee-master.pdf', ['branch_id' => $brc_id]) }}";
        } else if (type === 'print') {
            window.open("{{ route('admin.account.fee-master.pdf', ['branch_id' => $brc_id]) }}?print=1", "_blank");
        } else if (type === 'columns') {
            showToast('All 5 columns visible');
        }
    }

    // Interactive Column Sorting
    var sortDirections = {
        branch: 'asc',
        class: 'asc',
        fee_head: 'asc',
        amount: 'asc'
    };

    function sortFeeTable(column) {
        var table = document.getElementById('feeStructureTable');
        if (!table) return;
        var tbody = table.querySelector('tbody');
        if (!tbody) return;

        // Toggle sort direction
        var currentDir = sortDirections[column] || 'asc';
        var newDir = currentDir === 'asc' ? 'desc' : 'asc';
        sortDirections[column] = newDir;

        // Update header indicators
        var headers = table.querySelectorAll('thead th.sortable');
        headers.forEach(function(th) {
            var col = th.getAttribute('data-sort-col');
            var arrow = th.querySelector('.sort-arrow');
            if (col === column) {
                if (arrow) arrow.innerHTML = newDir === 'asc' ? ' ▲' : ' ▼';
            } else {
                if (arrow) arrow.innerHTML = ' ▾';
            }
        });

        // Parse class groups from tbody
        var rows = Array.from(tbody.querySelectorAll('tr'));
        var groups = [];
        var currentGroup = null;

        rows.forEach(function(row) {
            if (row.classList.contains('class-group-row')) {
                if (currentGroup) {
                    groups.push(currentGroup);
                }
                currentGroup = {
                    headerRow: row,
                    branch: (row.cells[0] ? row.cells[0].innerText : '').trim().toLowerCase(),
                    className: (row.cells[1] ? row.cells[1].innerText : '').trim().toLowerCase(),
                    itemRows: [],
                    totalRow: null,
                    totalAmount: 0
                };
            } else if (row.classList.contains('fee-item-row')) {
                if (currentGroup) {
                    var feeHead = (row.cells[2] ? row.cells[2].innerText : '').trim().toLowerCase();
                    var amountText = (row.cells[3] ? row.cells[3].innerText : '').replace(/[^0-9.-]+/g, '');
                    var amount = parseFloat(amountText) || 0;
                    currentGroup.itemRows.push({
                        row: row,
                        feeHead: feeHead,
                        amount: amount
                    });
                }
            } else if (row.classList.contains('class-total-row')) {
                if (currentGroup) {
                    currentGroup.totalRow = row;
                    var totalText = (row.cells[3] ? row.cells[3].innerText : '').replace(/[^0-9.-]+/g, '');
                    currentGroup.totalAmount = parseFloat(totalText) || 0;
                }
            }
        });

        if (currentGroup) {
            groups.push(currentGroup);
        }

        if (groups.length === 0) return;

        // Sort based on chosen column
        if (column === 'branch') {
            groups.sort(function(a, b) {
                var cmp = a.branch.localeCompare(b.branch);
                return newDir === 'asc' ? cmp : -cmp;
            });
        } else if (column === 'class') {
            groups.sort(function(a, b) {
                var cmp = a.className.localeCompare(b.className);
                return newDir === 'asc' ? cmp : -cmp;
            });
        } else if (column === 'fee_head') {
            // Sort fee items within each class group
            groups.forEach(function(g) {
                g.itemRows.sort(function(a, b) {
                    var cmp = a.feeHead.localeCompare(b.feeHead);
                    return newDir === 'asc' ? cmp : -cmp;
                });
            });
        } else if (column === 'amount') {
            // Sort class groups by total amount and items by amount
            groups.sort(function(a, b) {
                var cmp = a.totalAmount - b.totalAmount;
                return newDir === 'asc' ? cmp : -cmp;
            });
            groups.forEach(function(g) {
                g.itemRows.sort(function(a, b) {
                    var cmp = a.amount - b.amount;
                    return newDir === 'asc' ? cmp : -cmp;
                });
            });
        }

        // Re-append sorted rows to tbody
        tbody.innerHTML = '';
        groups.forEach(function(g) {
            tbody.appendChild(g.headerRow);
            g.itemRows.forEach(function(item) {
                tbody.appendChild(item.row);
            });
            if (g.totalRow) {
                tbody.appendChild(g.totalRow);
            }
        });
    }
</script>
@endpush
@endsection
