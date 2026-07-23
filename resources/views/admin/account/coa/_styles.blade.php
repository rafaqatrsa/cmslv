<style>
    .legacy-coa {
        color: #000;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    .legacy-coa .content {
        padding: 4px 8px 15px;
    }

    .legacy-coa .row {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .legacy-coa .col-md-4 {
        width: calc(33.333333% - 4px);
    }

    .legacy-coa .col-md-8 {
        width: calc(66.666667% - 4px);
    }

    .legacy-coa .col-md-12 {
        width: 100%;
    }

    .legacy-coa .box {
        position: relative;
        border: 1px solid #d2d6de;
        border-top: 2px solid #3c8dbc;
        border-radius: 4px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .18);
    }

    .legacy-coa .box-header {
        display: block;
        position: relative;
        border-bottom: 1px solid #e5e5e5;
        padding: 8px;
    }

    .legacy-coa .box-title {
        display: inline-block;
        margin: 0;
        font-size: 16px;
        line-height: 1;
    }

    .legacy-coa .box-body {
        padding: 8px;
    }

    .legacy-coa .box-footer {
        border-top: 1px solid #f4f4f4;
        padding: 8px;
        background: #fff;
    }

    .legacy-coa .form-group {
        margin-bottom: 15px;
    }

    .legacy-coa label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: 700;
    }

    .legacy-coa .req,
    .legacy-coa .text-danger {
        color: #dd4b39;
    }

    .legacy-coa .form-control {
        display: block;
        width: 100%;
        height: 29px;
        border: 1px solid #d2d6de;
        border-radius: 0;
        padding: 4px 8px;
        background-color: #fff;
        color: #555;
        font-size: 14px;
        line-height: 1.42857143;
    }

    .legacy-coa textarea.form-control {
        height: auto;
    }

    .legacy-coa .btn {
        display: inline-block;
        border: 1px solid transparent;
        border-radius: 3px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
    }

    .legacy-coa .btn-primary {
        border-color: #1d3f8d;
        background-color: #24448d;
        color: #fff;
    }

    .legacy-coa .btn-success {
        border-color: #008d4c;
        background-color: #00a65a;
        color: #fff;
    }

    .legacy-coa .btn-danger {
        border-color: #d73925;
        background-color: #dd4b39;
        color: #fff;
    }

    .legacy-coa .btn-xs {
        padding: 1px 5px;
        font-size: 12px;
        line-height: 1.5;
    }

    .legacy-coa .pull-right {
        float: right;
    }

    .legacy-coa table {
        width: 100%;
        max-width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
        background-color: transparent;
    }

    .legacy-coa .table-bordered > thead > tr > th,
    .legacy-coa .table-bordered > tbody > tr > td {
        border: 1px solid #d5dfef;
    }

    .legacy-coa th,
    .legacy-coa td {
        padding: 7px;
        vertical-align: top;
        line-height: 1.42857143;
    }

    .legacy-coa thead th {
        background: #24448d;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
    }

    .legacy-coa thead th::after {
        margin-left: 4px;
        color: #cbd9ff;
        content: "\25BE";
        font-size: 9px;
    }

    .legacy-coa .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .legacy-coa .table-hover > tbody > tr:hover {
        background-color: #f5f5f5;
    }

    .legacy-coa .text-right {
        text-align: right;
    }

    .legacy-coa .alert {
        margin-bottom: 15px;
        border: 1px solid transparent;
        border-radius: 4px;
        padding: 15px;
    }

    .legacy-coa .alert-success {
        border-color: #d6e9c6;
        background-color: #dff0d8;
        color: #3c763d;
    }

    .legacy-coa .alert-danger {
        border-color: #ebccd1;
        background-color: #f2dede;
        color: #a94442;
    }

    .legacy-coa .nav-tabs {
        display: flex;
        margin: 0;
        border-bottom: 1px solid #24448d;
        padding-left: 0;
        list-style: none;
    }

    .legacy-coa .nav-tabs a {
        display: block;
        margin-right: 2px;
        border: 1px solid transparent;
        border-radius: 4px 4px 0 0;
        padding: 10px 15px;
        color: #444;
        text-decoration: none;
    }

    .legacy-coa .nav-tabs .active a {
        border: 1px solid #ddd;
        border-bottom-color: #fff;
        background: #fff;
    }

    .legacy-coa .tab-content {
        border: 1px solid #ddd;
        border-top: 0;
        background: #fff;
        padding: 8px;
    }

    .legacy-coa .tab-pane {
        display: none;
    }

    .legacy-coa .tab-pane.active {
        display: block;
    }

    .legacy-coa .panel {
        margin-bottom: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #fff;
    }

    .legacy-coa .panel-heading {
        border-bottom: 1px solid #ddd;
        padding: 10px 15px;
        background: #f5f5f5;
    }

    .legacy-coa .panel-title {
        margin: 0;
        font-size: 14px;
    }

    .legacy-coa .panel-body {
        padding: 15px;
    }

    .legacy-coa .collapse {
        display: none;
    }

    .legacy-coa .collapse.in {
        display: block;
    }

    .legacy-datatable-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .legacy-datatable-toolbar input {
        width: 140px;
        height: 22px;
        border: 1px solid #777;
        border-radius: 3px;
        padding: 2px 5px;
        font-size: 11px;
    }

    .legacy-datatable-icons {
        display: flex;
        gap: 3px;
    }

    .legacy-datatable-icons span {
        display: inline-flex;
        width: 20px;
        height: 20px;
        align-items: center;
        justify-content: center;
        border-radius: 3px;
        background: #24448d;
        color: #fff;
        font-size: 11px;
    }

    .legacy-coa .mailbox-date .btn-primary {
        border-color: #24448d;
        background: #24448d;
    }

    @media (max-width: 992px) {
        .legacy-coa .col-md-4,
        .legacy-coa .col-md-8 {
            width: 100%;
        }
    }
</style>
