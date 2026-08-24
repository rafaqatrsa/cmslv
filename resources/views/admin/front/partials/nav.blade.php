<section class="mb-4">
    <h1 class="text-2xl font-semibold text-neutral-900">Front CMS</h1>
    <div class="mt-3 flex flex-wrap gap-2 text-sm">
        @foreach (app(\App\Services\Front\FrontModuleRegistry::class)->all() as $key => $item)
            <a href="{{ route($item['route']) }}" class="rounded border px-3 py-2 {{ request()->routeIs($item['route']) ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-50' }}">
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>
</section>
