@extends('admin.layouts.app')

@section('title', 'Student Details')

@section('content')
    @include('admin.adm.partials.nav')
    <section class="mt-4 overflow-hidden rounded-xl border border-[#d8d8d8] bg-white shadow-sm">
        <div class="flex items-center justify-between border-b px-4 py-3"><h1 class="text-xl font-semibold">Student Details</h1><a class="directory-primary" href="{{ url()->previous() }}">Back</a></div>
        <div class="grid gap-5 p-5 md:grid-cols-[150px_1fr]">
            <img class="h-36 w-36 rounded border object-cover" src="{{ $student->image ? asset('storage/'.$student->image) : asset('assets/images/no_image.png') }}" alt="{{ $student->firstname }}">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2"><div><dt class="font-semibold">Student</dt><dd>{{ trim($student->firstname.' '.$student->middlename.' '.$student->lastname) }}</dd></div><div><dt class="font-semibold">Admission No</dt><dd>{{ $student->admission_no ?: 'N/A' }}</dd></div><div><dt class="font-semibold">Branch</dt><dd>{{ $branch ?: 'N/A' }}</dd></div><div><dt class="font-semibold">Class / Section</dt><dd>{{ $class ?: 'N/A' }} / {{ $section ?: 'N/A' }}</dd></div><div><dt class="font-semibold">Father</dt><dd>{{ $student->father_name ?: 'N/A' }}</dd></div><div><dt class="font-semibold">Phone</dt><dd>{{ $student->mobileno ?: $student->father_phone ?: 'N/A' }}</dd></div><div><dt class="font-semibold">Date of Birth</dt><dd>{{ $student->dob?->format('Y-m-d') ?: 'N/A' }}</dd></div><div><dt class="font-semibold">Gender</dt><dd>{{ $student->gender ?: 'N/A' }}</dd></div><div class="sm:col-span-2"><dt class="font-semibold">Address</dt><dd>{{ $student->current_address ?: 'N/A' }}</dd></div></dl>
        </div>
        @if ($student->documents->isNotEmpty())<div class="border-t p-5"><h2 class="mb-3 font-semibold">Documents</h2><ul class="list-disc pl-5">@foreach ($student->documents as $document)<li>{{ $document->title ?: 'Document' }}{{ $document->doc ? ' - '.$document->doc : '' }}</li>@endforeach</ul></div>@endif
    </section>
@endsection
