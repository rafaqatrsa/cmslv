@extends('admin.layouts.app')

@section('title', 'ADM / Student Affairs')

@section('content')
    @include('admin.adm.partials.nav')

    <section class="mt-4 overflow-hidden rounded border border-neutral-300 bg-white shadow-sm">
        <div class="grid gap-3 p-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => 'ADMISSION INQUIRY', 'value' => $stats['admission_inquiries'], 'meta' => 'TODAY: '.$stats['admission_inquiries_today'].' / WON: '.$stats['admission_inquiries_won'], 'icon' => 'fa-regular fa-clipboard', 'color' => 'text-red-500', 'href' => route('admin.adm.enquiries.index', absolute: false)],
                ['label' => 'REGISTRATION', 'value' => $stats['registrations'], 'meta' => 'SELF: '.$stats['registrations_self'].' / ONLINE: '.$stats['registrations_online'], 'icon' => 'fa-solid fa-file-signature', 'color' => 'text-neutral-700', 'href' => route('admin.adm.student-registrations.index', absolute: false)],
                ['label' => 'ADMISSION', 'value' => $stats['admissions'], 'meta' => 'TODAY: '.$stats['admissions_today'], 'icon' => 'fa-regular fa-id-badge', 'color' => 'text-emerald-500', 'href' => route('admin.adm.students.index', absolute: false)],
                ['label' => 'STUDENTS', 'value' => $stats['students'], 'meta' => 'FAMILIES: '.$stats['families'], 'icon' => 'fa-solid fa-user-graduate', 'color' => 'text-orange-500', 'href' => route('admin.adm.students.index', absolute: false)],
            ] as $card)
                <a href="{{ $card['href'] }}" class="flex min-h-24 items-center gap-3 rounded-xl border border-neutral-300 bg-white px-4 py-3 shadow-sm transition hover:-translate-y-0.5 hover:border-[#2f61b3] hover:shadow-md">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-neutral-50 text-2xl {{ $card['color'] }}">
                        <i class="{{ $card['icon'] }}"></i>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-xs font-semibold tracking-wide {{ $card['color'] }}">{{ $card['label'] }}</span>
                        <span class="block text-2xl font-bold text-[#2f61b3]">{{ $card['value'] }}</span>
                        <span class="block truncate text-xs font-medium text-neutral-500">{{ $card['meta'] }}</span>
                    </span>
                </a>
            @endforeach
        </div>

        <div class="grid gap-3 border-t border-neutral-200 p-3 lg:grid-cols-3">
            <section class="rounded-xl border border-neutral-300 bg-white shadow-sm">
                <h2 class="bg-[#2f61b3] px-3 py-2 text-sm font-semibold text-white">Admission Enquiry</h2>
                <div class="space-y-4 p-4">
                    @foreach ($stats['enquiry_overview'] as $status => $overview)
                        <div>
                            <div class="mb-1 flex justify-between text-xs font-semibold uppercase text-neutral-600">
                                <span>{{ $overview['count'] }} {{ $status }}</span>
                                <span>{{ $overview['percentage'] }}%</span>
                            </div>
                            <div class="h-1.5 rounded bg-neutral-200">
                                <div class="h-full rounded {{ $status === 'won' ? 'bg-amber-500' : ($status === 'active' ? 'bg-red-500' : 'bg-orange-400') }}" style="width: {{ $overview['percentage'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-xl border border-neutral-300 bg-white shadow-sm">
                <h2 class="bg-[#2f61b3] px-3 py-2 text-sm font-semibold text-white">Student Overview</h2>
                <div class="grid grid-cols-2 gap-3 p-4 text-center">
                    <div class="rounded-lg bg-emerald-50 p-4"><p class="text-xs uppercase text-emerald-700">Students</p><p class="text-2xl font-bold text-emerald-700">{{ $stats['students'] }}</p></div>
                    <div class="rounded-lg bg-sky-50 p-4"><p class="text-xs uppercase text-sky-700">Families</p><p class="text-2xl font-bold text-sky-700">{{ $stats['families'] }}</p></div>
                    <div class="rounded-lg bg-orange-50 p-4"><p class="text-xs uppercase text-orange-700">Complaints</p><p class="text-2xl font-bold text-orange-700">{{ $stats['complaints'] }}</p></div>
                    <div class="rounded-lg bg-violet-50 p-4"><p class="text-xs uppercase text-violet-700">Staff</p><p class="text-2xl font-bold text-violet-700">{{ $stats['staff'] }}</p></div>
                </div>
            </section>

            <section class="rounded-xl border border-neutral-300 bg-white shadow-sm">
                <h2 class="bg-[#2f61b3] px-3 py-2 text-sm font-semibold text-white">Quick Actions</h2>
                <div class="grid gap-2 p-4">
                    <a href="{{ route('admin.adm.student-registrations.create', absolute: false) }}" class="rounded-lg bg-[#2f61b3] px-3 py-2 text-center text-sm font-semibold text-white hover:bg-[#244d91]">Add Student Registration</a>
                    <a href="{{ route('admin.adm.students.index', absolute: false) }}" class="rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm font-semibold text-neutral-700 hover:border-[#2f61b3] hover:text-[#2f61b3]">View Students</a>
                    <a href="{{ route('admin.adm.enquiries.index', absolute: false) }}" class="rounded-lg border border-neutral-300 px-3 py-2 text-center text-sm font-semibold text-neutral-700 hover:border-[#2f61b3] hover:text-[#2f61b3]">View Admission Enquiries</a>
                </div>
            </section>
        </div>
    </section>
@endsection
