<section class="mb-5 rounded-[24px] border border-slate-200/80 bg-white p-4 shadow-sm shadow-slate-200/60 sm:p-5">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-700">Administration</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">HRMS</h1>
            <p class="mt-1 text-sm text-slate-500">Manage staff, documents, manuals, and supporting legacy records.</p>
        </div>
    </div>

    <div class="mt-5 flex flex-wrap gap-3 text-sm">
        @foreach (app(\App\Services\Hrms\HrmsModuleRegistry::class)->all() as $key => $item)
            <a
                href="{{ route($item['route']) }}"
                class="inline-flex items-center rounded-full border px-4 py-2.5 font-medium transition {{ request()->routeIs($item['route']) ? 'border-sky-200 bg-sky-50 text-sky-700 shadow-xs' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900' }}"
            >
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>
</section>
