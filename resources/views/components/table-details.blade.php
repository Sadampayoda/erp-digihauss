@props([
    'setupColumn' => [],
    'data' => [],
    'initialTable' => 'itemsTable',
])

<table class="table-fixed min-w-[1200px] text-sm">
    <thead class="bg-slate-50 text-slate-500 uppercase text-xs sticky top-0 z-10">
        <tr>
            @foreach ($setupColumn as $column)
                <th class="px-6 py-4 text-left">
                    {{ $column['label'] ?? '#' }}
                </th>
            @endforeach
        </tr>
    </thead>

    <tbody id="{{ $initialTable }}-body" class="divide-y">
        @forelse ($data as $value)
            <tr class="hover:bg-slate-50" data-id="{{ $value->id }}">

                @foreach ($setupColumn as $column => $details)
                    @php
                        $type = $details['type'] ?? 'text';
                        $isEdit = $details['edit'] ?? false;
                        $isDelete = $details['delete'] ?? false;
                        $color = $details['color_text'] ?? 'text-slate-800';
                    @endphp

                    {{-- ACTION COLUMN --}}
                    @if ($column === 'action')
                        <td class="px-6 py-4 text-center">
                            @if ($isDelete)
                                <button onclick="onDeleteDetail({{ $value->id }},{{ $value }})" type="button"
                                    class="text-red-500 hover:text-red-700 delete-row">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            @endif
                        </td>

                        {{-- IMAGE COLUMN --}}
                    @elseif ($type === 'image')
                        <td class="px-6 py-4">
                            @if (!empty($value->$column))
                                <img src="{{ asset('storage/' . $value->$column) }}"
                                    class="w-14 h-14 rounded-lg object-cover">
                            @endif
                        </td>

                        {{-- EDITABLE COLUMN --}}
                    @elseif ($isEdit)
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <p class="font-medium {{ $color }}">
                                    {{ $value->$column }}
                                </p>

                                <x-input-text :type="$type" :name="$column . '[]'"
                                    class="w-20 text-center border rounded-md p-1" :value="$value->$column ?? 0" />
                            </div>
                        </td>
                        {{-- NORMAL COLUMN --}}
                    @else
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="{{ $type }}" name="{{ $column }}[]"
                                value="{{ $value->$column }}" readonly
                                class="w-24 text-center border-none bg-transparent font-medium {{ $color }} focus:outline-none pointer-events-none" />
                        </td>
                    @endif
                @endforeach

            </tr>
        @empty
            <tr>
                <td colspan="{{ count($setupColumn) }}" class="text-center py-6 text-slate-400 empty-row">
                    Data tidak tersedia
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    {
        const onClickDelete = @json($setupColumn['action']['onClickDelete'] ?? null);
        const initialTable = @json($initialTable);
        window.renderDetailRow = function(item, setupColumn) {
            const tableBody = document.getElementById(`${initialTable}-body`);
            if (!tableBody) return;

            const emptyRow = tableBody.querySelector('.empty-row');
            if (emptyRow) emptyRow.remove();

            if (!document.querySelector(`tr[data-id="${item.id}"]`)) {

                let row = `<tr class="hover:bg-slate-50" data-id="${item.id}">`;

                Object.keys(setupColumn).forEach(column => {

                    let config = setupColumn[column];
                    let type = config.type ?? 'text';
                    let isEdit = config.edit ?? false;
                    let isDelete = config.delete ?? false;

                    // ACTION COLUMN
                    if (column === 'action') {

                        if (isDelete) {
                            row += `
                                <td class="px-6 py-4 text-center">
                                    <button
                                        type="button"
                                        onclick="onDeleteDetail(${item.id})"
                                        class="text-red-500 hover:text-red-700 delete-row cursor-pointer">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </td>
                            `;
                        }

                    }
                    // IMAGE COLUMN
                    else if (type === 'image') {

                        row += `
                    <td class="px-6 py-4">
                        <img src="/storage/${item[column] ?? ''}"
                            class="w-14 h-14 rounded-lg object-cover">
                    </td>
                `;
                    }
                    // EDITABLE COLUMN
                    else if (isEdit) {

                        row += `
                    <td class="px-6 py-4">
                        <input type="${type}"
                            name="${column}[]"
                            class="w-20 text-center border rounded-md p-1 ${column}"
                            value="${item[column] ?? 0}">
                    </td>
                `;
                    }
                    // NORMAL COLUMN
                    else {

                        row += `
                        <td class="px-6 py-4">
                            <input
                                type="${type}"
                                name="${column}[]"
                                value="${item[column] ?? ''}"
                                readonly
                                class="w-24 text-center bg-transparent border-none font-medium text-slate-800 focus:outline-none pointer-events-none ${column}">
                        </td>
                `;
                    }

                });

                row += `</tr>`;


                document
                    .getElementById(`${initialTable}-body`)
                    .insertAdjacentHTML('beforeend', row);
            }


            lucide.createIcons();
        };

        window.onDeleteDetail = (id) => {
            Swal.fire({
                title: "Yakin dihapus ?",
                showCancelButton: true,
                confirmButtonText: "Hapus",
            }).then((result) => {
                if (!result.isConfirmed) return;

                if (onClickDelete && typeof window[onClickDelete] === 'function') {
                    window[onClickDelete](id);
                }

                removeRowAndCheckEmpty(id);
            });

        }

        function removeRowAndCheckEmpty(id) {
            const tbody = document.getElementById(`${initialTable}-body`);

            const row = tbody.querySelector(`tr[data-id="${id}"]`);
            if (row) row.remove();

            const dataRows = tbody.querySelectorAll('tr[data-id]');

            if (dataRows.length === 0) {
                tbody.innerHTML = `
                <tr class="empty-row">
                    <td colspan="{{ count($setupColumn) }}"
                        class="text-center py-6 text-slate-400">
                        Data tidak tersedia
                    </td>
                </tr>
        `;
            }
            summaryForm();
        }

        window.getDetailTableData = function() {
            const tableBody = document.getElementById(`${initialTable}-body`);
            if (!tableBody) return [];

            const rows = tableBody.querySelectorAll('tr[data-id]');
            const result = [];

            rows.forEach(row => {
                const rowData = {
                    item_id: row.dataset.id
                };

                const inputs = row.querySelectorAll('input[name$="[]"]');

                inputs.forEach(input => {
                    const key = input.name.replace('[]', '');
                    rowData[key] = input.value;
                });

                result.push(rowData);
            });

            return result;
        };

        window.clearDetailTable = function() {
            const tbody = document.getElementById(`${initialTable}-body`)
            if (!tbody) return

            tbody.querySelectorAll('tr[data-id]').forEach(row => row.remove())


            tbody.innerHTML = `
                <tr class="empty-row">
                    <td colspan="{{ count($setupColumn) }}"
                        class="text-center py-6 text-slate-400">
                        Data tidak tersedia
                    </td>
                </tr>
            `

            if (typeof summaryForm === 'function') {
                summaryForm()
            }
        }

        window.getDetailTableLength = function() {
            const tbody = document.getElementById(`${initialTable}-body`)
            if (!tbody) return 0

            return tbody.querySelectorAll('tr[data-id]').length
        }
    }
</script>
