@extends('teacher.layouts.app')

@section('title', 'Teacher Profile')

@section('content')
    @include('teacher.partials.nav', ['moduleKey' => 'profile'])

    <section class="rounded border border-neutral-200 bg-white p-4">
        <p class="mb-4 text-sm text-neutral-500">Legacy table: {{ $module['table'] }}</p>

        @if ($teacher)
            <dl class="grid gap-4 sm:grid-cols-2">
                @foreach ($module['columns'] as $column)
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">{{ \Illuminate\Support\Str::headline($column) }}</dt>
                        <dd class="mt-1 text-neutral-900">{{ data_get($teacher, $column) ?: 'Not provided' }}</dd>
                    </div>
                @endforeach
            </dl>
        @else
            <p class="text-neutral-600">
                Teacher profile #{{ $id }} could not be loaded because the legacy staff table is not available in this environment.
            </p>
        @endif
    </section>
@endsection
