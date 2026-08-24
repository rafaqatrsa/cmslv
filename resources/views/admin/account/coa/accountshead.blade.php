@extends('admin.layouts.app')

@section('title', $title ?? 'Add New Accounts Head')

@push('styles')
<style>
    /* =========================================================
       CMSC Accounts Head (Add New Accounts) Styling
       ========================================================= */
    .content-wrapper {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #333;
    }

    .coa-grid-row {
        display: grid;
        grid-template-columns: minmax(0, 4fr) minmax(0, 8fr);
        gap: 20px;
        align-items: start;
    }

    @media (max-width: 991px) {
        .coa-grid-row {
            grid-template-columns: 1fr;
        }
    }

    /* Box Cards */
    .box {
        position: relative;
        border-radius: 4px;
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-top: 3px solid #d2d6de;
        margin-bottom: 20px;
        width: 100%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .box.box-primary {
        border-top-color: #2F5DA8;
    }

    .box-header {
        color: #333;
        background: #fff;
        border-bottom: 1px solid #f4f4f4;
        padding: 12px 15px;
        position: relative;
    }

    .box-title {
        display: inline-block;
        font-size: 16px;
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

    /* Form Fields */
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        font-size: 13px;
        color: #333;
    }

    .form-group label .req {
        color: #ff0000;
        font-size: 14px;
        font-weight: bold;
    }

    .form-control {
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        font-size: 13px;
        color: #555;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        outline: none;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #66afe9;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
    }

    textarea.form-control {
        height: auto;
        min-height: 80px;
        resize: vertical;
    }

    /* System Account Warning message boxes */
    .system-acc-alert {
        background-color: #f2dede;
        border: 1px solid #ebccd1;
        color: #a94442;
        padding: 10px 12px;
        border-radius: 4px;
        margin-bottom: 12px;
        font-size: 13px;
        display: none;
    }

    /* Buttons */
    .btn-save-cmsc {
        background-color: #1e3a8a;
        color: #ffffff !important;
        border: 1px solid #1e3a8a;
        padding: 6px 20px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-block;
        text-align: center;
    }

    .btn-save-cmsc:hover {
        background-color: #162c6d;
        border-color: #162c6d;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    /* DataTables Container & Search Bar */
    .dataTables_wrapper {
        font-size: 13px;
        color: #333;
    }

    .dataTables_wrapper .dataTables_filter {
        float: left;
        text-align: left;
        margin-bottom: 12px;
    }

    .dataTables_wrapper .dataTables_filter label {
        font-weight: normal;
        margin: 0;
    }

    .dataTables_wrapper .dataTables_filter input {
        height: 30px;
        width: 190px;
        padding: 4px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 12.5px;
        outline: none;
        transition: border-color 0.2s ease;
        margin-left: 0 !important;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #2F5DA8;
        box-shadow: 0 0 6px rgba(47,93,168,0.3);
    }

    /* DataTables Export Buttons */
    .dt-buttons {
        float: right !important;
        margin-bottom: 12px !important;
        display: inline-flex !important;
        gap: 4px !important;
    }

    .dt-buttons .dt-button {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 28px !important;
        height: 28px !important;
        padding: 0 !important;
        background: #ffffff !important;
        border: 1px solid #1e3a8a !important;
        color: #1e3a8a !important;
        border-radius: 3px !important;
        font-size: 12px !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        margin-right: 0 !important;
        box-shadow: none !important;
    }

    .dt-buttons .dt-button:hover {
        background: #1e3a8a !important;
        color: #ffffff !important;
        transform: translateY(-1px) !important;
    }

    /* Table Styles */
    .mailbox-messages {
        overflow-x: auto;
        border: 1px solid #d2d6de;
    }

    table.dataTable.example {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 0 !important;
        font-size: 13px !important;
    }

    table.dataTable.example thead th {
        background-color: #1e3a8a !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        padding: 9px 12px !important;
        border: 1px solid #162c6d !important;
        letter-spacing: 0.2px;
    }

    table.dataTable.example tbody td {
        padding: 8px 12px !important;
        border: 1px solid #e9ecef !important;
        vertical-align: middle !important;
        color: #333;
    }

    table.dataTable.example tbody tr:hover td {
        background-color: #f5f9ff !important;
    }

    /* Action Buttons in Table */
    .btn-action-xs {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 3px;
        font-size: 11px;
        color: #fff !important;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        margin-right: 3px;
        transition: opacity 0.15s ease;
    }

    .btn-action-xs:hover {
        opacity: 0.85;
    }

    .btn-action-edit {
        background-color: #1e3a8a;
        border-color: #1e3a8a;
    }

    .btn-action-success {
        background-color: #5cb85c;
        border-color: #4cae4c;
    }

    .btn-action-danger {
        background-color: #d9534f;
        border-color: #d43f3a;
    }

    /* DataTables Info & Pagination */
    .dataTables_wrapper .dataTables_info {
        padding-top: 10px;
        font-size: 12.5px;
        color: #666;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 4px 10px !important;
        font-size: 12px !important;
        border-radius: 3px !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #1e3a8a !important;
        color: #fff !important;
        border-color: #1e3a8a !important;
    }

    /* Toast Notification */
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
        animation: toastIn 0.3s ease;
    }

    @keyframes toastIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <section class="content-header" style="padding: 10px 0 15px;">
        <h1 style="font-size: 20px; font-weight: 600; margin: 0; color: #333;">
            <i class="fa fa-list"></i> {{ $title ?? 'Add New Accounts Head' }}
        </h1>
    </section>

    <!-- Main content -->
    <section class="content" style="padding: 0;">
        <div class="coa-grid-row">
            {{-- Left Form Column --}}
            <div>
                <div class="box box-primary" style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #2F5DA8; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 20px;">
                    <div class="box-header with-border" style="padding: 12px 15px; border-bottom: 1px solid #f4f4f4;">
                        <h3 class="box-title" style="font-size: 16px; font-weight: 600; margin: 0; color: #333;">{{ $title ?? 'Add New Accounts Head' }}</h3>
                    </div>

                    <form id="accountsHeadForm" action="{{ $editAccountHead ? route('admin.account.accounts.accountsheadupdate', ['id' => $editAccountHead->id, 'branch_id' => $brc_id], absolute: false) : route('admin.account.accounts.accountsheadcreate', ['branch_id' => $brc_id], absolute: false) }}" method="POST">
                        @csrf
                        <div class="box-body" style="padding: 15px;">
                            @if (session('success'))
                                <div class="alert alert-success text-left" style="background-color: #dff0d8; border: 1px solid #d6e9c6; color: #3c763d; padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger text-left" style="background-color: #f2dede; border: 1px solid #ebccd1; color: #a94442; padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if ($branchlist->count() > 1)
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="brc_id" style="font-weight: 600; font-size: 13px; color: #333; display: block; margin-bottom: 5px;">Branch <small class="req" style="color: #ff0000; font-weight: bold;">*</small></label>
                                    <select id="brc_id" name="brc_id" class="form-control" style="width: 100%; height: 34px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; font-size: 13px;" onchange="changeBranch(this.value)">
                                        <option value="">Select Branch</option>
                                        @foreach ($branchlist as $brc)
                                            <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>{{ $brc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="brc_id" id="brc_id" value="{{ $brc_id }}" />
                            @endif

                            <div class="form-group" style="margin-bottom: 15px;">
                                <label for="accounts_head_id" style="font-weight: 600; font-size: 13px; color: #333; display: block; margin-bottom: 5px;">
                                    Account Head <small class="req" style="color: #ff0000; font-weight: bold;">*</small>
                                </label>
                                <select id="accounts_head_id" name="accounts_head_id" class="form-control" style="width: 100%; height: 34px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; font-size: 13px;" onchange="onAccountHeadChange(this.value)" required>
                                    <option value="">Select</option>
                                    @foreach ($accountstypelist as $acc_type)
                                        <option value="{{ $acc_type->id }}" {{ (string)$accounts_head_id === (string)$acc_type->id ? 'selected' : '' }}>
                                            {{ $acc_type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('accounts_head_id')
                                    <span class="text-danger" style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" style="margin-bottom: 15px;">
                                <label for="account_type_id" style="font-weight: 600; font-size: 13px; color: #333; display: block; margin-bottom: 5px;">
                                    Account Type <small class="req" style="color: #ff0000; font-weight: bold;">*</small>
                                </label>
                                <select id="account_type_id" name="account_type_id" class="form-control" style="width: 100%; height: 34px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; font-size: 13px;" onchange="onAccountTypeChange(this.value)" required>
                                    <option value="">Select</option>
                                </select>
                                @error('account_type_id')
                                    <span class="text-danger" style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div id="ooamsg">
                                <div class="system-acc-alert trevd">Please add "trade receivable" in the "Student Admission" menu from "Admission Process" tab.</div>
                                <div class="system-acc-alert trpayabl">Please add "trade Payable" in the "Supplier" menu from "Inventory Process" tab.</div>
                                <div class="system-acc-alert invt">Please add "Inventories" in the "Product/Service" menu from "Inventory Process" tab.</div>
                                <div class="system-acc-alert salaies">Please add "Staff Directory" in the "Employees" menu from "Staff Recruitment" tab.</div>
                                <div class="system-acc-alert sales">"Sales" accounts cannot be created here. They are automatically generated when adding new products / services.</div>
                                <div class="system-acc-alert salesreturn">"Sales Return" accounts cannot be created here. They are automatically generated when adding new products / services.</div>
                                <div class="system-acc-alert purchases">"Purchases" accounts cannot be created here. They are automatically generated when adding new products / services.</div>
                                <div class="system-acc-alert purchasesreturn">"Purchases Return" accounts cannot be created here. They are automatically generated when adding new products / services.</div>
                                <div class="system-acc-alert costofsales">"Cost of Sales" accounts cannot be created here. They are automatically generated when adding new products / services.</div>
                            </div>

                            <div id="ooa">
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="name" style="font-weight: 600; font-size: 13px; color: #333; display: block; margin-bottom: 5px;">
                                        Account Name <small class="req" style="color: #ff0000; font-weight: bold;">*</small>
                                    </label>
                                    <input autofocus id="name" name="name" type="text" class="form-control" value="{{ $name }}" style="width: 100%; height: 34px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; font-size: 13px;" />
                                    @error('name')
                                        <span class="text-danger" style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div id="ob" style="display: none;">
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="date" style="font-weight: 600; font-size: 13px; color: #333; display: block; margin-bottom: 5px;">Opening Balance Date</label>
                                        <input type="date" id="date" name="date" class="form-control" value="{{ $date }}" style="width: 100%; height: 34px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; font-size: 13px;" />
                                    </div>
                                    <div class="form-group" style="margin-bottom: 15px;">
                                        <label for="opening_balance_amount" style="font-weight: 600; font-size: 13px; color: #333; display: block; margin-bottom: 5px;">Opening Balance Amount</label>
                                        <input type="number" step="any" id="opening_balance_amount" name="opening_balance_amount" class="form-control" value="{{ $amount }}" placeholder="0.00" style="width: 100%; height: 34px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; font-size: 13px;" />
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="description" style="font-weight: 600; font-size: 13px; color: #333; display: block; margin-bottom: 5px;">
                                        Description
                                    </label>
                                    <textarea class="form-control" id="description" name="description" rows="3" style="width: 100%; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; font-size: 13px;">{{ $description }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer" style="padding: 10px 15px; border-top: 1px solid #f4f4f4; text-align: right; background: #fff; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">
                            @if ($editAccountHead)
                                <a href="{{ route('admin.account.accounts.accountshead', ['branch_id' => $brc_id], absolute: false) }}" class="btn btn-default" style="display: inline-block; padding: 6px 14px; margin-right: 6px; font-size: 13px; border: 1px solid #ccc; border-radius: 4px; color: #333; text-decoration: none; background: #fff;">
                                    Cancel
                                </a>
                                <button type="submit" class="btn-save-cmsc" style="background-color: #1e3a8a; border: 1px solid #1e3a8a; color: #ffffff; padding: 6px 20px; font-size: 13px; font-weight: 600; border-radius: 4px; cursor: pointer;">
                                    Update
                                </button>
                            @else
                                <button type="submit" class="btn-save-cmsc" style="background-color: #1e3a8a; border: 1px solid #1e3a8a; color: #ffffff; padding: 6px 20px; font-size: 13px; font-weight: 600; border-radius: 4px; cursor: pointer;">
                                    Save
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right Table Column --}}
            <div>
                <div class="box box-primary" style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #2F5DA8; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 20px;">
                    <div class="box-header ptbnull" style="padding: 12px 15px; border-bottom: 1px solid #f4f4f4;">
                        <h3 class="box-title titlefix" style="font-size: 16px; font-weight: 600; margin: 0; color: #333;">Accounts Head List</h3>
                    </div>
                    <div class="box-body" style="padding: 15px;">
                        <div class="download_label" style="display: none;">Accounts Head List</div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover example" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                                <thead>
                                    <tr style="background-color: #1e3a8a; color: #ffffff;">
                                        <th style="padding: 8px 12px; border: 1px solid #162c6d; color: #fff; font-weight: 600;">Account Head</th>
                                        <th style="padding: 8px 12px; border: 1px solid #162c6d; color: #fff; font-weight: 600;">Account Type</th>
                                        <th style="padding: 8px 12px; border: 1px solid #162c6d; color: #fff; font-weight: 600;">Account Name</th>
                                        <th class="text-right noExport" style="text-align: right; width: 85px; padding: 8px 12px; border: 1px solid #162c6d; color: #fff; font-weight: 600;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resultacclist as $head)
                                        <tr style="background-color: #f9fbfd; font-weight: 600;">
                                            <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef; color: #222;">
                                                {{ $head->code ? $head->code . '. ' : '' }}{{ $head->name }}
                                            </td>
                                            <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;"></td>
                                            <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;"></td>
                                            <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;"></td>
                                        </tr>
                                        @foreach ($head->newAccounts as $sub)
                                            <tr style="background-color: #fafafa; font-weight: 500;">
                                                <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;"></td>
                                                <td class="mailbox-name" style="padding: 8px 12px 8px 24px; border: 1px solid #e9ecef; color: #333;">
                                                    {{ $sub->code ? $sub->code . '. ' : '' }}{{ $sub->name }}
                                                </td>
                                                <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;"></td>
                                                <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;"></td>
                                            </tr>
                                            @foreach ($sub->accountHeads as $acc)
                                                <tr>
                                                    <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;"></td>
                                                    <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;"></td>
                                                    <td class="mailbox-name" style="padding: 8px 12px 8px 36px; border: 1px solid #e9ecef; color: #444;">
                                                        {{ $acc->code ? $acc->code . '. ' : '' }}{{ $acc->name }}
                                                    </td>
                                                    <td class="mailbox-date text-right" style="text-align: right; white-space: nowrap; padding: 8px 12px; border: 1px solid #e9ecef;">
                                                        @if (!$acc->is_system)
                                                            @if ($acc->is_posted == 1)
                                                                <button type="button" onclick="changestatuspost('{{ $acc->id }}')" class="btn btn-success btn-xs" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; background: #5cb85c; color: #fff; border: 1px solid #4cae4c; border-radius: 3px; font-size: 11px; margin-right: 3px; cursor: pointer;" title="Posted">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            @else
                                                                <button type="button" onclick="changestatuspost('{{ $acc->id }}')" class="btn btn-danger btn-xs" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; background: #d9534f; color: #fff; border: 1px solid #d43f3a; border-radius: 3px; font-size: 11px; margin-right: 3px; cursor: pointer;" title="Not Posted">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            @endif

                                                            <a href="{{ route('admin.account.accounts.accountsheadedit', ['id' => $acc->id, 'branch_id' => $brc_id], absolute: false) }}" class="btn btn-primary btn-xs" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; background: #1e3a8a; color: #fff; border: 1px solid #1e3a8a; border-radius: 3px; font-size: 11px; text-decoration: none; margin-right: 3px;" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>

                                                            @if ($acc->is_active == 'yes')
                                                                <button type="button" onclick="changestatus('{{ $acc->id }}')" class="btn btn-success btn-xs" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; background: #5cb85c; color: #fff; border: 1px solid #4cae4c; border-radius: 3px; font-size: 11px; cursor: pointer;" title="Active">
                                                                    <i class="fa fa-check"></i>
                                                                </button>
                                                            @else
                                                                <button type="button" onclick="changestatus('{{ $acc->id }}')" class="btn btn-danger btn-xs" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; background: #d9534f; color: #fff; border: 1px solid #d43f3a; border-radius: 3px; font-size: 11px; cursor: pointer;" title="Inactive">
                                                                    <i class="fa fa-remove"></i>
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="exportToast">Action Completed</div>

@push('scripts')
<script>
    var selectedHeadId = '{{ $accounts_head_id }}';
    var selectedTypeId = '{{ $account_type_id }}';

    function changeBranch(val) { if (val) window.location.href = "{{ url('admin/account/accounts/accountshead') }}/" + val; }

    function onAccountHeadChange(headId, callback) {
        var typeSelect = document.getElementById('account_type_id');
        var obSection = document.getElementById('ob');
        if (headId == '1' || headId == '2' || headId == '3') { if (obSection) obSection.style.display = 'block'; } else { if (obSection) obSection.style.display = 'none'; }
        typeSelect.innerHTML = '<option value="">Select</option>';
        if (!headId) return;
        fetch("{{ route('admin.account.accounts.getBynewaccounts', absolute: false) }}?accounts_head_id=" + headId)
            .then(function(res) { return res.json(); })
            .then(function(data) {
                data.forEach(function(item) {
                    var opt = document.createElement('option');
                    opt.value = item.id;
                    opt.text = (item.code ? item.code + '. ' : '') + item.name;
                    if (String(selectedTypeId) === String(item.id)) opt.selected = true;
                    typeSelect.appendChild(opt);
                });
                if (callback) callback();
                onAccountTypeChange(typeSelect.value);
            });
    }

    function onAccountTypeChange(typeId) {
        var ooa = document.getElementById('ooa');
        var ooamsg = document.getElementById('ooamsg');
        var alerts = document.querySelectorAll('.system-acc-alert');
        alerts.forEach(function(el) { el.style.display = 'none'; });
        var typeMap = {'3': '.trevd', '13': '.trpayabl', '23': '.invt', '33': '.sales', '34': '.salesreturn', '35': '.purchases', '36': '.purchasesreturn', '37': '.costofsales'};
        if (typeMap[typeId]) {
            if (ooa) ooa.style.display = 'none';
            if (ooamsg) ooamsg.style.display = 'block';
            var targetAlert = document.querySelector(typeMap[typeId]);
            if (targetAlert) targetAlert.style.display = 'block';
        } else {
            if (ooamsg) ooamsg.style.display = 'none';
            if (ooa) ooa.style.display = 'block';
        }
    }

    function showToast(message) {
        var toast = document.getElementById('exportToast');
        if (toast) {
            toast.innerText = message;
            toast.style.display = 'block';
            setTimeout(function() { toast.style.display = 'none'; }, 2200);
        }
    }

    function changestatus(id) {
        fetch("{{ route('admin.account.accounts.changestatus', absolute: false) }}", {
            method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({ id: id })
        }).then(function(res) { return res.json(); }).then(function(data) { showToast(data.message || 'Status updated'); setTimeout(function() { window.location.reload(); }, 600); });
    }

    function changestatuspost(id) {
        fetch("{{ route('admin.account.accounts.changestatuspost', absolute: false) }}", {
            method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({ id: id })
        }).then(function(res) { return res.json(); }).then(function(data) { showToast(data.message || 'Posting status updated'); setTimeout(function() { window.location.reload(); }, 600); });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (selectedHeadId) { onAccountHeadChange(selectedHeadId, function() { if (selectedTypeId) onAccountTypeChange(selectedTypeId); }); }
    });
</script>
@endpush
@endsection
