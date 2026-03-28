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
                transaction: true
            },
            success: function(res) {

                res.data.forEach(item => {
                    console.log(item)
                    item.quantity_requested = 1
                    item.quantity_approved = 0
                    item.price = 0
                    item.unit = item.unit?.name
                    item.id = item?.id
                    renderDetailRow(item, setupColumn)
                })

                summaryForm();
                showAlert('Sukses Tambah', res.message, 'success', false);
                setButtonLoading(false);

                if(!@json($isAppoved)) {
                    lockDetailTableColumns(
                        ['quantity_approved'], {
                            mode: 'readonly'
                        }
                    )
                }
                $('#atk-requests-modal').addClass('hidden').removeClass('flex');
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
        renderDetailRow(item, setup)
    })
</script>
