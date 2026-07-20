@extends('user.layouts.app')

@section('title', 'Profile')

@section('content')
    @include('user.partials.nav', ['moduleKey' => 'profile'])

    <section class="rounded border border-neutral-200 bg-white p-4">
        <h2 class="mb-4 text-lg font-semibold">Student Profile</h2>

        @if ($student)
            <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach (['admission_no', 'roll_no', 'firstname', 'middlename', 'lastname', 'mobileno', 'email', 'dob', 'gender', 'father_name', 'mother_name', 'guardian_name'] as $column)
                    <div>
                        <dt class="text-sm font-medium text-neutral-500">{{ \Illuminate\Support\Str::headline($column) }}</dt>
                        <dd class="mt-1 text-neutral-900">{{ data_get($student, $column) ?: 'Not provided' }}</dd>
                    </div>
                @endforeach
            </dl>
        @else
            <p class="text-neutral-600">No linked student profile is available for this authenticated account.</p>
        @endif
    </section>
@endsection
