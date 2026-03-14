@extends('template.dashboard')

@section('content')
    <div class="p-6 space-y-6">

        {{-- ================= SUMMARY CARDS ================= --}}
        @php

            function percentStyle($value)
            {
                return [
                    'color' => $value > 0 ? 'text-green-500' : ($value < 0 ? 'text-red-500' : 'text-gray-400'),
                    'icon' => $value > 0 ? 'trending-up' : ($value < 0 ? 'trending-down' : 'minus'),
                    'prefix' => $value > 0 ? '+' : '',
                ];
            }

            $sales = percentStyle($percentageSales ?? 0);
            $profit = percentStyle($percentageNetProfit ?? 0);
            $cash = percentStyle($percentageCashBalance ?? 0);
            $stock = percentStyle($stockToday ?? 0);

        @endphp


        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- TOTAL SALES --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 flex justify-between items-start">

                <div class="flex gap-3">

                    <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase">Total Sales</p>
                        <h2 class="text-xl font-semibold mt-1">
                            IDR {{ number_format($totalSales) }}
                        </h2>
                    </div>

                </div>

                <span class="{{ $sales['color'] }} text-sm font-medium flex items-center gap-1">
                    <i data-lucide="{{ $sales['icon'] }}" class="w-4 h-4"></i>
                    {{ $sales['prefix'] }}{{ number_format($percentageSales, 2) }}%
                </span>

            </div>


            {{-- NET PROFIT --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 flex justify-between items-start">

                <div class="flex gap-3">

                    <div class="bg-green-100 text-green-600 p-3 rounded-xl">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase">Net Profit</p>
                        <h2 class="text-xl font-semibold mt-1">
                            IDR {{ number_format($netProfit) }}
                        </h2>
                    </div>

                </div>

                <span class="{{ $profit['color'] }} text-sm font-medium flex items-center gap-1">
                    <i data-lucide="{{ $profit['icon'] }}" class="w-4 h-4"></i>
                    {{ $profit['prefix'] }}{{ number_format($percentageNetProfit, 2) }}%
                </span>

            </div>


            {{-- CASH BALANCE --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 flex justify-between items-start">

                <div class="flex gap-3">

                    <div class="bg-orange-100 text-orange-600 p-3 rounded-xl">
                        <i data-lucide="piggy-bank" class="w-5 h-5"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase">Cash Balance</p>
                        <h2 class="text-xl font-semibold mt-1">
                            IDR {{ number_format($cashBalance) }}
                        </h2>
                    </div>

                </div>

                <span class="{{ $cash['color'] }} text-sm font-medium flex items-center gap-1">
                    <i data-lucide="{{ $cash['icon'] }}" class="w-4 h-4"></i>
                    {{ $cash['prefix'] }}{{ number_format($percentageCashBalance, 2) }}%
                </span>

            </div>


            {{-- TOTAL STOCK --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 flex justify-between items-start">

                <div class="flex gap-3">

                    <div class="bg-purple-100 text-purple-600 p-3 rounded-xl">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase">Total Stock</p>
                        <h2 class="text-xl font-semibold mt-1">
                            {{ $totalStock }} Units
                        </h2>
                    </div>

                </div>

                <span class="{{ $stock['color'] }} text-sm font-medium flex items-center gap-1">
                    <i data-lucide="{{ $stock['icon'] }}" class="w-4 h-4"></i>
                    {{ $stock['prefix'] }}{{ $totalStockToday }}
                </span>

            </div>

        </div>





        {{-- ================= GRAPH + INVENTORY ================= --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- SALES GRAPH --}}
            <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm p-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-700">Sales Performance</h3>
                    <span class="text-sm text-gray-500">This Month</span>
                </div>

                <canvas id="salesChart" height="120"></canvas>

            </div>


            {{-- INVENTORY STATUS --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">

                <h3 class="font-semibold text-gray-700 mb-5">
                    Inventory Status
                </h3>


                {{-- NEW IPHONE --}}
                <div class="flex items-center justify-between mb-4">

                    <div class="flex items-center gap-3">

                        <div
                            class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-semibold">
                            N
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                New iPhones
                            </p>
                            <p class="text-xs text-gray-400">
                                Sealed box
                            </p>
                        </div>

                    </div>

                    <span class="font-semibold text-gray-800">
                        {{ $newPhones }}
                    </span>

                </div>


                {{-- SECOND HAND --}}
                <div class="flex items-center justify-between mb-6">

                    <div class="flex items-center gap-3">

                        <div
                            class="w-9 h-9 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-semibold">
                            S
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                Second Hand
                            </p>
                            <p class="text-xs text-gray-400">
                                Tested & Verified
                            </p>
                        </div>

                    </div>

                    <span class="font-semibold text-gray-800">
                        {{ $secondPhones }}
                    </span>

                </div>


                {{-- SALES READY --}}
                <div class="bg-green-50 border border-green-100 rounded-xl p-4">

                    <div class="flex justify-between items-center mb-2">

                        <span class="text-xs font-semibold text-green-700 uppercase">
                            Sales Ready
                        </span>

                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-md font-medium">
                            ACTIVE
                        </span>

                    </div>

                    <div class="flex items-end gap-2 mb-2">

                        <span class="text-2xl font-bold text-green-700">
                            {{ $totalStock }}
                        </span>

                        <span class="text-sm text-gray-500 mb-1">
                            units
                        </span>

                    </div>

                    {{-- PROGRESS --}}
                    <div class="w-full bg-gray-200 rounded-full h-2">

                        @php
                            $totalInventory = $newPhones + $secondPhones;
                            $percentReady = $totalInventory ? ($totalStock / $totalInventory) * 100 : 0;
                        @endphp

                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $percentReady }}%">
                        </div>

                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        {{ number_format($percentReady, 0) }}% of total stock
                    </p>

                </div>


                {{-- VIEW INVENTORY --}}
                <a href="{{ route('items.index') }}"
                    class="mt-5 block text-center bg-gray-100 hover:bg-gray-200 transition rounded-lg py-2 text-sm font-medium text-gray-600">

                    View Full Inventory

                </a>

            </div>

        </div>


        {{-- ================= TABLE SALES INVOICE ================= --}}
        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex justify-between mb-4">
                <h3 class="font-semibold text-gray-700">Sales Invoice</h3>
            </div>

            <x-table :data="$sales_invoice_new" :labels="[
                'transaction_number' => 'No Invoice',
                'transaction_date' => 'Tanggal',
                'customer_name' => 'Customer',
                'status' => 'Status',
                'grand_total' => 'Total Transaksi',
                'summary_paid' => 'Pembayaran',
            ]"  />

        </div>

    </div>


    {{-- ================= CHART ================= --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('salesChart');

        new Chart(ctx, {

            type: 'line',

            data: {

                labels: {!! json_encode($salesLabels) !!},

                datasets: [{
                    label: 'Sales This Month',

                    data: {!! json_encode($salesData) !!},

                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.15)',
                    fill: true,
                    tension: 0.4,
                }]
            },

            options: {

                responsive: true,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {

                    y: {
                        beginAtZero: true
                    }

                }

            }

        });
    </script>
@endsection
