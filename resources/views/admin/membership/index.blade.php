@extends('admin.layouts.app')

@section('title', 'Membership')

@section('content')
    @php
        $module = [
            'label' => 'Membership',
            'route' => 'admin.membership.index',
            'columns' => ['library_card_no', 'member_type', 'member_id', 'is_active']
        ];
        $records = $members;
    @endphp

    @include('admin.partials.module_table_component')
@endsection
