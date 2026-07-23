@extends('admin.layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
    @include('admin.account.coa._styles')

    <div class="legacy-coa">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-legacy-tab><i class="fa fa-list"></i> List View</a></li>
                            <li><a href="#tab_2" data-legacy-tab><i class="fa fa-file-invoice"></i> Details View</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active table-responsive no-padding" id="tab_1">
                                <div class="legacy-datatable-toolbar">
                                    <input type="search" placeholder="Search...">
                                    <div class="legacy-datatable-icons">
                                        <span><i class="fa fa-copy"></i></span>
                                        <span><i class="fa fa-file-csv"></i></span>
                                        <span><i class="fa fa-file-text"></i></span>
                                        <span><i class="fa fa-file-pdf"></i></span>
                                        <span><i class="fa fa-print"></i></span>
                                        <span><i class="fa fa-table-list"></i></span>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Account Head</th>
                                            <th>Account Type</th>
                                            <th>Account Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($chartRows as $row)
                                            <tr>
                                                <td>{{ $row->account_head }}</td>
                                                <td>{{ $row->account_type }}</td>
                                                <td style="text-align:left !important;">{{ $row->account_code }}. {{ $row->account_name }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">No chart of accounts records found, or the legacy tables are not available in this environment.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane" id="tab_2">
                                <div class="panel-group" id="accordion1">
                                    @forelse ($hierarchy as $headIndex => $head)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a href="#collapse{{ $headIndex }}" data-panel-toggle>{{ $head->code }}. {{ $head->name }}</a>
                                                </h4>
                                            </div>
                                            <div id="collapse{{ $headIndex }}" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="panel-group">
                                                        @foreach ($head->newaccounts as $typeIndex => $type)
                                                            <div class="panel">
                                                                <a href="#collapse{{ $headIndex }}{{ $typeIndex }}" data-panel-toggle>{{ $type->code }}. {{ $type->name }} &raquo;</a>
                                                                <div id="collapse{{ $headIndex }}{{ $typeIndex }}" class="panel-collapse collapse">
                                                                    <div class="panel-body">
                                                                        <div class="panel-group">
                                                                            @foreach ($type->accountshead as $account)
                                                                                {{ $account->code }}. {{ $account->name }}<br>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="panel panel-default">
                                            <div class="panel-body">No chart of accounts detail records found.</div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-legacy-tab]').forEach(function (tabLink) {
                tabLink.addEventListener('click', function (event) {
                    event.preventDefault();
                    document.querySelectorAll('.legacy-coa .nav-tabs li').forEach(function (tab) {
                        tab.classList.remove('active');
                    });
                    document.querySelectorAll('.legacy-coa .tab-pane').forEach(function (pane) {
                        pane.classList.remove('active');
                    });
                    tabLink.parentElement.classList.add('active');
                    document.querySelector(tabLink.getAttribute('href')).classList.add('active');
                });
            });

            document.querySelectorAll('[data-panel-toggle]').forEach(function (toggle) {
                toggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    var panel = document.querySelector(toggle.getAttribute('href'));

                    if (panel) {
                        panel.classList.toggle('in');
                    }
                });
            });
        });
    </script>
@endsection
