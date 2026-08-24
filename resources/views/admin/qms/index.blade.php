@extends('admin.layouts.app')

@section('title', 'QMS')

@section('content')
    <section class="rounded border border-amber-200 bg-amber-50 p-4 text-amber-900">
        <h2 class="font-semibold">Migration note</h2>
        <p class="mt-2 text-sm">{{ $migrationNote }}</p>
    </section>
@endsection
