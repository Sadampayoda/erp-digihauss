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
    'parentPaidAmount' => null,
    'parentRemainingAmount' => 'remaining_amount',
    'parentAdvanceAmount' => 'advance_amount',
    'module' => 'sales',
])

<x-modal id="{{ $summaryFormModal }}" title="Sub total" onSubmit="submit" width="w-[100%] sm:max-w-4xl">
    <form id="subTotalForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- ROW 1 --}}
        <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4 pb-4 border-b border-slate-300">
            <x-input-text :readonly="true" type="number" name="item_quantity" label="Jumlah Barang"
                class="rounded-lg px-3 py-2" value="0" />

            <x-input-text :readonly="true" type="number" :name="$parentSubTotal"
                label="{{ $module == 'sales' ? 'Total Harga Jual' : 'Total Harga Beli' }}" class="rounded-lg px-3 py-2"
                value="0" />

            <x-input-text :readonly="true" type="number" :name="$parentPurchasePrice"
                label="{{ $module == 'purchase' ? 'Total Harga Jual' : 'Total Harga Beli' }}"
                class="rounded-lg px-3 py-2" value="0" />
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
        <x-input-text :readonly="true" type="number" :name="$parentPaidAmount" label="Total Transaksi"
            class="rounded-lg px-3 py-2" value="0" />

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
        parentRemainingAmount: "{{ $parentRemainingAmount }}",
        parentAdvanceAmount: "{{ $parentAdvanceAmount }}",
        module: "{{ $module }}",
    }

    const tbody = document.getElementById(`${ids.tableId}`);

    window.summaryForm = () => {

        const rows = tbody.querySelectorAll('tr[data-id]');

        Array.from(rows).forEach((row) => {

            const quantity = getInputValue(ids.columnQuantityTable, row);
            const price = getInputValue(ids.columnPriceTable, row);
            const purchasePrice = getInputValue(ids.columnPurchasePriceTable, row);
            const service = getInputValue(ids.columnServiceTable, row);

            const basePrice = parsePrice(price, purchasePrice);

            const revenue = basePrice.base_price * quantity;
            const cost = basePrice.compare_price * quantity;

            const subTotal = revenue + service;

            putInputValue(ids.columnSubTotalTable, row, subTotal);

            if (ids.columnMarginTable) {

                const margin = revenue - cost;

                const marginPercentage = revenue ?
                    Math.round((margin / revenue) * 100) :
                    0;

                putInputValue(ids.columnMarginTable, row, margin);
                putInputValue(ids.columnMarginPercentageTable, row, marginPercentage);
            }

        });

        const subTotal = Array.from(rows).reduce((total, item) => {

            const quantity = getInputValue(ids.columnQuantityTable, item);
            const salePrice = getInputValue(ids.columnPriceTable, item);
            const purchasePrice = getInputValue(ids.columnPurchasePriceTable, item);

            const basePrice = parsePrice(salePrice, purchasePrice);

            return total + (basePrice.base_price * quantity);

        }, 0);


        const purchasePrice = Array.from(rows).reduce((total, item) => {

            const quantity = getInputValue(ids.columnQuantityTable, item);
            const salePrice = getInputValue(ids.columnPriceTable, item);
            const purchase = getInputValue(ids.columnPurchasePriceTable, item);

            const basePrice = parsePrice(salePrice, purchase);

            return total + (basePrice.compare_price * quantity);

        }, 0);


        const service = Array.from(rows).reduce((total, item) => {

            const service = getInputValue(ids.columnServiceTable, item) || 0;

            return total + service;

        }, 0);

        const totalTransaction = subTotal + service;

        const margin = totalTransaction - purchasePrice;

        const marginPercentage = totalTransaction ?
            Math.round((margin / totalTransaction) * 100) :
            0;


        const form = document.getElementById('subTotalForm');

        form.querySelector('[name="item_quantity"]').value = rows.length;

        form.querySelector(`[name="${ids.parentSubTotal}"]`).value = subTotal;
        form.querySelector(`[name="${ids.parentPurchasePrice}"]`).value = purchasePrice;
        form.querySelector(`[name="${ids.parentService}"]`).value = service;

        form.querySelector(`[name="${ids.parentMargin}"]`).value = margin;
        form.querySelector(`[name="${ids.parentMarginPercentage}"]`).value = marginPercentage;

        if (ids.parentPaidAmount) {
            form.querySelector(`[name="${ids.parentPaidAmount}"]`).value = totalTransaction;
        }

        const advanceEl = document.getElementById(ids.parentAdvanceAmount);
        const remainingEl = document.getElementById(ids.parentRemainingAmount);

        if (advanceEl && remainingEl) {

            const advance = Number(advanceEl.value) || 0;

            remainingEl.value = totalTransaction - advance;

        }

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

    const parsePrice = (salePrice, purchasePrice) => {

        if (ids.module === 'sales') {
            return {
                base_price: salePrice,
                compare_price: purchasePrice
            }
        }

        if (ids.module === 'purchase') {
            return {
                base_price: purchasePrice,
                compare_price: salePrice
            }
        }

        return {
            base_price: salePrice,
            compare_price: purchasePrice
        }
    }
</script>
