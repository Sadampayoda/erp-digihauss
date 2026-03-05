<div class="bg-white rounded-xl shadow min-w-0">
    <div class="max-h-[40vh] overflow-x-auto overflow-y-auto custom-scroll">
        <x-table-details :setupColumn="$setupColumn" />
    </div>
</div>


<script>
    const createItems = () => {
        setButtonLoading(true);
        const selected = Array.from(document.querySelectorAll('.rowCheckbox:checked'))
            .map(cb => cb.value);

        if (selected.length < 1) {
            showAlert('Terdapat Kesalahan', 'Pilih setidaknya 1 barang', 'errors', false);
            setButtonLoading(false);
        }

        $.ajax({
            url: "{{ route('items.index') }}",
            type: 'GET',
            data: {
                items: selected,
                _token: '{{ csrf_token() }}',
                advance_sale: true
            },
            success: function(res) {

                res.data.forEach(item => {
                    item.quantity = 1
                    renderDetailRow(item, setupColumn)
                })
                summaryForm();
                showAlert('Sukses Tambah', res.message, 'success', false);
                setButtonLoading(false);
                $('#sales-invoices-modal').addClass('hidden').removeClass('flex');
            },
            error: function(err) {

            }
        });
    }

    window.onDelete = (id) => {

    }

    const items = @json(@$data->items);
    const setup = @json($setupColumn);
    items.forEach((item) => {
        item.detail_id = item.id
        item.id = item?.item?.id
        item.image = item?.item?.image
        item.name = item.item_name;
        item.variant = item.item?.variant
        console.log(item.item);
        renderDetailRow(item, setup)
    })

</script>
