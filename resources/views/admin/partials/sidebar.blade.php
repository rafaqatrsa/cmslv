@php
    $admSidebarService = app(\App\Services\Adm\AdmSidebarService::class);
    $sidebarItems = $admSidebarService->items();
    $isAdmissionExpanded = $admSidebarService->isAdmissionExpanded();
@endphp

<aside class="admin-sidebar fixed inset-y-0 left-0 z-30 w-[296px] overflow-hidden bg-[#24448d] text-white shadow-xl">
    <div class="admin-sidebar-header flex h-16 items-center gap-2 border-b border-white/10 bg-[#24448d] px-2">
        <div class="admin-avatar flex h-11 w-11 items-center justify-center rounded-full border border-white/50 bg-white/90 text-2xl text-slate-500">
            <i class="fa-regular fa-user"></i>
        </div>
        <a href="{{ route('admin.dashboard', absolute: false) }}" class="min-w-0 text-lg font-semibold text-white no-underline">
            <i class="fa-regular fa-hand-point-right"></i>
            Super Admin
        </a>
    </div>

    <div class="border-b border-black/20 bg-[#254693] px-4 py-2 shadow-inner">
        <p class="text-base font-semibold">Current Session: 2026-27</p>
        <div class="mt-2 flex items-center justify-between text-base">
            <span>Quick Links</span>
            <span class="text-lg"><i class="fa-solid fa-grip"></i></span>
        </div>
    </div>

    <nav class="h-[calc(100vh-136px)] overflow-y-auto pb-6 pt-2 [scrollbar-width:thin]">
        @foreach ($sidebarItems as $item)
            @if (($item['children'] ?? []) !== [])
                @php
                    $isExpanded = $isAdmissionExpanded;
                @endphp

                <div class="border-b border-white/10">
                    <a
                        href="{{ route($item['route'], absolute: false) }}"
                        class="admin-sidebar-link flex w-full items-center gap-3 px-3 py-3 text-[16px] font-semibold transition hover:bg-white/10 {{ $isExpanded ? 'is-active bg-[#1d3a7d]' : '' }}"
                        aria-current="{{ $isExpanded ? 'page' : 'false' }}"
                    >
                        <span class="w-6 text-center text-lg"><i class="{{ $item['icon'] }}"></i></span>
                        <span class="min-w-0 flex-1 truncate text-left">{{ $item['label'] }}</span>
                        <span class="text-2xl leading-none">
                            <i class="fa-solid fa-angle-{{ $isExpanded ? 'down' : 'right' }}"></i>
                        </span>
                    </a>

                    <div class="{{ $isExpanded ? '' : 'hidden' }} bg-[#355aa8] py-1" data-admission-menu>
                        @foreach ($item['children'] as $child)
                            @if ($child['is_disabled'])
                                <span class="ml-6 flex items-center gap-3 px-3 py-2 text-[15px] font-semibold text-white/45">
                                    <span class="w-5 text-center text-sm"><i class="{{ $child['icon'] }}"></i></span>
                                    <span class="min-w-0 flex-1 truncate">{{ $child['label'] }}</span>
                                </span>
                            @else
                                <a
                                    href="{{ route($child['route'], absolute: false) }}"
                                    class="ml-6 flex items-center gap-3 px-3 py-2 text-[15px] font-semibold text-white transition hover:bg-white/10 {{ request()->routeIs($child['route']) ? 'bg-[#1d3a7d] text-white' : '' }}"
                                >
                                    <span class="w-5 text-center text-sm"><i class="{{ $child['icon'] }}"></i></span>
                                    <span class="min-w-0 flex-1 truncate">{{ $child['label'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <a
                    href="{{ route($item['route'], absolute: false) }}"
                    class="admin-sidebar-link flex items-center gap-3 px-3 py-3 text-[16px] font-semibold transition hover:bg-white/10 {{ request()->routeIs($item['route']) ? 'is-active bg-[#1d3a7d]' : '' }}"
                >
                    <span class="w-6 text-center text-lg"><i class="{{ $item['icon'] }}"></i></span>
                    <span class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                    @unless ($loop->first)
                        <span class="text-2xl leading-none"><i class="fa-solid fa-angle-right"></i></span>
                    @endunless
                </a>
            @endif
        @endforeach
    </nav>
</aside>
