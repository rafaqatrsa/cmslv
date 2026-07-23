@php($isHrmsRoute = request()->routeIs('admin.hrms.*'))

<footer class="{{ $isHrmsRoute ? 'bg-transparent px-0 py-0 text-[11px] text-[#666]' : 'border-t border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-500' }} text-center lg:ml-[296px]">
    @if ($isHrmsRoute)
        <div class="mx-auto mt-2 w-[62%] bg-white py-2.5 shadow-[0_1px_2px_rgba(15,23,42,0.06)]">
            &copy; {{ now()->year }} {{ config('app.name', 'Laravel') }}
        </div>
    @else
        &copy; {{ now()->year }} {{ config('app.name', 'Laravel') }}
    @endif
</footer>
