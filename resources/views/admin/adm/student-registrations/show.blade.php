@extends('admin.layouts.app')

@section('title', 'Student Registration Details')

@section('content')
    @include('admin.adm.partials.nav')

    @php
        $student = $registration;
        $photo = $student->image ? Storage::disk('public')->url($student->image) : null;
        $basicDetails = [
            ['label' => 'Branch', 'value' => data_get($branches->firstWhere('id', $student->brc_id), 'name', $student->brc_id)],
            ['label' => 'Class', 'value' => data_get($classes->firstWhere('id', $student->class_id), 'class', $student->class_id)],
            ['label' => 'Session', 'value' => data_get($sessions->firstWhere('id', $student->session_id), 'session', $student->session_id)],
            ['label' => 'Academic Year', 'value' => data_get($academicYears->firstWhere('id', $student->adcademicyear_id), 'name', $student->adcademicyear_id)],
            ['label' => 'Registration Date', 'value' => $student->regd_date?->format('d M Y')],
            ['label' => 'Gender', 'value' => $student->gender],
            ['label' => 'Date of Birth', 'value' => $student->dob?->format('d M Y')],
            ['label' => 'Mobile', 'value' => trim(($student->mobile_country_code ? '+'.$student->mobile_country_code.' ' : '').($student->mobileno ?? ''))],
            ['label' => 'Religion', 'value' => data_get($religions->firstWhere('id', $student->religion), 'name', $student->religion)],
            ['label' => 'Medium', 'value' => data_get($mediums->firstWhere('id', $student->medium_id), 'name', $student->medium_id)],
            ['label' => 'Previous School', 'value' => data_get($previousSchools->firstWhere('id', $student->previous_school_id), 'name', $student->previous_school_id)],
            ['label' => 'Previous Class', 'value' => $student->previous_class],
            ['label' => 'Leaving Date', 'value' => $student->pervious_schl_leaving_date?->format('d M Y')],
            ['label' => 'Bay Form No', 'value' => $student->bayformno],
            ['label' => 'District', 'value' => data_get($districts->firstWhere('id', $student->district_id), 'name', $student->district_id)],
            ['label' => 'Tehsils', 'value' => data_get($tehsils->firstWhere('id', $student->tehsils_id), 'name', $student->tehsils_id)],
            ['label' => 'Area', 'value' => data_get($areas->firstWhere('id', $student->area_id), 'name', $student->area_id)],
            ['label' => 'Status', 'value' => $student->is_active],
        ];
        $parentDetails = [
            ['label' => 'Father Name', 'value' => $student->father_name],
            ['label' => 'Father Phone', 'value' => trim(($student->father_country_code ? '+'.$student->father_country_code.' ' : '').($student->father_phone ?? ''))],
            ['label' => 'Father Occupation', 'value' => $student->father_occupation],
            ['label' => 'Father CNIC', 'value' => $student->father_cnic],
            ['label' => 'Mother Name', 'value' => $student->mother_name],
            ['label' => 'Mother Phone', 'value' => trim(($student->mother_country_code ? '+'.$student->mother_country_code.' ' : '').($student->mother_phone ?? ''))],
            ['label' => 'Mother Occupation', 'value' => $student->mother_occupation],
            ['label' => 'Guardian Is', 'value' => $student->guardian_is],
            ['label' => 'Guardian Name', 'value' => $student->guardian_name],
            ['label' => 'Guardian Relation', 'value' => $student->guardian_relation],
            ['label' => 'Guardian Phone', 'value' => trim(($student->guardian_country_code ? '+'.$student->guardian_country_code.' ' : '').($student->guardian_phone ?? ''))],
            ['label' => 'Guardian Occupation', 'value' => $student->guardian_occupation],
            ['label' => 'Guardian Email', 'value' => $student->guardian_email],
            ['label' => 'Address', 'value' => $student->address],
        ];
    @endphp

    <section class="mt-4 overflow-hidden rounded-2xl border border-neutral-300 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-200 bg-[#2f61b3] px-4 py-3 text-white">
            <div>
                <p class="text-xs uppercase tracking-[0.28em] text-white/70">Admission Process</p>
                <h1 class="text-2xl font-semibold">Student Registration Details</h1>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.adm.student-registrations.edit', $student) }}" class="rounded-full bg-white px-3 py-1.5 text-sm font-semibold text-[#2f61b3] no-underline">
                    Edit
                </a>
                <a href="{{ route('admin.adm.student-registrations.index') }}" class="rounded-full border border-white/30 px-3 py-1.5 text-sm font-semibold text-white no-underline">
                    Back
                </a>
                <button type="button" onclick="window.print()" class="rounded-full bg-slate-900 px-3 py-1.5 text-sm font-semibold text-white">
                    Print
                </button>
            </div>
        </div>

        <div class="grid gap-6 p-4 xl:grid-cols-[220px_1fr]">
            <div class="space-y-4">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                    @if ($photo)
                        <img src="{{ $photo }}" alt="Student photo" class="h-64 w-full object-cover">
                    @else
                        <div class="flex h-64 items-center justify-center text-sm text-slate-500">No photo available</div>
                    @endif
                </div>

                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Registration No</p>
                    <p class="text-xl font-semibold text-slate-900">{{ $student->regd_no }}</p>
                    <p class="mt-1 text-sm text-slate-600">{{ trim($student->firstname.' '.$student->lastname) }}</p>
                </div>
            </div>

            <div class="space-y-6">
                <section class="rounded-2xl border border-slate-200">
                    <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                        <h2 class="text-lg font-semibold text-slate-800">Basic Details</h2>
                    </div>

                    <div class="grid gap-4 p-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ($basicDetails as $detail)
                            <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">{{ $detail['label'] }}</p>
                                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $detail['value'] ?: 'N/A' }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200">
                    <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                        <h2 class="text-lg font-semibold text-slate-800">Parent and Guardian</h2>
                    </div>

                    <div class="grid gap-4 p-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ($parentDetails as $detail)
                            <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 {{ $detail['label'] === 'Address' ? 'xl:col-span-2' : '' }}">
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">{{ $detail['label'] }}</p>
                                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $detail['value'] ?: 'N/A' }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200">
                    <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                        <h2 class="text-lg font-semibold text-slate-800">Fee Details</h2>
                    </div>

                    <div class="overflow-x-auto p-4">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th class="px-4 py-3">Fee Head</th>
                                    <th class="px-4 py-3">Frequency</th>
                                    <th class="px-4 py-3">Amount</th>
                                    <th class="px-4 py-3">Note</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($student->fees as $fee)
                                    <tr>
                                        <td class="px-4 py-3">{{ data_get($feeHeads->firstWhere('id', $fee->feetype_id), 'name', $fee->feetype_id) }}</td>
                                        <td class="px-4 py-3">{{ $fee->frequency ?: 'N/A' }}</td>
                                        <td class="px-4 py-3">{{ number_format((float) $fee->amount, 2) }}</td>
                                        <td class="px-4 py-3">{{ $fee->note ?: 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-slate-500">No fees assigned.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
