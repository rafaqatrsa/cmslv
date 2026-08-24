@extends('admin.layouts.app')

@section('title', 'Chart of Accounts')

@push('styles')
<style>
    /* =========================================================
       CMSC Chart of Accounts List Styling
       ========================================================= */
    .content-wrapper {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #333;
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
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
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

    /* Branch Dropdown */
    .branch-select-cmsc {
        height: 32px;
        padding: 4px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 13px;
        background: #fff;
        color: #333;
        outline: none;
    }

    .branch-select-cmsc:focus {
        border-color: #2F5DA8;
        box-shadow: 0 0 6px rgba(47,93,168,0.3);
    }

    /* Nav Tabs Custom */
    .nav-tabs-custom {
        margin-bottom: 20px;
        background: #fff;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        border-radius: 3px;
    }

    .nav-tabs-custom > .nav-tabs {
        margin: 0;
        border-bottom-color: #f4f4f4;
        border-top-right-radius: 3px;
        border-top-left-radius: 3px;
        display: flex;
        list-style: none;
        padding: 0;
        border-bottom: 1px solid #ddd;
        background: #fafafa;
    }

    .nav-tabs-custom > .nav-tabs > li {
        border-top: 3px solid transparent;
        margin-bottom: -1px;
        margin-right: 5px;
    }

    .nav-tabs-custom > .nav-tabs > li > a {
        color: #444;
        border-radius: 0;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 13px;
        display: block;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
        border-bottom: none;
        transition: all 0.2s ease;
    }

    .nav-tabs-custom > .nav-tabs > li.active {
        border-top-color: #2F5DA8;
    }

    .nav-tabs-custom > .nav-tabs > li.active > a {
        background-color: #fff;
        color: #2F5DA8;
        border-color: #ddd #ddd transparent #ddd;
    }

    .tab-content {
        padding: 15px 0;
        background: #fff;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
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

    /* Accordion Styles (Details View) */
    .panel-group .panel {
        margin-bottom: 10px;
        border-radius: 4px;
        border: 1px solid #e3e6f0;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .panel-heading {
        padding: 12px 15px;
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        cursor: pointer;
        user-select: none;
    }

    .panel-title {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #2F5DA8;
    }

    .panel-title a {
        color: #2F5DA8;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .panel-body {
        padding: 15px 20px;
    }

    .sub-accordion-item {
        margin-bottom: 8px;
        border: 1px solid #edf0f5;
        border-radius: 4px;
        overflow: hidden;
    }

    .sub-accordion-header {
        padding: 9px 14px;
        background: #f4f6fa;
        font-size: 13px;
        font-weight: 600;
        color: #333;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background-color 0.2s ease;
    }

    .sub-accordion-header:hover {
        background: #eaedf5;
    }

    .sub-accordion-body {
        padding: 10px 16px;
        background: #fff;
        display: none;
        border-top: 1px solid #edf0f5;
    }

    .acc-item-line {
        padding: 4px 0;
        font-size: 13px;
        color: #444;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .acc-item-line i {
        font-size: 8px;
        color: #2F5DA8;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <section class="content-header" style="padding: 10px 0 15px;">
        <h1 style="font-size: 20px; font-weight: 600; margin: 0; color: #333;">
            <i class="fa fa-list-alt"></i> {{ $title ?? 'Chart of Accounts' }}
        </h1>
    </section>

    <!-- Main content -->
    <section class="content" style="padding: 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="background: #fff; border: 1px solid #d2d6de; border-top: 3px solid #2F5DA8; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 20px;">
                    <div class="box-header with-border" style="padding: 12px 15px; border-bottom: 1px solid #f4f4f4;">
                        <h3 class="box-title" style="font-size: 16px; font-weight: 600; margin: 0; color: #333;">
                            <i class="fa fa-list-alt" style="margin-right: 6px; color: #2F5DA8;"></i> Chart of Accounts
                        </h3>

                        @if (isset($branchlist) && $branchlist->count() > 1)
                            <div>
                                <select class="branch-select-cmsc" onchange="changeBranch(this.value)">
                                    @foreach ($branchlist as $brc)
                                        <option value="{{ $brc->id }}" {{ (string)($brc_id ?? 1) === (string)$brc->id ? 'selected' : '' }}>
                                            {{ $brc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="box-body" style="padding: 15px;">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active" id="tab1-li">
                                    <a onclick="switchTab('tab_1')">
                                        <i class="fa fa-list" style="margin-right: 4px;"></i> List View
                                    </a>
                                </li>
                                <li id="tab2-li">
                                    <a onclick="switchTab('tab_2')">
                                        <i class="fa fa-newspaper-o" style="margin-right: 4px;"></i> Details View
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                {{-- Tab 1: List View --}}
                                <div class="tab-pane active" id="tab_1">
                                    <div class="download_label" style="display: none;">Chart of Accounts</div>
                                    <div class="table-responsive mailbox-messages">
                                        <table class="table table-striped table-bordered table-hover example" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                                            <thead>
                                                <tr style="background-color: #1e3a8a; color: #ffffff;">
                                                    <th style="padding: 8px 12px; border: 1px solid #162c6d; color: #fff; font-weight: 600;">Account Head</th>
                                                    <th style="padding: 8px 12px; border: 1px solid #162c6d; color: #fff; font-weight: 600;">Account Type</th>
                                                    <th style="padding: 8px 12px; border: 1px solid #162c6d; color: #fff; font-weight: 600;">Account Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($acclist as $val)
                                                    <tr>
                                                        <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;">{{ $val['account_head'] }}</td>
                                                        <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef;">{{ $val['account_type'] }}</td>
                                                        <td class="mailbox-name" style="padding: 8px 12px; border: 1px solid #e9ecef; text-align: left !important;">
                                                            {{ $val['account_code'] ? $val['account_code'] . '. ' : '' }}{{ $val['account_name'] }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Tab 2: Details View (Tree Accordion) --}}
                                <div class="tab-pane" id="tab_2">
                                    <div class="panel-group" id="accordion1">
                                        @foreach ($accountstypelist as $acc_type_key => $acc_type_val)
                                            <div class="panel">
                                                <div class="panel-heading" onclick="toggleAccordion('collapse-head-{{ $acc_type_val->id }}')">
                                                    <h4 class="panel-title">
                                                        <a>
                                                            <span>{{ $acc_type_val->code ? $acc_type_val->code . '. ' : '' }}{{ $acc_type_val->name }}</span>
                                                            <i class="fa fa-chevron-down" id="icon-head-{{ $acc_type_val->id }}" style="font-size: 11px; transition: transform 0.2s ease;"></i>
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse-head-{{ $acc_type_val->id }}" class="panel-collapse" style="display: {{ $acc_type_key === 0 ? 'block' : 'none' }};">
                                                    <div class="panel-body">
                                                        @foreach ($acc_type_val->newAccounts as $new_key => $new_val)
                                                            <div class="sub-accordion-item">
                                                                <div class="sub-accordion-header" onclick="toggleSubAccordion('collapse-sub-{{ $new_val->id }}')">
                                                                    <span>{{ $new_val->code ? $new_val->code . '. ' : '' }}{{ $new_val->name }} &raquo;</span>
                                                                    <i class="fa fa-angle-down" id="icon-sub-{{ $new_val->id }}" style="font-size: 12px; transition: transform 0.2s ease;"></i>
                                                                </div>
                                                                <div id="collapse-sub-{{ $new_val->id }}" class="sub-accordion-body">
                                                                    @foreach ($new_val->accountHeads as $head_val)
                                                                        <div class="acc-item-line">
                                                                            <i class="fa fa-circle"></i>
                                                                            <span>{{ $head_val->code ? $head_val->code . '. ' : '' }}{{ $head_val->name }}</span>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    function changeBranch(val) {
        if (val) {
            window.location.href = "{{ url('admin/account/accounts/index') }}/" + val;
        }
    }

    function switchTab(tabId) {
        document.querySelectorAll('.tab-pane').forEach(function(pane) {
            pane.classList.remove('active');
        });
        document.querySelectorAll('.nav-tabs li').forEach(function(li) {
            li.classList.remove('active');
        });

        var activePane = document.getElementById(tabId);
        if (activePane) activePane.classList.add('active');

        if (tabId === 'tab_1') {
            document.getElementById('tab1-li').classList.add('active');
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('.example')) {
                $('.example').DataTable().columns.adjust().responsive.recalc();
            }
        } else {
            document.getElementById('tab2-li').classList.add('active');
        }
    }

    function toggleAccordion(collapseId) {
        var el = document.getElementById(collapseId);
        var iconId = collapseId.replace('collapse-head-', 'icon-head-');
        var icon = document.getElementById(iconId);

        if (el) {
            if (el.style.display === 'none' || !el.style.display) {
                el.style.display = 'block';
                if (icon) icon.style.transform = 'rotate(180deg)';
            } else {
                el.style.display = 'none';
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        }
    }

    function toggleSubAccordion(collapseId) {
        var el = document.getElementById(collapseId);
        var iconId = collapseId.replace('collapse-sub-', 'icon-sub-');
        var icon = document.getElementById(iconId);

        if (el) {
            if (el.style.display === 'none' || !el.style.display) {
                el.style.display = 'block';
                if (icon) icon.style.transform = 'rotate(180deg)';
            } else {
                el.style.display = 'none';
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        }
    }
</script>
@endpush
@endsection
