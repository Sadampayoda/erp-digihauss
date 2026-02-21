@props([
    'summaryFormModalButton' => 'button-modal',
    'summaryFormModal' => 'total-modal',
    'tableId' => 'itemsTable-body',
    'columnQuantityTable',
    'columnPriceTable',
    'columnPurchasePriceTable',
    'columnSubTotalTable',
    'columnServiceTable' => null,
    'columnMarginTable' => null,
    'columnMarginPercentageTable' => null,
    'parentSubTotal' => 'sub_total',
    'parentPurchasePrice' => 'purchase_price',
    'parentService' => 'service',
    'parentMargin' => 'margin',
    'parentMarginPercentage' => 'margin_percentage',
    'parentPaidAmount' => null
])

<x-modal id="{{ $summaryFormModal }}" title="Sub total" onSubmit="submit" width="w-[100%] sm:max-w-4xl">
    <form id="subTotalForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- ROW 1 --}}
        <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4 pb-4 border-b border-slate-300">
            <x-input-text :readonly="true" type="number" name="item_quantity" label="Jumlah Barang"
                class="rounded-lg px-3 py-2" value="0" />

            <x-input-text :readonly="true" type="number" :name="$parentSubTotal" label="Total" class="rounded-lg px-3 py-2"
                value="0" />

            <x-input-text :readonly="true" type="number" :name="$parentPurchasePrice" label="Hpp" class="rounded-lg px-3 py-2"
                value="0" />
        </div>

        {{-- ROW 2 --}}
        <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4 pb-4 border-b border-slate-300">
            <x-input-text :readonly="true" type="number" :name="$parentService" label="Service"
                class="rounded-lg px-3 py-2" value="0" />

            <x-input-text :readonly="true" type="number" :name="$parentMargin" label="Margin" class="rounded-lg px-3 py-2"
                value="0" />

            <x-input-text :readonly="true" type="number" :name="$parentMarginPercentage" label="Margin (%)"
                class="rounded-lg px-3 py-2" value="0" />
        </div>
        <x-input-text :readonly="true" type="number" :name="$parentPaidAmount" label="Total Transaksi" class="rounded-lg px-3 py-2"
            value="0" />

    </form>

</x-modal>


<script>
    const summaryFormModal = @json($summaryFormModal);
    const summaryFormModalButton = @json($summaryFormModalButton);
    const openBtnSummary = document.getElementById(`${summaryFormModalButton}`)
    const modelSummary = document.getElementById(`${summaryFormModal}`)
    openBtnSummary.addEventListener('click', () => {
        modelSummary.classList.remove('hidden');
        modelSummary.classList.add('flex');
    });

    window.ids = {
        tableId: "{{ $tableId }}",
        columnQuantityTable: "{{ $columnQuantityTable }}",
        columnPriceTable: "{{ $columnPriceTable }}",
        columnPurchasePriceTable: "{{ $columnPurchasePriceTable }}",
        columnSubTotalTable: "{{ $columnSubTotalTable }}",
        columnServiceTable: "{{ $columnServiceTable }}",
        columnMarginTable: "{{ $columnMarginTable }}",
        columnMarginPercentageTable: "{{ $columnMarginPercentageTable }}",
        parentSubTotal: "{{ $parentSubTotal }}",
        parentPurchasePrice: "{{ $parentPurchasePrice }}",
        parentService: "{{ $parentService }}",
        parentMargin: "{{ $parentMargin }}",
        parentMarginPercentage: "{{ $parentMarginPercentage }}",
        parentPaidAmount: "{{ $parentPaidAmount }}",

    }

    const tbody = document.getElementById(`${ids.tableId}`);

    window.summaryForm = () => {

        const rows = tbody.querySelectorAll('tr[data-id]');

        Array.from(rows).forEach((row) => {
            const quantity = getInputValue(ids.columnQuantityTable, row);
            const price = getInputValue(ids.columnPriceTable, row);
            const purchasePrice = getInputValue(ids.columnPurchasePriceTable, row);
            const service = getInputValue(ids.columnServiceTable, row);

            const subTotal = (price * quantity) - service
            putInputValue(ids.columnSubTotalTable, row, subTotal);

            if (ids.columnMarginTable) {
                const margin = subTotal - (purchasePrice * quantity);
                const marginPercentage = subTotal ?
                    Math.round((margin / subTotal) * 100) :
                    0;


                putInputValue(ids.columnMarginTable, row, margin);
                putInputValue(ids.columnMarginPercentageTable, row, marginPercentage);

            }

        })

        const subTotal = Array.from(rows).reduce((total, item) => {
            const quantity = getInputValue(ids.columnQuantityTable, item);
            const price = getInputValue(ids.columnPriceTable, item);

            const subTotal = price * quantity;

            return total + subTotal
        }, 0)

        const purchasePrice = Array.from(rows).reduce((total, item) => {
            const quantity = getInputValue(ids.columnQuantityTable, item);
            const purchasePrice = getInputValue(ids.columnPurchasePriceTable, item);

            const totalPurchasePrice = purchasePrice * quantity
            return total + totalPurchasePrice
        }, 0)

        const service = Array.from(rows).reduce((total, item) => {
            const service = getInputValue(ids.columnServiceTable, item);
            return total + service
        }, 0)

        const margin = (subTotal - service) - purchasePrice
        const marginPercentage = subTotal ?
            Math.round((margin / (subTotal - service)) * 100) :
            0;

        const form = document.getElementById('subTotalForm');

        form.querySelector('[name="item_quantity"]').value = rows.length;
        form.querySelector(`[name="${ids.parentSubTotal}"]`).value = subTotal;
        form.querySelector(`[name="${ids.parentPurchasePrice}"]`).value = purchasePrice;
        form.querySelector(`[name="${ids.parentService}"]`).value = service;
        form.querySelector(`[name="${ids.parentMargin}"]`).value = margin;
        form.querySelector(`[name="${ids.parentMarginPercentage}"]`).value = marginPercentage;
        form.querySelector(`[name="${ids.parentPaidAmount}"]`).value = subTotal - service;

    }


    window.getInputValue = (className, row) => {
        console.log(className, row);
        const el = row.querySelector(`.${className}`);
        return el ? Number(el.value) || 0 : 0;
    };

    window.putInputValue = (className, row, value) => {
        const el = row.querySelector(`.${className}`);
        if (el) el.value = value;
    };


    document.addEventListener('DOMContentLoaded', () => {
        summaryForm();
    });

    tbody.addEventListener('change', (event) => {
        const target = event.target;
        const row = target.closest('tr[data-id]');
        if (!row) return;
        if (
            target.classList.contains(ids.columnQuantityTable) ||
            target.classList.contains(ids.columnPriceTable) ||
            target.classList.contains(ids.columnServiceTable)
        ) {
            summaryForm();
        }
    });
</script>
