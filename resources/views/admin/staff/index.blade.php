@extends('admin.layouts.app')

@section('title', 'Staff')

@section('content')
    @php
        $module = [
            'label' => 'Staff',
            'route' => 'admin.staff.index',
            'columns' => ['employee_id', 'full_name', 'email', 'role_name', 'status']
        ];
        $mappedStaff = clone $staff;
        $mappedStaff->setCollection(
            $staff->getCollection()->map(function($m) {
                return (object)[
                    'employee_id' => $m->employee_id,
                    'full_name' => trim($m->name . ' ' . $m->surname),
                    'email' => $m->email,
                    'role_name' => $m->role?->name ?? '-',
                    'status' => (int) $m->is_active === 1 ? 'Active' : 'Inactive',
                ];
            })
        );
        $records = $mappedStaff;
    @endphp

    @include('admin.partials.module_table_component')
@endsection
