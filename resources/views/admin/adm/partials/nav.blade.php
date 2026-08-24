@php
    $modules = app(\App\Services\Adm\AdmModuleRegistry::class)->all();
@endphp

<section class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm">
    <div class="border-b border-[#f1b44c] bg-[#f8fafc] px-4 py-3">
        <h1 class="text-[27px] font-semibold tracking-tight text-neutral-900">ADM / Student Affairs</h1>
    </div>

    <div class="flex flex-wrap gap-3 px-4 py-4">
        @foreach ($modules as $key => $item)
            <a
                href="{{ route($item['route']) }}"
                class="{{ request()->routeIs($item['route']) ? 'border-[#2f61b3] bg-[#2f61b3] text-white shadow-sm' : 'border-neutral-200 bg-white text-[#2f2f2f] hover:border-[#b8c6e2] hover:bg-[#f8fbff]' }} inline-flex items-center gap-2 rounded-lg border px-4 py-2.5 text-[15px] font-medium no-underline transition-colors"
            >
                <span class="text-[15px] leading-none">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</section>
