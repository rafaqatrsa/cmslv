@extends('user.layouts.app')

@section('title', $module['label'])

@section('content')
    @include('user.partials.nav')

    <form method="GET" action="{{ route($module['route']) }}" class="mb-4 flex gap-2 rounded border border-neutral-200 bg-white p-3">
        <input name="search" value="{{ request('search') }}" class="w-full rounded border border-neutral-300 px-3 py-2" placeholder="Search {{ strtolower($module['label']) }}">
        <button class="rounded bg-blue-600 px-4 py-2 text-white">Search</button>
    </form>

    <section class="overflow-x-auto rounded border border-neutral-200 bg-white">
        <div class="border-b border-neutral-200 px-4 py-3">
            <p class="text-sm text-neutral-500">Legacy table: {{ $module['table'] }}</p>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="bg-neutral-50">
                <tr>
                    @foreach ($module['columns'] as $column)
                        <th class="px-4 py-3">{{ \Illuminate\Support\Str::headline($column) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($records as $record)
                    <tr>
                        @foreach ($module['columns'] as $column)
                            <td class="max-w-sm px-4 py-3">
                                {{ \Illuminate\Support\Str::limit(strip_tags((string) data_get($record, $column)), 120) }}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($module['columns']) }}" class="px-4 py-3 text-neutral-600">
                            No {{ strtolower($module['label']) }} records found, or the legacy table is not available in this environment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="mt-4">{{ $records->links() }}</div>
@endsection
