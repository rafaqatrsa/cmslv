@extends('admin.layouts.app')

@section('title', 'Staff')

@section('content')
    <form method="GET" action="{{ route('admin.staff.index') }}" class="mb-4 flex gap-2">
        <input name="search" value="{{ request('search') }}" class="w-full rounded border border-neutral-300 px-3 py-2" placeholder="Search staff">
        <button class="rounded bg-blue-600 px-4 py-2 text-white">Search</button>
    </form>

    <div class="overflow-x-auto rounded border border-neutral-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-4 py-3">Employee ID</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($staff as $member)
                    <tr>
                        <td class="px-4 py-3">{{ $member->employee_id }}</td>
                        <td class="px-4 py-3">{{ trim($member->name.' '.$member->surname) }}</td>
                        <td class="px-4 py-3">{{ $member->email }}</td>
                        <td class="px-4 py-3">{{ $member->role?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ (int) $member->is_active === 1 ? 'Active' : 'Inactive' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-3 text-neutral-600">No staff records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $staff->links() }}</div>
@endsection
