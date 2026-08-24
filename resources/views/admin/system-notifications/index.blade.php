@extends('admin.layouts.app')

@section('title', 'System Notifications')

@section('content')
    @php
        $module = [
            'label' => 'System Notifications',
            'route' => 'admin.system-notification.index',
            'columns' => ['notification_title', 'notification_desc', 'notification_type', 'notification_for', 'branch_name', 'date', 'is_active']
        ];
        $mappedNotifications = clone $notifications;
        $mappedNotifications->setCollection(
            $notifications->getCollection()->map(function($n) {
                return (object)[
                    'notification_title' => $n->notification_title,
                    'notification_desc' => \Illuminate\Support\Str::limit($n->notification_desc, 100),
                    'notification_type' => $n->notification_type,
                    'notification_for' => $n->notification_for,
                    'branch_name' => $n->branch?->name ?? '-',
                    'date' => $n->date?->format('Y-m-d H:i') ?? '-',
                    'is_active' => $n->is_active ? 'Active' : 'Inactive',
                ];
            })
        );
        $records = $mappedNotifications;
    @endphp

    @include('admin.partials.module_table_component')
@endsection
