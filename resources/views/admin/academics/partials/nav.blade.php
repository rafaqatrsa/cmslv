<nav class="mb-4 flex gap-2 overflow-x-auto rounded border border-neutral-200 bg-white p-3 text-sm">
    <a href="{{ route('admin.academics.dashboard') }}" class="shrink-0 rounded px-3 py-2 {{ request()->routeIs('admin.academics.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-neutral-700 hover:bg-neutral-100' }}">Overview</a>
    @foreach ($modules ?? app(\App\Services\Academics\AcademicModuleRegistry::class)->all() as $key => $module)
        <a href="{{ route($module['route']) }}" class="shrink-0 rounded px-3 py-2 {{ request()->routeIs($module['route']) ? 'bg-blue-50 text-blue-700' : 'text-neutral-700 hover:bg-neutral-100' }}">
            {{ $module['label'] }}
        </a>
    @endforeach
</nav>
