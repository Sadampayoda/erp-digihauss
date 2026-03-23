<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    {{-- TOTAL STOCK EXPECTED --}}
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-slate-400">Total Stock Expected</p>
                <p class="text-xl font-semibold mt-1">
                    {{ number_format($closing->total_stock_expected ?? 0) }}
                    <span class="text-xs text-slate-400">units</span>
                </p>
            </div>
            <i data-lucide="archive" class="w-6 h-6 text-slate-300"></i>
        </div>

        {{-- PROGRESS --}}
        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-4">
            <div class="bg-blue-500 h-1.5 rounded-full" style="width: 100%"></div>
        </div>
    </div>

    {{-- TOTAL STOCK ACTUAL --}}
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-slate-400">Total Stock Actual</p>
                <p class="text-xl font-semibold mt-1">
                    {{ number_format($closing->total_stock_actual ?? 0) }}
                    <span class="text-xs text-slate-400">units</span>
                </p>
            </div>
            <i data-lucide="clipboard-check" class="w-6 h-6 text-slate-300"></i>
        </div>

        {{-- PROGRESS --}}
        @php
            $expected = $closing->total_stock_expected ?? 1;
            $actual = $closing->total_stock_actual ?? 0;
            $percent = $expected > 0 ? ($actual / $expected) * 100 : 0;
        @endphp

        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-4">
            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ min($percent, 100) }}%"></div>
        </div>
    </div>

    {{-- STOCK DIFFERENCE --}}
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs text-slate-400">Stock Difference</p>
                @php
                    $diff = $closing->total_stock_difference ?? 0;
                @endphp
                <p class="text-xl font-semibold mt-1 {{ $diff < 0 ? 'text-red-500' : 'text-emerald-500' }}">
                    {{ $diff }}
                    <span class="text-xs text-slate-400">units</span>
                </p>
            </div>
            <i data-lucide="file-warning" class="w-6 h-6 text-slate-300"></i>
        </div>

        {{-- PROGRESS --}}
        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-4">
            <div class="{{ $diff != 0 ? 'bg-red-500' : 'bg-emerald-500' }} h-1.5 rounded-full"
                style="width: {{ abs($diff) > 0 ? '20%' : '100%' }}"></div>
        </div>
    </div>

</div>
