@extends('user.layouts.app')

@section('title', 'Student Dashboard')

@section('content')
    @include('user.partials.nav', ['moduleKey' => 'dashboard'])

    <div class="grid gap-4 md:grid-cols-3">
        <section class="rounded border border-neutral-200 bg-white p-4">
            <h2 class="mb-3 text-lg font-semibold">Selected Student</h2>
            <p class="text-sm text-neutral-700">{{ $student?->firstname }} {{ $student?->lastname }}</p>
            <p class="text-sm text-neutral-500">Admission: {{ $student?->admission_no ?: 'Not available' }}</p>
            <p class="text-sm text-neutral-500">Roll: {{ $student?->roll_no ?: 'Not available' }}</p>
        </section>

        <section class="rounded border border-neutral-200 bg-white p-4">
            <h2 class="mb-3 text-lg font-semibold">Class Session</h2>
            <p class="text-sm text-neutral-500">Class ID: {{ $studentSession?->class_id ?: 'Not available' }}</p>
            <p class="text-sm text-neutral-500">Section ID: {{ $studentSession?->section_id ?: 'Not available' }}</p>
            <p class="text-sm text-neutral-500">Session ID: {{ $studentSession?->session_id ?: 'Not available' }}</p>
        </section>

        <section class="rounded border border-neutral-200 bg-white p-4">
            <h2 class="mb-3 text-lg font-semibold">Fee Summary</h2>
            <p class="text-sm text-neutral-500">Assigned: {{ number_format($feeSummary['totals']['assigned_amount'], 2) }}</p>
            <p class="text-sm text-neutral-500">Paid: {{ number_format($feeSummary['totals']['paid_amount'], 2) }}</p>
            <p class="text-sm font-medium text-neutral-900">Balance: {{ number_format($feeSummary['totals']['balance'], 2) }}</p>
        </section>
    </div>
@endsection
