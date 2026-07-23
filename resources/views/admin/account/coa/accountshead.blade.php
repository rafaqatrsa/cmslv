@extends('admin.layouts.app')

@section('title', $title)

@php
    $selectedHeadId = old('accounts_head_id', $account->accounts_head_id ?? '');
    $selectedTypeId = old('account_type_id', $account->new_accounts_id ?? '');
    $openingAmount = old('opening_balance_amount', $openingBalance->debit_amount ?? $openingBalance->credit_amount ?? '');
@endphp

@section('content')
    @include('admin.account.coa._styles')

    <div class="legacy-coa">
        <section class="content">
            <div class="row">
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $title }}</h3>
                        </div>

                        <form action="{{ $account ? route('admin.account.accounts.accountshead.update', ['account' => $account->id, 'branch' => $branchId], false) : route('admin.account.accounts.accountshead.store', ['branch' => $branchId], false) }}" method="post" accept-charset="utf-8">
                            @csrf
                            <div class="box-body">
                                @if (session('success'))
                                    <div class="alert alert-success text-left">{{ session('success') }}</div>
                                @endif

                                @if ($account)
                                    <input type="hidden" name="id" value="{{ $account->id }}">
                                @endif

                                @if ($branches !== [])
                                    <div class="form-group">
                                        <label>Branch</label><small class="req"> *</small>
                                        <select id="brc_id" name="brc_id" class="form-control selectval brc_id" onchange="getBranchByID(this.value);">
                                            <option value="">Select</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" @selected((int) $branchId === (int) $branch->id)>{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label>Account Head</label><small class="req"> *</small>
                                    <select id="accounts_head_id" name="accounts_head_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach ($accountTypes as $accountType)
                                            <option value="{{ $accountType->id }}" @selected((string) $selectedHeadId === (string) $accountType->id)>{{ $accountType->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('accounts_head_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Account Type</label><small class="req"> *</small>
                                    <select id="account_type_id" name="account_type_id" class="form-control selectval">
                                        <option value="">Select</option>
                                        @foreach ($newAccounts as $newAccount)
                                            <option value="{{ $newAccount->id }}" @selected((string) $selectedTypeId === (string) $newAccount->id)>{{ $newAccount->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div id="ooa" style="display:none;">
                                    <div class="form-group">
                                        <label>Account Name</label> <small class="req"> *</small>
                                        <input autofocus id="name" name="name" type="text" class="form-control" value="{{ old('name', $account->name ?? '') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div id="ob" style="display:none;">
                                        <div class="form-group">
                                            <label>Staff</label>
                                            <select id="staff_id" name="staff_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach ($staffList as $staff)
                                                    <option value="{{ $staff->staff_id ?? $staff->id }}" @selected((string) old('staff_id', $account->staff_id ?? '') === (string) ($staff->staff_id ?? $staff->id))>
                                                        {{ $staff->employee_id }} - {{ trim(($staff->name ?? '').' '.($staff->surname ?? '')) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Opening Balance Date</label>
                                            <input id="date" name="date" type="date" class="form-control date" value="{{ old('date', isset($openingBalance->date) ? \Illuminate\Support\Carbon::parse($openingBalance->date)->toDateString() : now()->toDateString()) }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Opening Balance Amount</label>
                                            <input id="opening_balance_amount" name="opening_balance_amount" type="text" class="form-control" value="{{ $openingAmount }}" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $account->note ?? '') }}</textarea>
                                    </div>
                                </div>

                                <div id="ooamsg" style="display:none;">
                                    <div class="alert alert-danger text-left trevd" style="display:none;">Please add "trade receivable" in the "Student Admission" menu from "Admission Process" tab.</div>
                                    <div class="alert alert-danger text-left trpayabl" style="display:none;">Please add "trade Payable" in the "Supplier" menu from "Inventory Process" tab.</div>
                                    <div class="alert alert-danger text-left invt" style="display:none;">Please add "Inventories" in the "Product/Service" menu from "Inventory Process" tab.</div>
                                    <div class="alert alert-danger text-left salaies" style="display:none;">Please add " Staff Directory" in the "Employees" menu from "Staff Recruitment" tab.</div>
                                    <div class="alert alert-danger text-left sales" style="display:none;">"Sales" accounts cannot be created here. They are automatically generated when adding new products / services.</div>
                                    <div class="alert alert-danger text-left salesreturn" style="display:none;">"Sales Return" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left purchases" style="display:none;">"Purchases" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left purchasesreturn" style="display:none;">"Purchases Return" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left costofsales" style="display:none;">"Cost of Sales" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary pull-right">Save</button>
                                <div style="clear:both;"></div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix">Accounts Head List</h3>
                        </div>
                        <div class="box-body">
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
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Account Head</th>
                                        <th>Account Type</th>
                                        <th>Account Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($hierarchy as $head)
                                        <tr>
                                            <td>{{ $head->code }}. {{ $head->name }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @foreach ($head->newaccounts as $type)
                                            <tr>
                                                <td></td>
                                                <td>{{ $type->code }}. {{ $type->name }}</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @foreach ($type->accountshead as $accountHead)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $accountHead->code }}. {{ $accountHead->name }}</td>
                                                    <td class="mailbox-date text-right">
                                                        @unless ((bool) ($accountHead->is_system ?? false))
                                                            <button onclick="changestatuspost('{{ $accountHead->id }}')" type="button" class="btn {{ (int) ($accountHead->is_posted ?? 0) === 1 ? 'btn-success' : 'btn-danger' }} btn-xs" title="{{ (int) ($accountHead->is_posted ?? 0) === 1 ? 'Is Posted' : 'Is Post' }}"><i class="fa fa-plus"></i></button>
                                                            <a href="{{ route('admin.account.accounts.accountshead.edit', ['account' => $accountHead->id, 'branch' => $accountHead->brc_id ?: $branchId], false) }}" class="btn btn-primary btn-xs" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <button onclick="changestatus('{{ $accountHead->id }}')" type="button" class="btn {{ ($accountHead->is_active ?? 'yes') === 'yes' ? 'btn-success' : 'btn-danger' }} btn-xs" title="{{ ($accountHead->is_active ?? 'yes') === 'yes' ? 'Active' : 'In Active' }}"><i class="fa {{ ($accountHead->is_active ?? 'yes') === 'yes' ? 'fa-check' : 'fa-remove' }}"></i></button>
                                                        @endunless
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="4">No accounts head records found, or the legacy tables are not available in this environment.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function getBranchByID(val) {
            if (val) {
                window.location.href = '{{ url('/admin/account/accounts/accountshead') }}/' + val;
            }
        }

        function setAccountTypeVisibility(value) {
            var blocked = {
                3: 'trevd',
                23: 'invt',
                13: 'trpayabl',
                33: 'sales',
                34: 'salesreturn',
                35: 'purchases',
                36: 'purchasesreturn',
                37: 'costofsales'
            };
            document.querySelectorAll('#ooamsg .alert').forEach(function (element) {
                element.style.display = 'none';
            });

            if (blocked[value]) {
                document.getElementById('ooa').style.display = 'none';
                document.getElementById('ooamsg').style.display = 'block';
                document.querySelector('.' + blocked[value]).style.display = 'block';

                return;
            }

            document.getElementById('ooamsg').style.display = 'none';
            document.getElementById('ooa').style.display = 'block';
        }

        function setOpeningBalanceVisibility(headId) {
            document.getElementById('ob').style.display = ['1', '2', '3'].includes(String(headId)) ? 'block' : 'none';
        }

        function loadAccountTypes(headId, selectedTypeId) {
            var target = document.getElementById('account_type_id');
            target.innerHTML = '<option value="">Select</option>';

            if (!headId) {
                setAccountTypeVisibility('');
                setOpeningBalanceVisibility('');

                return;
            }

            fetch('{{ route('admin.account.accounts.newaccounts.by-head', absolute: false) }}?accounts_head_id=' + encodeURIComponent(headId), {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
                .then(function (response) { return response.json(); })
                .then(function (items) {
                    items.forEach(function (item) {
                        var option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;

                        if (String(selectedTypeId || '') === String(item.id)) {
                            option.selected = true;
                        }

                        target.appendChild(option);
                    });

                    setAccountTypeVisibility(target.value);
                    setOpeningBalanceVisibility(headId);
                });
        }

        function postStatus(url, id) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({id: id})
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (data.status === 'success') {
                        window.location.reload();
                    } else {
                        alert((data.error || ['Unable to update record.']).join(' '));
                    }
                });
        }

        function changestatus(id) {
            postStatus('{{ route('admin.account.accounts.change-status', absolute: false) }}', id);
        }

        function changestatuspost(id) {
            postStatus('{{ route('admin.account.accounts.change-status-post', absolute: false) }}', id);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var headSelect = document.getElementById('accounts_head_id');
            var typeSelect = document.getElementById('account_type_id');

            headSelect.addEventListener('change', function () {
                loadAccountTypes(this.value, '');
            });
            typeSelect.addEventListener('change', function () {
                setAccountTypeVisibility(this.value);
            });
            loadAccountTypes(headSelect.value, '{{ $selectedTypeId }}');
        });
    </script>
@endsection
