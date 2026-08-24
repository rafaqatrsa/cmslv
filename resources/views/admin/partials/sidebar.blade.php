@php
    $isAccountsSection = request()->is('admin/account*') || request()->routeIs('admin.account.*') || request()->is('cmsc/admin/account*');

    $mainSidebarItems = [
        ['label' => 'Dashboard', 'icon' => 'fa-solid fa-desktop', 'route' => 'admin.dashboard'],
        ['label' => 'Staff Recruitment', 'icon' => 'fa-solid fa-users', 'route' => 'admin.hrms.staff.index'],
        ['label' => 'Internal & External Commn', 'icon' => 'fa-regular fa-comments', 'route' => 'admin.adm.mail-sms.index'],
        ['label' => 'Customer Services Mgmt.', 'icon' => 'fa-solid fa-list-check', 'route' => 'admin.adm.complaints.index'],
        ['label' => 'Admission Process', 'icon' => 'fa-solid fa-user-plus', 'route' => 'admin.adm.student-registrations.index'],
        ['label' => 'Withdrawal Process', 'icon' => 'fa-solid fa-ban', 'route' => 'admin.adm.student-transfers.index'],
        ['label' => 'Attendance Mgmt.', 'icon' => 'fa-regular fa-calendar-check', 'route' => 'admin.adm.attendance.index'],
        ['label' => 'Syllabus Mgmt.', 'icon' => 'fa-regular fa-building', 'route' => 'admin.academics.syllabus.index'],
        ['label' => 'Effective Lesson Planning', 'icon' => 'fa-regular fa-calendar-check', 'route' => 'admin.academics.lessons.index'],
        ['label' => 'Timetable & staffing', 'icon' => 'fa-regular fa-clock', 'route' => 'admin.academics.timetables.index'],
        ['label' => 'Homework', 'icon' => 'fa-solid fa-flask', 'route' => 'admin.academics.homework.index'],
        ['label' => 'Paper Generate', 'icon' => 'fa-regular fa-copy', 'route' => 'admin.academics.paper-generate.index'],
        ['label' => 'Examination', 'icon' => 'fa-regular fa-file-lines', 'route' => 'admin.academics.exam-schedules.index'],
        ['label' => 'Test System', 'icon' => 'fa-regular fa-file', 'route' => 'admin.academics.test-schedules.index'],
        ['label' => 'Accounts & Finance', 'icon' => 'fa-solid fa-calculator', 'route' => 'admin.account.accounts.dashboard'],
    ];

    $accountMenus = [
        [
            'type' => 'link',
            'label' => 'Dashboard',
            'icon' => 'fa-solid fa-desktop',
            'route' => 'admin.account.accounts.dashboard',
            'active' => request()->routeIs('admin.account.accounts.dashboard') || request()->routeIs('admin.account.dashboard'),
        ],
        [
            'type' => 'treeview',
            'label' => 'Manual Support',
            'icon' => 'fa-solid fa-life-ring',
            'active' => request()->routeIs('admin.account.documents.*'),
            'children' => [
                ['label' => 'Add Documents', 'route' => 'admin.account.documents.index'],
                ['label' => 'Policy Manual', 'route' => 'admin.account.documents.index'],
                ['label' => 'Flow Charts', 'route' => 'admin.account.documents.index'],
                ['label' => 'Supportive Documents', 'route' => 'admin.account.documents.index'],
                ['label' => 'Registers', 'route' => 'admin.account.documents.index'],
                ['label' => 'Video Supports', 'route' => 'admin.account.documents.index'],
            ],
        ],
        [
            'type' => 'treeview',
            'label' => 'Chart Of Accounts',
            'icon' => 'fa-solid fa-list',
            'active' => request()->routeIs('admin.account.accounts.newaccounts*') || request()->routeIs('admin.account.accounts.accountshead*') || request()->routeIs('admin.account.accounts.index') || request()->routeIs('admin.account.fee-master.*') || request()->is('admin/account/feemaster*') || request()->is('cmsc/admin/account/feemaster*') || request()->routeIs('cmsc.admin.account.feemaster.*'),
            'children' => [
                ['label' => 'Add Accounts Type', 'route' => 'admin.account.accounts.newaccounts'],
                ['label' => 'Add New Accounts', 'route' => 'admin.account.accounts.accountshead'],
                ['label' => 'Chart Of Accounts', 'route' => 'admin.account.accounts.index'],
                ['label' => 'Fee Structure', 'route' => 'admin.account.fee-master.index'],
            ],
        ],
        [
            'type' => 'treeview',
            'label' => 'Fee Voucher',
            'icon' => 'fa-regular fa-newspaper',
            'active' => request()->routeIs('admin.account.student-fees.*') || request()->routeIs('admin.account.studentfee.*') || request()->is('admin/account/studentfee*') || request()->is('admin/account/student-fees*'),
            'children' => [
                ['label' => 'Fee Revise', 'route' => 'admin.account.studentfee.feerevise'],
                ['label' => 'Assign Dues', 'route' => 'admin.account.studentfee.assigndues'],
                ['label' => 'Assign Fee Voucher', 'route' => 'admin.account.studentfee.assignfeevoucher'],
                ['label' => 'Assign Fee Voucher Date Wise', 'route' => 'admin.account.studentfee.assignfeevoucherdatewise'],
                ['label' => 'Fee Voucher Student Sibling', 'route' => 'admin.account.studentfee.feevoucherstudentsibling'],
                ['label' => 'Fee Voucher', 'route' => 'admin.account.studentfee.feevoucher'],
                ['label' => 'Custom Fee Voucher', 'route' => 'admin.account.studentfee.customfeevoucher'],
            ],
        ],
        [
            'type' => 'treeview',
            'label' => 'Accounting Records',
            'icon' => 'fa-solid fa-money-bill-wave',
            'active' => request()->routeIs('admin.account.expenses.*') || request()->routeIs('admin.account.payments.*') || request()->routeIs('admin.account.receipts.*') || request()->routeIs('admin.account.contra.*') || request()->routeIs('admin.account.journal-vouchers.*'),
            'children' => [
                ['label' => 'Expense Bill', 'route' => 'admin.account.expenses.index'],
                ['label' => 'Payment Voucher', 'route' => 'admin.account.payments.index'],
                ['label' => 'Receipt Voucher', 'route' => 'admin.account.receipts.index'],
                ['label' => 'Contra Voucher', 'route' => 'admin.account.contra.index'],
                ['label' => 'JV (Journal Voucher)', 'route' => 'admin.account.journal-vouchers.index'],
                ['label' => 'Fee Collect', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Cash Book', 'route' => 'admin.account.accounts.index'],
            ],
        ],
        [
            'type' => 'treeview',
            'label' => 'Payroll/Advance/Clearance',
            'icon' => 'fa-solid fa-indent',
            'active' => request()->routeIs('admin.account.payroll.*'),
            'children' => [
                ['label' => 'Payroll', 'route' => 'admin.account.payroll.index'],
            ],
        ],
        [
            'type' => 'treeview',
            'label' => 'Inventory Process',
            'icon' => 'fa-solid fa-cart-shopping',
            'active' => request()->routeIs('admin.account.item-categories.*') || request()->routeIs('admin.account.units.*') || request()->routeIs('admin.account.brands.*') || request()->routeIs('admin.account.products.*') || request()->routeIs('admin.account.stock.*') || request()->routeIs('admin.account.suppliers.*') || request()->routeIs('admin.account.class-book-sets.*') || request()->routeIs('admin.account.invoice-book-sets.*') || request()->routeIs('admin.account.invoice-book-set-returns.*') || request()->routeIs('admin.account.purchases.*') || request()->routeIs('admin.account.purchase-returns.*') || request()->routeIs('admin.account.sales.*') || request()->routeIs('admin.account.sales-returns.*'),
            'children' => [
                ['label' => 'Item Category', 'route' => 'admin.account.item-categories.index'],
                ['label' => 'Units', 'route' => 'admin.account.units.index'],
                ['label' => 'Brands', 'route' => 'admin.account.brands.index'],
                ['label' => 'Products Services', 'route' => 'admin.account.products.index'],
                ['label' => 'Stock', 'route' => 'admin.account.stock.index'],
                ['label' => 'Supplier', 'route' => 'admin.account.suppliers.index'],
                ['label' => 'Classes Book Sets', 'route' => 'admin.account.class-book-sets.index'],
                ['label' => 'Invoice Book Sets', 'route' => 'admin.account.invoice-book-sets.index'],
                ['label' => 'Invoice Book Sets Return', 'route' => 'admin.account.invoice-book-set-returns.index'],
                ['label' => 'Purchases', 'route' => 'admin.account.purchases.index'],
                ['label' => 'Purchase Return', 'route' => 'admin.account.purchase-returns.index'],
                ['label' => 'Sale Invoice', 'route' => 'admin.account.sales.index'],
                ['label' => 'Sales Return', 'route' => 'admin.account.sales-returns.index'],
            ],
        ],
        [
            'type' => 'treeview',
            'label' => 'Network Associate Account',
            'icon' => 'fa-solid fa-sitemap',
            'active' => request()->routeIs('admin.account.royalty.*'),
            'children' => [
                ['label' => 'Assign Royalty Voucher', 'route' => 'admin.account.royalty.index'],
                ['label' => 'Collect Royalty', 'route' => 'admin.account.royalty.index'],
            ],
        ],
        [
            'type' => 'treeview',
            'label' => 'Reports & Reviews',
            'icon' => 'fa-solid fa-chart-column',
            'active' => false,
            'children' => [
                ['label' => 'General Report', 'route' => 'admin.account.accounts.index'],
                ['label' => 'Incomes / Fee Report', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Expenses Report', 'route' => 'admin.account.expenses.index'],
                ['label' => 'Payroll Report', 'route' => 'admin.account.payroll.index'],
                ['label' => 'Inventory Reports', 'route' => 'admin.account.purchases.index'],
            ],
        ],
    ];
@endphp

<aside class="admin-sidebar fixed inset-y-0 left-0 z-30 hidden w-[296px] overflow-hidden bg-[#24448d] text-white shadow-xl lg:block">
    {{-- Header --}}
    <div class="admin-sidebar-header flex h-16 items-center gap-2 border-b border-white/10 bg-[#24448d] px-2">
        <div class="admin-avatar flex h-11 w-11 items-center justify-center rounded-full border border-white/50 bg-white/90 text-2xl text-slate-500">
            <i class="fa-regular fa-user"></i>
        </div>
        <a href="{{ route('admin.dashboard', absolute: false) }}" class="min-w-0 text-lg font-semibold text-white no-underline">
            <i class="fa-regular fa-hand-point-right"></i>
            Super Admin
        </a>
    </div>

    {{-- Session & Quick Bar --}}
    <div class="border-b border-black/20 bg-[#254693] px-4 py-2 shadow-inner">
        <p class="text-base font-semibold">Current Session: 2026-27</p>
        <div class="mt-2 flex items-center justify-between text-base">
            <span>Quick Links</span>
            <span class="text-lg"><i class="fa-solid fa-grip"></i></span>
        </div>
    </div>

    {{-- Sidebar Navigation Links --}}
    <nav class="h-[calc(100vh-136px)] overflow-y-auto pb-6 pt-2 [scrollbar-width:thin]" id="adminSidebarNav">
        @if ($isAccountsSection)
            {{-- ACCOUNTS MODULE SIDEBAR (CMSC Style) --}}
            <div class="px-3 py-2 text-xs font-bold uppercase tracking-wider text-blue-200/80 flex items-center justify-between">
                <span>Accounts & Finance</span>
                <a href="{{ route('admin.dashboard', absolute: false) }}" class="text-xs text-white/80 hover:text-white underline normal-case">
                    <i class="fa fa-arrow-left"></i> Main Menu
                </a>
            </div>

            @foreach ($accountMenus as $index => $menu)
                @if ($menu['type'] === 'link')
                    <a
                        href="{{ route($menu['route'], absolute: false) }}"
                        class="admin-sidebar-link flex items-center gap-3 px-3 py-3 text-[16px] font-semibold transition hover:bg-white/10 {{ $menu['active'] ? 'is-active bg-[#1d3a7d] text-amber-300' : '' }}"
                    >
                        <span class="w-6 text-center text-lg"><i class="{{ $menu['icon'] }}"></i></span>
                        <span class="min-w-0 flex-1 truncate">{{ $menu['label'] }}</span>
                        <span class="text-base leading-none text-white/70"><i class="fa-solid fa-angles-right"></i></span>
                    </a>
                @elseif ($menu['type'] === 'treeview')
                    <div class="sidebar-treeview-group">
                        <button
                            type="button"
                            onclick="toggleSidebarSubmenu('acc_menu_{{ $index }}', this)"
                            class="admin-sidebar-link flex w-full items-center gap-3 px-3 py-3 text-left text-[16px] font-semibold transition hover:bg-white/10 focus:outline-none {{ $menu['active'] ? 'bg-[#1d3a7d]' : '' }}"
                        >
                            <span class="w-6 text-center text-lg"><i class="{{ $menu['icon'] }}"></i></span>
                            <span class="min-w-0 flex-1 truncate">{{ $menu['label'] }}</span>
                            <span class="text-sm leading-none transition-transform duration-200 tree-icon">
                                <i class="fa-solid {{ $menu['active'] ? 'fa-angle-down' : 'fa-angle-left' }}"></i>
                            </span>
                        </button>
                        <div
                            id="acc_menu_{{ $index }}"
                            class="sidebar-submenu bg-[#1a3369] py-1 {{ $menu['active'] ? '' : 'hidden' }}"
                        >
                            @foreach ($menu['children'] as $child)
                                <a
                                    href="{{ route($child['route'], absolute: false) }}"
                                    class="flex items-center gap-2 py-2 pl-10 pr-3 text-[14px] font-medium text-blue-100 transition hover:bg-white/10 hover:text-white {{ request()->routeIs($child['route']) ? 'text-amber-300 font-bold bg-[#142852]' : '' }}"
                                >
                                    <i class="fa-solid fa-angles-right text-[10px] text-blue-300"></i>
                                    <span class="truncate">{{ $child['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            {{-- MAIN SYSTEM SIDEBAR --}}
            @foreach ($mainSidebarItems as $item)
                <a
                    href="{{ route($item['route'], absolute: false) }}"
                    class="admin-sidebar-link flex items-center gap-3 px-3 py-3 text-[18px] font-semibold transition hover:bg-white/10 {{ request()->routeIs($item['route']) ? 'is-active bg-[#1d3a7d]' : '' }}"
                >
                    <span class="w-6 text-center text-lg"><i class="{{ $item['icon'] }}"></i></span>
                    <span class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                    @unless ($loop->first)
                        <span class="text-2xl leading-none"><i class="fa-solid fa-angle-right"></i></span>
                    @endunless
                </a>
            @endforeach
        @endif
    </nav>
</aside>

<script>
    function toggleSidebarSubmenu(menuId, button) {
        var menu = document.getElementById(menuId);
        if (!menu) return;
        var isHidden = menu.classList.contains('hidden');
        var icon = button.querySelector('.tree-icon i');

        if (isHidden) {
            menu.classList.remove('hidden');
            if (icon) {
                icon.className = 'fa-solid fa-angle-down';
            }
        } else {
            menu.classList.add('hidden');
            if (icon) {
                icon.className = 'fa-solid fa-angle-left';
            }
        }
    }
</script>
