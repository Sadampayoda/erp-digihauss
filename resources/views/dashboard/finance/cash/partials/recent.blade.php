<div class="bg-white rounded-2xl shadow p-5 flex flex-col gap-5">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <p class="font-semibold text-lg text-slate-700">
            Chart of Account Kas
        </p>
    </div>

    {{-- LIST --}}
    <div class="flex flex-col gap-4">

        @foreach ($recent_assets as $coa)
            @php


                if ($coa->balance > 0) {
                    $color = 'text-blue-500';
                    $iconBg = 'bg-blue-100';
                    $iconColor = 'text-blue-500';
                } elseif ($coa->balance < 0) {
                    $color = 'text-red-500';
                    $iconBg = 'bg-red-100';
                    $iconColor = 'text-red-500';
                } else {
                    $color = 'text-gray-400';
                    $iconBg = 'bg-gray-100';
                    $iconColor = 'text-gray-400';
                }

            @endphp

            <div class="flex items-center justify-between">

                <div class="flex items-center gap-3">

                    {{-- ICON --}}
                    <div class="w-10 h-10 rounded-full {{ $iconBg }} flex items-center justify-center">
                        <i data-lucide="wallet" class="w-5 h-5 {{ $iconColor }}"></i>
                    </div>

                    {{-- INFO --}}
                    <div>
                        <p class="font-medium text-slate-700 text-sm">
                            {{ $coa->name }}
                        </p>

                        <p class="text-xs text-slate-400">
                            {{ \Carbon\Carbon::parse($coa->last_transaction)->format('d M Y') }}
                        </p>
                    </div>

                </div>

                {{-- AMOUNT --}}
                <div class="text-right">

                    <p class="font-semibold text-sm {{ $color }}">
                        Rp {{ number_format($coa->balance, 0, ',', '.') }}
                    </p>

                </div>

            </div>
        @endforeach

    </div>

</div>
