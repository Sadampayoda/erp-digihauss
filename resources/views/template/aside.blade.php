<aside id="sidebar"
    class="
    fixed lg:static inset-y-0 left-0 z-40
    w-75 bg-white px-4 py-6
    shadow-[4px_0_10px_-2px_rgba(0,0,0,0.1)]
    transform -translate-x-full lg:translate-x-0
    transition-transform
">
    <div class="h-15 mb-5 flex justify-between items-center">
        <x-image-digihaus />
        <button onclick="toggleSidebar()" class="lg:hidden p-1">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>
    <p class="text-slate-400">Main Menu</p>
    <div class="flex flex-col py-5 px-2 gap-2 h-full overflow-y-auto min-h-0 space-y-1 custom-scroll">

        <a href="{{ url('/dashboard') }}"
            class="flex items-center gap-3 p-4 rounded-xl text-md cursor-pointer
    {{ Request::is('dashboard*') ? 'bg-stone-200 text-slate-800' : 'text-slate-400' }}
    hover:bg-stone-200 transition-all">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span>Dashboard</span>
        </a>

        <div>
            <button onclick="toggleMenu('produk')"
                class="flex w-full items-center justify-between p-4 rounded-xl text-md text-slate-400 hover:bg-stone-200 transition">
                <div class="flex items-center gap-3">
                    <i data-lucide="boxes" class="w-5 h-5"></i>
                    <span>Katalog Produk</span>
                </div>
                <i data-lucide="chevron-down" id="icon-produk" class="w-4 h-4 transition-transform"></i>
            </button>

            <div id="menu-produk" class="ml-10 mt-1 space-y-1 overflow-hidden max-h-0 transition-all duration-300">
                <a href="{{ route('items.index') }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg {{ Request::is('items*') && !Request::is('items/stock') ? 'bg-stone-200 text-slate-800' : 'text-slate-400' }} hover:bg-stone-200">
                    <i data-lucide="package" class="w-4 h-4"></i>
                    <span>Barang</span>
                </a>
                <a href="{{ route('items.stock') }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg {{ Request::is('items/stock') ? 'bg-stone-200 text-slate-800' : 'text-slate-400' }} hover:bg-stone-200">
                    <i data-lucide="boxes" class="w-4 h-4"></i>
                    <span>Stok Barang</span>
                </a>
                <a href="/produk/stok"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <span>Kondisi Barang</span>
                </a>
            </div>
        </div>

        <div>
            <button onclick="toggleMenu('sales')"
                class="flex w-full items-center justify-between p-4 rounded-xl text-md text-slate-400 hover:bg-stone-200 transition">
                <div class="flex items-center gap-3">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span>Penjualan</span>
                </div>
                <i data-lucide="chevron-down" id="icon-sales" class="w-4 h-4 transition-transform"></i>
            </button>

            <div id="menu-sales" class="ml-10 mt-1 space-y-1 overflow-hidden max-h-0 transition-all duration-300">
                <a href="{{ route('advance-sales.index') }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg
                    {{ Request::is('advance-sales*') ? 'bg-stone-200 text-slate-800' : 'text-slate-400' }} hover:bg-stone-200">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                    <span>Uang Muka Penjualan</span>
                </a>
                <a href="{{ route('sales-invoices.index') }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg {{ Request::is('sales-invoices*') ? 'bg-stone-200 text-slate-800' : 'text-slate-400' }} hover:bg-stone-200">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span>Sales Invoice</span>
                </a>
                <a href="{{ route('sales-returns.index') }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg {{ Request::is('sales-returns*') ? 'bg-stone-200 text-slate-800' : 'text-slate-400' }} hover:bg-stone-200">
                    <i data-lucide="undo-2" class="w-4 h-4"></i>
                    <span>Sales Return</span>
                </a>
                <a href="{{ route('contacts.index') }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg {{ Request::is('contacts*') ? 'bg-stone-200 text-slate-800' : 'text-slate-400' }} hover:bg-stone-200">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    <span>Manage Customer</span>
                </a>
                <a href="{{ route('journals.index',['menu' => 'sales']) }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg {{ Request::is('journals*') ? 'bg-stone-200 text-slate-800' : 'text-slate-400' }} hover:bg-stone-200">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                    <span>Journal Sales</span>
                </a>
                <a href="{{ route('setting-coas.index') }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg {{ Request::is('setting-coas*') ? 'bg-stone-200 text-slate-800' : 'text-slate-400' }} hover:bg-stone-200">
                    <i data-lucide="settings-2" class="w-4 h-4"></i>
                    <span>Pengaturan COA Sales</span>
                </a>
            </div>
        </div>

        <div>
            <button onclick="toggleMenu('purchasing')"
                class="flex w-full items-center justify-between p-4 rounded-xl text-md text-slate-400 hover:bg-stone-200 transition">
                <div class="flex items-center gap-3">
                    <i data-lucide="truck" class="w-5 h-5"></i>
                    <span>Pembelian</span>
                </div>
                <i data-lucide="chevron-down" id="icon-purchasing" class="w-4 h-4 transition-transform"></i>
            </button>

            <div id="menu-purchasing" class="ml-10 mt-1 space-y-1 overflow-hidden max-h-0 transition-all duration-300">
                <a href="/purchasing/down-payment"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                    <span>Uang Muka Pembelian</span>
                </a>
                <a href="/purchasing/invoice"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                    <span>Receipt Invoice</span>
                </a>
                <a href="/purchasing/vendor"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                    <span>Manage Vendor</span>
                </a>
            </div>
        </div>

        <div>
            <button onclick="toggleMenu('finance')"
                class="flex w-full items-center justify-between p-4 rounded-xl text-md text-slate-400 hover:bg-stone-200 transition">
                <div class="flex items-center gap-3">
                    <i data-lucide="banknote" class="w-5 h-5"></i>
                    <span>Keuangan</span>
                </div>
                <i data-lucide="chevron-down" id="icon-finance" class="w-4 h-4 transition-transform"></i>
            </button>

            <div id="menu-finance" class="ml-10 mt-1 space-y-1 overflow-hidden max-h-0 transition-all duration-300">
                <a href="/finance/cash-in"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="arrow-down-circle" class="w-4 h-4"></i>
                    <span>Kas Masuk</span>
                </a>
                <a href="/finance/cash-out"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="arrow-up-circle" class="w-4 h-4"></i>
                    <span>Kas Keluar</span>
                </a>
                <a href="/finance/bank-in"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                    <span>Bank Masuk</span>
                </a>
                <a href="/finance/bank-out"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                    <span>Bank Keluar</span>
                </a>
            </div>
        </div>

        <a href="/administrasi"
            class="flex items-center gap-3 p-4 rounded-xl text-md text-slate-400 hover:bg-stone-200 transition">
            <i data-lucide="settings" class="w-5 h-5"></i>
            <span>Administrasi Umum</span>
        </a>

        <a href="/tukar-tambah"
            class="flex items-center gap-3 p-4 rounded-xl text-md text-slate-400 hover:bg-stone-200 transition">
            <i data-lucide="repeat" class="w-5 h-5"></i>
            <span>Tukar Tambah</span>
        </a>

        <div>
            <button onclick="toggleMenu('master')"
                class="flex w-full items-center justify-between p-4 rounded-xl text-md text-slate-400 hover:bg-stone-200 transition">
                <div class="flex items-center gap-3">
                    <i data-lucide="database" class="w-5 h-5"></i>
                    <span>Master</span>
                </div>
                <i data-lucide="chevron-down" id="icon-master" class="w-4 h-4 transition-transform"></i>
            </button>

            <div id="menu-master" class="ml-10 mt-1 space-y-1 overflow-hidden max-h-0 transition-all duration-300">
                <a href="{{ route('brands.index') }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="badge" class="w-4 h-4"></i>
                    <span>Brand</span>
                </a>

                <a href="{{ route('series.index') }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    <span>Series</span>
                </a>

                <a href="{{ route('payment-methods.index') }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                    <span>Metode Bayar</span>
                </a>

                <a href="{{ route('coas.index') }}"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    <span>COA</span>
                </a>

                <a href="/master/type"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    <span>User</span>
                </a>
            </div>
        </div>

        <div class="mb-30">
            <button onclick="toggleMenu('report')"
                class="flex w-full items-center justify-between p-4 rounded-xl text-md text-slate-400 hover:bg-stone-200 transition">
                <div class="flex items-center gap-3">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    <span>Laporan</span>
                </div>
                <i data-lucide="chevron-down" id="icon-report" class="w-4 h-4 transition-transform"></i>
            </button>

            <div id="menu-report" class="ml-10 mt-1 space-y-1 overflow-hidden max-h-0 transition-all duration-300">
                <a href="/report/journal"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span>Journal</span>
                </a>

                <a href="/report/cash-flow"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                    <span>Arus Kas</span>
                </a>

                <a href="/report/balance-sheet"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="scale" class="w-4 h-4"></i>
                    <span>Neraca</span>
                </a>

                <a href="/report/stock-card"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    <span>Kartu Stok</span>
                </a>

                <a href="/report/gross-profit"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="percent" class="w-4 h-4"></i>
                    <span>Laba Kotor</span>
                </a>

                <a href="/report/profit-loss"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                    <span>Laba Rugi</span>
                </a>

                <a href="/report/payable"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="file-minus" class="w-4 h-4"></i>
                    <span>Laporan Hutang</span>
                </a>

                <a href="/report/receivable"
                    class="flex items-center gap-2 p-2 text-sm rounded-lg text-slate-400 hover:bg-stone-200">
                    <i data-lucide="file-plus" class="w-4 h-4"></i>
                    <span>Laporan Piutang</span>
                </a>
            </div>
        </div>

    </div>

</aside>

<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="toggleSidebar()">
</div>
