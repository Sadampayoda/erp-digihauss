 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-3 sm:px-5 mt-1">

        {{-- CASH EXPECTED --}}
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-sm text-slate-400">Cash Expected</p>
            <p class="text-2xl font-semibold mt-1">
                Rp {{ number_format($closing->cash_expected ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-emerald-500 mt-1">
                ↗ +0.0% vs target
            </p>
        </div>

        {{-- CASH ACTUAL --}}
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-sm text-slate-400">Cash Actual</p>
            <p class="text-2xl font-semibold mt-1">
                Rp {{ number_format($closing->cash_actual ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-red-500 mt-1">
                ↘ -0.12% discrepancy
            </p>
        </div>

        {{-- DIFFERENCE --}}
        <div class="bg-slate-900 text-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-slate-400">Difference</p>
            <p class="text-2xl font-semibold mt-1">
                Rp {{ number_format($closing->cash_difference ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-red-400 mt-1">
                ⚠ Action Required
            </p>
        </div>

    </div>
