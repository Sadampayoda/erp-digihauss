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
            <x-input-text :readonly="true" name="item_quantity" label="Jumlah Barang"
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

                const margin = ids.module == 'sales' ? revenue - cost : cost - revenue;
                const columnModuleMargin = ids.module == 'sales' ? revenue : cost;
                const marginPercentage = columnModuleMargin ?
                    Math.round((margin / columnModuleMargin) * 100) :
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
            console.log(basePrice);

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

        const totalService = service;
        const totalTransaction = subTotal + (ids.module == 'sales' ? 0 : totalService);
        const totalTransactionPurchase = purchasePrice + (ids.module == 'sales' ? totalService : 0);

        const margin =
            ids.module == 'sales' ?
            (totalTransaction - totalTransactionPurchase) : totalTransactionPurchase - totalTransaction;

        const columnMarginPercentage = ids.module == 'sales' ? totalTransaction : totalTransactionPurchase;
        const marginPercentage = columnMarginPercentage ?
            Math.round((margin / columnMarginPercentage) * 100) :
            0;


        const form = document.getElementById('subTotalForm');

        form.querySelector('[name="item_quantity"]').value = rows.length;

        setValue(ids.parentSubTotal, subTotal);
        setValue(ids.parentPurchasePrice, purchasePrice);
        setValue(ids.parentService, service);

        setValue(ids.parentMargin, margin);
        setValue(ids.parentMarginPercentage, marginPercentage);

        if (ids.parentPaidAmount) {
            setValue(ids.parentPaidAmount, totalTransaction);
        }

        const advanceEl = document.getElementById(ids.parentAdvanceAmount);
        const remainingEl = document.getElementById(ids.parentRemainingAmount);

        if (advanceEl && remainingEl) {

            const advance = Number(advanceEl.value) || 0;

            const remaining = totalTransaction - advance;

            remainingEl.value = remaining;

            const remainingLabel = document.getElementById(`${ids.parentRemainingAmount}-label`);
            if (remainingLabel) {
                remainingLabel.value = formatRupiah(remaining);
            }

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

    const setValue = (name, value) => {
        const form = document.getElementById('subTotalForm');
        const input = form.querySelector(`[name="${name}"]`);
        if (input) {
            input.value = value;
        }

        const label = document.getElementById(`${name}-label`);
        if (label) {
            label.value = formatRupiah(value);
        }

    };
</script>
