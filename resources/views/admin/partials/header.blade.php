<header class="admin-topbar fixed left-0 right-0 top-0 z-20 h-16 bg-[#24448d] text-white shadow lg:left-[304px]">
    <div class="admin-topbar-inner flex h-full items-center gap-4 px-4">
        <button type="button" class="text-[22px] leading-none" aria-label="Toggle menu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <a href="{{ route('admin.dashboard', absolute: false) }}" class="admin-brand flex h-9 min-w-[300px] items-center rounded-full bg-[#3f70c9] px-5 text-2xl font-bold tracking-wide shadow-inner">
            TNT SOL
        </a>

        <form class="admin-search mx-auto hidden w-full max-w-[470px] items-center overflow-hidden rounded-full bg-white text-neutral-700 shadow-sm md:flex">
            <input class="min-w-0 flex-1 border-0 px-4 py-2 text-sm outline-none" placeholder="Search By Admit No, Name, Father Name... Etc." type="search">
            <button class="flex h-11 w-14 items-center justify-center bg-[#3f70c9] text-xl text-white" type="submit" aria-label="Search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <div class="admin-topbar-icons ml-auto flex items-center gap-5 text-[18px]">
            <span title="Calculator"><i class="fa-solid fa-calculator"></i></span>
            <span title="Notifications"><i class="fa-regular fa-bell"></i></span>
            <span title="Messages"><i class="fa-regular fa-comment"></i></span>
            <span title="Calendar"><i class="fa-regular fa-calendar-days"></i></span>
            <span class="relative" title="Tasks">
                <i class="fa-regular fa-square-check"></i>
                <span class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-orange-500 text-xs font-bold">1</span>
            </span>
            <span title="Birthdays"><i class="fa-solid fa-cake-candles"></i></span>
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-sm text-slate-500">
                <i class="fa-regular fa-user"></i>
            </span>
        </div>
    </div>
</header>
