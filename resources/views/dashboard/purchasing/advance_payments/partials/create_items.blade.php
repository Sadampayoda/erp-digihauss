<!-- Form -->

<x-modal id="advance-item-new-modal" title="Tambah Barang Baru" onSubmit="onSubmiItem" typeButton="button">
    <form class="flex flex-col gap-4" action="{{ route('item.details.store') }}" id="form-modal">
        @csrf
        <input type="hidden" name="_method" id="form-method" value="POST">

        <x-input-select name="item_id" label="Model Name (Parent Item)" route="{{ route('items.index') }}"
            :required="true" />

        <x-input-text name="imei" label="IMEI Number" placeholder="Enter 15-digit IMEI" class="rounded-sm p-1 md:p-2"
            border_color="border-stone-300" :required="true" />

        <x-input-text name="serial_number" label="Serial Number" placeholder="Enter Serial Number"
            class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :required="true" />

        <x-input-text name="color" label="Color" placeholder="Silver" class="rounded-sm p-1 md:p-2"
            border_color="border-stone-300" :required="true" />

        <x-input-text type="number" name="internal_storage" label="Internal Storage" placeholder="128GB"
            class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :required="true" />

        <x-input-text type="number" name="network" label="Network" placeholder="5G" class="rounded-sm p-1 md:p-2"
            border_color="border-stone-300" :required="true" />

        <div>
            <label class="text-sm font-medium">Item Condition</label>

            <div class="flex gap-4 mt-2">

                <label class="flex items-center gap-2">
                    <input type="radio" name="type" value="new" {{ @$data->type == 'new' ? 'checked' : '' }}>
                    New
                </label>

                <label class="flex items-center gap-2">
                    <input type="radio" name="type" value="second" {{ @$data->type == 'second' ? 'checked' : '' }}>
                    Second
                </label>

            </div>
        </div>

        <x-input-text type="number" :required="true" name="purchase_price" label="Harga Beli" placeholder="IDR 0"
            class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :value="@$data->purchase_price" />

        <x-input-text type="number" name="sale_price" label="Harga Jual" placeholder="IDR 0"
            class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :value="@$data->sale_price" />

    </form>
</x-modal>


<script>
    const openBtnItemNew = document.getElementById('btn-advance-item-new-modal')
    const modalItemNew = document.getElementById('advance-item-new-modal')
    openBtnItemNew.addEventListener('click', () => {
        modalItemNew.classList.remove('hidden');
        modalItemNew.classList.add('flex');
    });

    const onSubmiItem = () => {
        setButtonLoading(true);
        const $form = $('#form-modal');

        const url = $form.attr('action');
        const method = $form.find('[name="_method"]').val() || 'POST';
        const data = $form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(res) {
                console.log('success', res);

                setButtonLoading(false);
                $('#advance-item-new-modal').addClass('hidden').removeClass('flex');
                showAlert('Sukses', res.message, 'success', false);

            },
            error: function(err) {
                const message = err.responseJSON.message;
                setButtonLoading(false);
                showAlert('Gagal', message, 'errors', false);
                resetErrors();

                if (err.responseJSON.errors) {
                    showValidationErrors(err.responseJSON.errors);
                }
            }
        });
    }
</script>
