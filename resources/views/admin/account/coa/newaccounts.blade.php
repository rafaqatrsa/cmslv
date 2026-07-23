@extends('admin.layouts.app')

@section('title', $title)

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

                        <form action="{{ $account ? route('admin.account.accounts.newaccounts.update', $account->id, false) : route('admin.account.accounts.newaccounts.store', absolute: false) }}" method="post" accept-charset="utf-8">
                            @csrf
                            <div class="box-body">
                                @if (session('success'))
                                    <div class="alert alert-success text-left">{{ session('success') }}</div>
                                @endif

                                @if ($account)
                                    <input type="hidden" name="id" value="{{ $account->id }}">
                                @endif

                                <div class="form-group">
                                    <label>Accounts Head</label><small class="req"> *</small>
                                    <select id="accounts_type_id" name="accounts_type_id" class="form-control selectval">
                                        <option value="">Select</option>
                                        @foreach ($accountTypes as $accountType)
                                            <option value="{{ $accountType->id }}" @selected((string) old('accounts_type_id', $account->accounts_type_id ?? '') === (string) $accountType->id)>{{ $accountType->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('accounts_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Account Type Name</label> <small class="req"> *</small>
                                    <input autofocus id="name" name="name" type="text" class="form-control" value="{{ old('name', $account->name ?? '') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $account->note ?? '') }}</textarea>
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
                            <h3 class="box-title titlefix">Accounts Type List</h3>
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
                                        <th>Account Type</th>
                                        <th>Account Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($hierarchy as $type)
                                        <tr>
                                            <td class="mailbox-name">{{ $type->code }}. {{ $type->name }}</td>
                                            <td class="mailbox-name"></td>
                                            <td class="mailbox-name"></td>
                                        </tr>
                                        @foreach ($type->newaccounts as $newAccount)
                                            <tr>
                                                <td class="mailbox-name"></td>
                                                <td class="mailbox-name">{{ $newAccount->code }}. {{ $newAccount->name }}</td>
                                                <td class="mailbox-date text-right">
                                                    @unless ((bool) ($newAccount->is_system ?? false))
                                                        <a href="{{ route('admin.account.accounts.newaccounts.edit', $newAccount->id, false) }}" class="btn btn-primary btn-xs" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    @endunless
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="3">No account type records found, or the legacy tables are not available in this environment.</td>
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
@endsection
