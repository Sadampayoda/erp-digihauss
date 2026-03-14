@props([
    'labels',
    'data' => [],
    'onEdit' => null,
    'onDelete' => null,
    'checkbox' => false,
    'titleEdit' => 'Edit',
    'onPaymentProof' => null,
    'onSearch' => null,
    'onSearchParams' => [],
    'onPrefix' => null,
    'onStatus' => 'transaction',
])
<div class="overflow-x-auto w-screen lg:w-full h-120">
    @if ($onSearch)
        <x-input-select name="search" label="Cari..." :required="true" :route="$onSearch" class="rounded-sm" />
    @endif
    <table class="w-full text-sm text-left" id="table-data">
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
                                $status = transactionStatusBadge($item->$key, $onStatus);
                            @endphp
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $status['class'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </td>
                        @elseif ($key === 'ready')
                            <td class="px-4 py-3">
                                @if ($item->$key)
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Ready
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        Tidak Ready
                                    </span>
                                @endif
                            </td>
                        @else
                            <td class="px-4 py-3">
                                {{ is_numeric($item->$key) ? rupiah($item->$key) : $item->$key }}
                            </td>
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
                                    <button
                                        onclick="{{ $onEdit }}({{ $item->id ?? 0 }}, {{ json_encode($item) }})"
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
    </table>
</div>
<div class="flex flex-row justify-end gap-3">
    <a class="py-3 px-4 border border-slate-400 hover:border-slate-700 rounded-lg cursor-pointer">
        <p class="text-slate-500 hover:text-slate-800">
            < </p>
    </a>
    <a class="py-3 px-4 border border-slate-400 hover:border-slate-700 rounded-lg cursor-pointer">
        <p class="text-slate-500 hover:text-slate-800">1</p>
    </a>
    <a class="py-3 px-4 border border-slate-700 hover:border-slate-700 rounded-lg cursor-pointer">
        <p class="text-slate-800 hover:text-slate-800">2</p>
    </a>
    <a class="py-3 px-4 border border-slate-400 hover:border-slate-700 rounded-lg cursor-pointer">
        <p class="text-slate-500 hover:text-slate-800">3</p>
    </a>
    <a class="py-3 px-4 border border-slate-400 hover:border-slate-700 rounded-lg cursor-pointer">
        <p class="text-slate-500 hover:text-slate-800">></p>
    </a>
</div>

<x-modal onSubmit="submitPaymentProod" id="payment-proof-modal" title="Upload Bukti Pembayaran">

    <form id="payment-proof-form" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
        @csrf

        <input type="hidden" name="module" id="payment-module">
        <input type="hidden" name="transaction_id" id="payment-transaction-id">

        {{-- Upload Area --}}
        <div id="upload-area"
            class="border-2 border-dashed border-orange-300 rounded-xl
        p-10 flex flex-col items-center justify-center text-center
        bg-orange-50 hover:bg-orange-100 cursor-pointer transition">

            <i data-lucide="upload-cloud" class="w-10 h-10 text-orange-500 mb-3"></i>

            <p class="font-medium text-slate-700">
                Klik atau seret file ke sini
            </p>

            <p class="text-sm text-slate-500 mt-1">
                Mendukung format JPG, PNG atau PDF (Maks 5MB)
            </p>

            <button type="button"
                class="mt-4 bg-orange-500 hover:bg-orange-600
            text-white px-5 py-2 rounded-lg">
                Pilih File
            </button>

            <input type="file" id="payment-files" name="image[]" multiple class="hidden"
                accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <div>
            <p class="font-semibold text-slate-700 mb-2">
                Daftar Lampiran
            </p>

            <div id="file-list" class="flex flex-col gap-3">
            </div>
        </div>

    </form>

</x-modal>

<script>
    function toggleMenuAction(event, button) {
        event.stopPropagation();

        const menu = button.parentElement.querySelector('.action-menu');
        document.querySelectorAll('.action-menu').forEach(el => {
            if (el !== menu) el.classList.add('hidden');
        });

        menu.classList.toggle('hidden');

        lucide.createIcons();
    }

    document.addEventListener('click', function() {
        document.querySelectorAll('.action-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    });

    const modalTable = document.getElementById('payment-proof-modal')
    document.querySelectorAll('.btn-open-payment-proof-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            modalTable.classList.remove('hidden')
            modalTable.classList.add('flex')
        })
    })

    const onSearch = @json($onSearch);
    const onPrefix = @json($onPrefix);
    const labels = @json($labels);
    const checkbox = @json($checkbox);
    const onSearchEdit = @json($onEdit);
    const onSearchDelete = @json($onDelete);
    const onSearchParams = @json($onSearchParams);
    document.getElementById('search').addEventListener('change', function() {
        if (!onSearch) {
            return;
        }

        const search = this.value ?? ''
        $.ajax({
            url: "{{ route('table.index') }}",
            data: {
                search: search,
                prefix: onPrefix,
                labels: labels,
                checkbox: checkbox,
                edit: onSearchEdit,
                delete: onSearchDelete,
                searchParams: onSearchParams
            },
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                $('#table-data').html(res);
            },
            error: function(err) {
                console.log(err);
            }
        });

    })
</script>
