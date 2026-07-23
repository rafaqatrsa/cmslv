@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
    <section class="admin-dashboard-section overflow-hidden rounded border border-neutral-300 bg-white shadow-sm">
        <div class="admin-module-tabs flex flex-wrap gap-4 border-b border-amber-300 px-3 py-3">
            <a href="{{ route('admin.systemsettings.dashboard', absolute: false) }}" class="admin-module-tab is-active bg-[#2f61b3] text-white">
                <i class="fa-solid fa-desktop"></i>
                <span>DASHBOARD</span>
            </a>
            <a href="{{ route('admin.systemsettings.dashboard', absolute: false) }}" class="admin-module-tab bg-white text-neutral-800">
                <i class="fa-solid fa-gears"></i>
                <span>SYSTEM SETTINGS</span>
            </a>
        </div>

        <div class="grid gap-4 p-4 xl:grid-cols-2">
            @foreach ($settingGroups as $group => $items)
                <section class="rounded-xl border border-neutral-300 bg-white shadow-sm">
                    <h2 class="bg-[#2f61b3] px-3 py-2 text-sm font-semibold uppercase tracking-wide text-white">{{ $group }}</h2>
                    <div class="grid gap-3 p-3 sm:grid-cols-2">
                        @foreach ($items as $item)
                            <div class="flex min-h-[88px] items-center rounded-xl border border-neutral-300 bg-neutral-50 px-4 py-3 text-sm font-semibold text-neutral-800 shadow-sm">
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </section>
@endsection
