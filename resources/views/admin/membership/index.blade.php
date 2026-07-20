@extends('admin.layouts.app')

@section('title', 'Membership')

@section('content')
    <form method="GET" action="{{ route('admin.membership.index') }}" class="mb-4 flex gap-2">
        <input name="search" value="{{ request('search') }}" class="w-full rounded border border-neutral-300 px-3 py-2" placeholder="Search members">
        <button class="rounded bg-blue-600 px-4 py-2 text-white">Search</button>
    </form>

    <div class="overflow-x-auto rounded border border-neutral-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-4 py-3">Card No</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Member ID</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($members as $member)
                    <tr>
                        <td class="px-4 py-3">{{ $member->library_card_no }}</td>
                        <td class="px-4 py-3">{{ $member->member_type }}</td>
                        <td class="px-4 py-3">{{ $member->member_id }}</td>
                        <td class="px-4 py-3">{{ $member->is_active }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-3 text-neutral-600">No membership records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $members->links() }}</div>
@endsection
