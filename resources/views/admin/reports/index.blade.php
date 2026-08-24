@extends('admin.layouts.app')

@section('title', 'Reports')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($reportCards as $label => $value)
            <section class="rounded border border-neutral-200 bg-white p-4">
                <p class="text-sm text-neutral-500">{{ $label }}</p>
                <p class="mt-2 text-2xl font-semibold">{{ number_format($value) }}</p>
            </section>
        @endforeach
    </div>
@endsection
