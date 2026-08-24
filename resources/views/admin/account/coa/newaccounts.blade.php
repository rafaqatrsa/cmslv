@extends('admin.layouts.app')

@section('title', $title ?? 'Add Accounts Type')

@push('styles')
<style>
    /* =========================================================
       CMSC Accounts Type (newaccounts) Styling
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

    /* Buttons */
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

    /* DataTables Export Buttons (Copy, Excel, CSV, PDF, Print, Columns) */
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

    /* Action Edit Square */
    .btn-edit-square {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background-color: #1e3a8a;
        color: #fff !important;
        border-radius: 3px;
        font-size: 11px;
        text-decoration: none;
        transition: background-color 0.15s ease;
    }

    .btn-edit-square:hover {
        background-color: #162c6d;
        color: #fff !important;
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
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <section class="content-header" style="padding: 10px 0 15px;">
        <h1 style="font-size: 20px; font-weight: 600; margin: 0; color: #333;">
            <i class="fa fa-list"></i> {{ $title ?? 'Add Accounts Type' }}
        </h1>
    </section>

    <!-- Main content -->
    <section class="content" style="padding: 0;">
        <div class="coa-grid-row">
            {{-- Left Form Column --}}
            <div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $title ?? 'Add Accounts Type' }}</h3>
                    </div>

                        <form id="form1" action="{{ $editAccount ? route('admin.account.accounts.newaccountsupdate', $editAccount->id, absolute: false) : route('admin.account.accounts.newaccountscreate', absolute: false) }}" method="POST" accept-charset="utf-8">
                            @csrf
                            @if ($editAccount)
                                <input type="hidden" name="id" value="{{ $editAccount->id }}" />
                            @endif

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

                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="accounts_type_id" style="font-weight: 600; font-size: 13px; color: #333; display: block; margin-bottom: 5px;">
                                        Accounts Head <small class="req" style="color: #ff0000; font-weight: bold;">*</small>
                                    </label>
                                    <select id="accounts_type_id" name="accounts_type_id" class="form-control" style="width: 100%; height: 34px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; font-size: 13px;" required>
                                        <option value="">Select</option>
                                        @foreach ($accountstypelist as $acc_type)
                                            <option value="{{ $acc_type->id }}" {{ (string)$accounts_type_id === (string)$acc_type->id ? 'selected' : '' }}>
                                                {{ $acc_type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('accounts_type_id')
                                        <span class="text-danger" style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="name" style="font-weight: 600; font-size: 13px; color: #333; display: block; margin-bottom: 5px;">
                                        Account Type Name <small class="req" style="color: #ff0000; font-weight: bold;">*</small>
                                    </label>
                                    <input autofocus id="name" name="name" type="text" class="form-control" value="{{ $name }}" style="width: 100%; height: 34px; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; font-size: 13px;" required />
                                    @error('name')
                                        <span class="text-danger" style="color: #ff0000; font-size: 12px; display: block; margin-top: 4px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="description" style="font-weight: 600; font-size: 13px; color: #333; display: block; margin-bottom: 5px;">
                                        Description
                                    </label>
                                    <textarea class="form-control" id="description" name="description" rows="3" style="width: 100%; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px; font-size: 13px;">{{ $description }}</textarea>
                                </div>
                            </div>

                            <div class="box-footer" style="padding: 10px 15px; border-top: 1px solid #f4f4f4; text-align: right; background: #fff; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">
                                @if ($editAccount)
                                    <a href="{{ route('admin.account.accounts.newaccounts', absolute: false) }}" class="btn btn-default" style="display: inline-block; padding: 6px 14px; margin-right: 6px; font-size: 13px; border: 1px solid #ccc; border-radius: 4px; color: #333; text-decoration: none; background: #fff;">
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
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Accounts Type List</h3>
                    </div>
                    <div class="box-body">
                        <div class="download_label" style="display: none;">Accounts Type List</div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr style="background-color: #1e3a8a; color: #ffffff;">
                                            <th style="padding: 8px 12px; border: 1px solid #162c6d; color: #fff; font-weight: 600;">Account Type</th>
                                            <th style="padding: 8px 12px; border: 1px solid #162c6d; color: #fff; font-weight: 600;">Account Name</th>
                                            <th class="text-right noExport" style="text-align: right; width: 60px; padding: 8px 12px; border: 1px solid #162c6d; color: #fff; font-weight: 600;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($resultacclist as $val)
                                            <tr style="background-color: #f9fbfd; font-weight: 600;">
                                                <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef; color: #222;">
                                                    {{ $val->code ? $val->code . '. ' : '' }}{{ $val->name }}
                                                </td>
                                                <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;"></td>
                                                <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;"></td>
                                            </tr>
                                            @foreach ($val->newAccounts as $head_val)
                                                <tr>
                                                    <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;"></td>
                                                    <td class="mailbox-name" style="padding: 8px 12px 8px 24px; border: 1px solid #e9ecef; color: #333;">
                                                        {{ $head_val->code }}. {{ $head_val->name }}
                                                    </td>
                                                    <td class="mailbox-date text-right" style="text-align: right; padding: 8px 12px; border: 1px solid #e9ecef;">
                                                        @if (!$head_val->is_system)
                                                            <a href="{{ route('admin.account.accounts.newaccountsedit', $head_val->id, absolute: false) }}" class="btn btn-primary btn-xs" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; background: #1e3a8a; color: #fff; border-radius: 3px; font-size: 11px; text-decoration: none;" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
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
@endsection
