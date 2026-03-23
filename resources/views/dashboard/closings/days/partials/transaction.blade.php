<div class="bg-white rounded-xl shadow min-w-0">
    <div class="max-h-[40vh] overflow-x-auto overflow-y-auto custom-scroll">
        <x-table-details :setupColumn="$setupColumnTransaction" />
    </div>
</div>


<script>
    const items = @json(@$closing->dailyClosingItems);
    const setup = @json($setupColumnTransaction);
    items.forEach((item) => {
        item.transaction_number = item.sales_invoice?.transaction_number;
        item.name = item.item?.name;
        item.serial_number = item.item_detail?.serial_number;
        item.sale_price_base = item.item_detail?.sale_price;
        item.purchase_price_base = item.item_detail?.purchase_price;
        item.service_base = item.item_detail?.service;
        item.service = item.item_detail?.service;
        item.purchase_price = item.item_detail?.purchase_price;
        item.sale_price = item.item_detail?.sale_price;
        renderDetailRow(item, setup)
    })
</script>
