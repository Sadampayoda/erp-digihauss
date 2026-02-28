<div class="bg-white rounded-xl shadow min-w-0">

    <div class="max-h-[40vh] overflow-x-auto overflow-y-auto custom-scroll">
        <x-table-details initialTable="itemsTable-{{ $key }}" :setupColumn="$setupColumn" />
    </div>
</div>


<script>

    (() => {
        const setup = @json($setupColumn);
        const item = @json($item);
        const key = @json($key);
        item.details.forEach(detail => {
            detail.transaction_date = item.journal_date
            renderDetailRow(detail, setup, `itemsTable-${key}`)
        })
    })();

</script>
