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
        window.renderDetailRow = function(item, setupColumn, duplicateColumn = 'item_detail_id', nameTable =
            'itemsTable') {
            const table = nameTable ?? initialTable;
            const tableBody = document.getElementById(`${table}-body`);
            let uid = `${item.id}-${generateUID()}`;
            if (!tableBody) return;

            const emptyRow = tableBody.querySelector('.empty-row');
            if (emptyRow) emptyRow.remove();

            if (duplicateColumn) {
                const rows = tableBody.querySelectorAll('tr[data-id]');

                const isDuplicate = Array.from(rows).some(row => {
                    const input = row.querySelector(`[name="${duplicateColumn}[]"]`);
                    return input && input.value == item[duplicateColumn];
                });

                if (isDuplicate) {
                    console.warn(`Duplicate ${duplicateColumn}:`, item[duplicateColumn]);
                    return;
                }
            }

            let row = `<tr class="hover:bg-slate-50"
                data-id="${uid}"
                data-real-id="${item.id}">`;


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
                                        onclick="onDeleteDetail('${uid}')"
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



            lucide.createIcons();
        };

        window.onDeleteDetail = (uid) => {
            Swal.fire({
                title: "Yakin dihapus ?",
                showCancelButton: true,
                confirmButtonText: "Hapus",
            }).then((result) => {
                if (!result.isConfirmed) return;

                if (onClickDelete && typeof window[onClickDelete] === 'function') {
                    window[onClickDelete](uid);
                }

                removeRowAndCheckEmpty(uid);
            });

        }

        function removeRowAndCheckEmpty(uid) {
            const tbody = document.getElementById(`${initialTable}-body`);

            const row = tbody.querySelector(`tr[data-id="${uid}"]`);
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
            if (typeof summaryForm === 'function') {
                summaryForm()
            }
        }

        window.getDetailTableData = function() {
            const tableBody = document.getElementById(`${initialTable}-body`);
            if (!tableBody) return [];

            const rows = tableBody.querySelectorAll('tr[data-id]');
            const result = [];

            rows.forEach(row => {
                const rowData = {
                    item_id: row.dataset.realId
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

        window.lockDetailTableColumns = function(columns = [], options = {}) {
            const {
                mode = 'readonly',
                    clearValue = false
            } = options

            const tbody = document.getElementById(`${initialTable}-body`)
            if (!tbody) return

            columns.forEach(column => {
                const inputs = tbody.querySelectorAll(`input[name="${column}[]"]`)

                inputs.forEach(input => {
                    if (mode === 'disabled') {
                        input.disabled = true
                    } else {
                        input.readOnly = true
                    }

                    if (clearValue) {
                        input.value = ''
                    }

                    input.classList.add(
                        'bg-slate-100',
                        'cursor-not-allowed',
                        'text-slate-500'
                    )
                })
            })
        }

        window.unlockDetailTableColumns = function(columns = []) {
            const tbody = document.getElementById(`${initialTable}-body`)
            if (!tbody) return

            columns.forEach(column => {
                const inputs = tbody.querySelectorAll(`input[name="${column}[]"]`)

                inputs.forEach(input => {
                    input.readOnly = false
                    input.disabled = false

                    input.classList.remove(
                        'bg-slate-100',
                        'cursor-not-allowed',
                        'text-slate-500'
                    )
                })
            })
        }

        window.generateUID = (length = 6) => {
            return Math.random().toString(36).substring(2, 2 + length);
        }
    }
</script>
