@php($isHrmsRoute = request()->routeIs('admin.hrms.*'))

<header class="admin-topbar fixed left-0 right-0 top-0 z-20 h-16 bg-[#24448d] text-white shadow lg:left-[296px]">
    <div class="admin-topbar-inner flex h-full items-center gap-4 px-3">
        <button type="button" class="{{ $isHrmsRoute ? 'text-[20px]' : 'text-2xl' }} leading-none" aria-label="Toggle menu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <a
            href="{{ route('admin.dashboard', absolute: false) }}"
            class="admin-brand {{ $isHrmsRoute ? 'min-w-[278px] rounded-l-full rounded-r-[14px] bg-[#4473c9] px-4 text-[18px] tracking-normal' : 'min-w-[300px] rounded-tl-full rounded-tr-2xl bg-[#3f70c9] px-5 text-2xl tracking-wide' }} flex h-9 items-center font-bold shadow-inner"
        >
            TNT SOL
        </a>

        <form class="admin-search {{ $isHrmsRoute ? 'max-w-[490px]' : 'max-w-[420px]' }} mx-auto hidden w-full items-center overflow-hidden rounded-full bg-white text-neutral-700 shadow-sm md:flex">
            <input class="min-w-0 flex-1 border-0 px-4 py-2 text-sm outline-none" placeholder="Search By Admit No, Name, Father Name... Etc." type="search">
            <button class="flex h-10 w-12 items-center justify-center bg-[#3f70c9] text-lg text-white" type="submit" aria-label="Search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <div class="admin-topbar-icons ml-auto flex items-center gap-5 text-[20px]">
            <span title="Calculator"><i class="fa-solid fa-calculator"></i></span>
            <span title="Notifications"><i class="fa-regular fa-bell"></i></span>
            <span title="Messages"><i class="fa-regular fa-comment"></i></span>
            <span title="Calendar"><i class="fa-regular fa-calendar-days"></i></span>
            <span class="relative" title="Tasks">
                <i class="fa-regular fa-square-check"></i>
                <span class="absolute -right-2 -top-2 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-orange-500 text-[9px] font-bold">1</span>
            </span>
            <span title="Birthdays"><i class="fa-solid fa-cake-candles"></i></span>
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-sm text-slate-500">
                <i class="fa-regular fa-user"></i>
            </span>
        </div>
    </div>
</header>
