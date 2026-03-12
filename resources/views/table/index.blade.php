<thead class="bg-slate-100 text-slate-600 uppercase text-xs">
    <tr>
        @if ($checkbox)
            <th class="px-4 py-3">
                Checkbox
            </th>
        @else
            <th class="px-4 py-3">No</th>
        @endif
        @foreach ($labels as $key => $value)
            <th class="px-4 py-3">{{ $value }}</th>
        @endforeach
        @if ($onEdit || $onDelete)
            <th class="px-4 py-3 text-center">Action</th>
        @endif
    </tr>
</thead>


<tbody class="divide-y">
    @foreach ($data as $item)
        <tr class="hover:bg-slate-50 transition">
            @if ($checkbox)
                <td class="flex px-4 py-3 justify-center items-center mt-1">
                    <input type="checkbox" name="selected[]" value="{{ $item->id }}" class="rowCheckbox">
                </td>
            @else
                <td class="px-4 py-3">{{ $loop->iteration }}</td>
            @endif
            @foreach ($labels as $key => $value)
                @if ($key === 'status')
                    @php
                        $status = transactionStatusBadge($item->$key);
                    @endphp
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $status['class'] }}">
                            {{ $status['label'] }}
                        </span>
                    </td>
                @elseif ($key === 'ready')
                    <td class="px-4 py-3">
                        @if ($item->$key)
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Ready
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                Tidak Ready
                            </span>
                        @endif
                    </td>
                @else
                    <td class="px-4 py-3">{{ $item->$key }}</td>
                @endif
            @endforeach
            @if ($onEdit || $onDelete)
                <td class="px-4 py-3 text-center relative">
                    <button onclick="toggleMenuAction(event, this)" class="p-2 rounded-full hover:bg-slate-200">
                        <i data-lucide="more-vertical" class="w-5 h-5"></i>
                    </button>
                    <div
                        class="action-menu hidden absolute right-0 mt-2 w-44
                                bg-white rounded-xl shadow-lg border z-50 border-slate-200">
                        @if ($onEdit)
                        <button onclick="{{ $onEdit }}({{ $item->id ?? 0 }}, {{ json_encode($item) }})"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm hover:bg-slate-100 cursor-pointer">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                {{ $titleEdit }}
                            </button>
                        @endif


                        @if ($onDelete)
                            <button onclick="{{ $onDelete }}({{ $item->id }})"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm
                                        text-red-600 hover:bg-red-50">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                Delete
                            </button>
                        @endif
                        @if ($onPaymentProof)
                            <button class="btn-open-payment-proof-modal" data-id="{{ $item->id }}"
                                class="flex flex-col items-center text-slate-700 gap-2 px-4 py-2 text-sm hover:bg-slate-100">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                Bukti Pembayaran
                            </button>
                        @endif
                    </div>
                </td>
            @endif

        </tr>
    @endforeach
</tbody>
