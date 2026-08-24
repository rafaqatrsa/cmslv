<section class="mb-4">
    <h1 class="text-2xl font-semibold text-neutral-900">Accounts</h1>
    <div class="mt-3 flex flex-wrap gap-2 text-sm">
        @foreach (app(\App\Services\Account\AccountModuleRegistry::class)->all() as $key => $item)
            @php
                $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*') || (str_contains($item['route'], 'feerevise') && str_contains(request()->route()->getName() ?? '', 'feerevise'));
            @endphp
            <a href="{{ route($item['route'], absolute: false) }}" class="rounded border px-3 py-2 {{ $isActive ? 'border-blue-600 bg-blue-50 text-blue-700 font-medium' : 'border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-50' }}">
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>
</section>
