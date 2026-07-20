@extends('admin.layouts.app')

@section('title', 'System Notifications')

@section('content')
    <form method="GET" action="{{ route('admin.system-notification.index') }}" class="mb-4 grid gap-3 rounded border border-neutral-200 bg-white p-4 md:grid-cols-5">
        <input name="search" value="{{ request('search') }}" class="rounded border border-neutral-300 px-3 py-2 md:col-span-2" placeholder="Search title, description, type">
        <select name="brc_id" class="rounded border border-neutral-300 px-3 py-2">
            <option value="">All branches</option>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}" @selected((string) request('brc_id') === (string) $branch->id)>{{ $branch->name }}</option>
            @endforeach
        </select>
        <input name="type" value="{{ request('type') }}" class="rounded border border-neutral-300 px-3 py-2" placeholder="Type">
        <button class="rounded bg-blue-600 px-4 py-2 text-white">Filter</button>
    </form>

    <div class="overflow-x-auto rounded border border-neutral-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">For</th>
                    <th class="px-4 py-3">Branch</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($notifications as $notification)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $notification->notification_title }}</p>
                            <p class="text-neutral-600">{{ \Illuminate\Support\Str::limit($notification->notification_desc, 100) }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $notification->notification_type }}</td>
                        <td class="px-4 py-3">{{ $notification->notification_for }}</td>
                        <td class="px-4 py-3">{{ $notification->branch?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $notification->date?->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">{{ $notification->is_active }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-3 text-neutral-600">No system notifications found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $notifications->links() }}</div>
@endsection
