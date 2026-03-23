<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-3 sm:px-5 mt-1">

    {{-- TOTAL SALES --}}
    <div class="flex items-center gap-4 bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-100">
            <i data-lucide="shopping-cart" class="w-5 h-5 text-blue-500"></i>
        </div>
        <div>
            <p class="text-xs text-slate-400 uppercase">Total Sales</p>
            <p class="text-lg font-semibold">
                Rp {{ number_format($closing->total_sales ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- TOTAL HPP --}}
    <div class="flex items-center gap-4 bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100">
            <i data-lucide="receipt" class="w-5 h-5 text-slate-500"></i>
        </div>
        <div>
            <p class="text-xs text-slate-400 uppercase">Total HPP</p>
            <p class="text-lg font-semibold">
                Rp {{ number_format($closing->total_hpp ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- NET PROFIT --}}
    <div class="flex items-center gap-4 bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-emerald-100">
            <i data-lucide="trending-up" class="w-5 h-5 text-emerald-500"></i>
        </div>
        <div>
            <p class="text-xs text-slate-400 uppercase">Net Profit</p>
            <p class="text-lg font-semibold">
                Rp {{ number_format($closing->total_profit ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>

</div>
