<!-- Form -->

<x-modal id="atk-requests-item-new-modal" title="Tambah Barang Baru" onSubmit="onSubmiItem" typeButton="button">
    <form class="flex flex-col gap-4" action="{{ route('items.store') }}" id="form-modal">
        @csrf
        <input type="hidden" name="_method" id="form-method" value="POST">

        <x-input-text name="code" label="Item Code" placeholder="Kode ATK" class="rounded-sm p-1 md:p-2"
            border_color="border-stone-300" :required="true" />

        <x-input-text name="name" label="Product Name" placeholder="Nama ATK" class="rounded-sm p-1 md:p-2"
            border_color="border-stone-300" :required="true" />

        <x-input-select name="brand" label="Brand" route="{{ route('brands.index') }}" :required="true" />

        <x-input-text name="model" label="Model" placeholder="13 Pro" class="rounded-sm p-1 md:p-2"
            border_color="border-stone-300" :required="true" />

        <x-input-select name="unit_id" label="Unit" route="{{ route('units.index') }}" />

        <x-input-status :allowed="[0]" label="Tipe Barang" transactionStatus="item_type" name="type" border_color="border-stone-300"
            class="rounded-sm p-1 md:p-2" :selected="0" />
    </form>
</x-modal>


<script>
    const openBtnItemNew = document.getElementById('btn-atk-requests-item-new-modal')
    const modalItemNew = document.getElementById('atk-requests-item-new-modal')
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
                $('#atk-requests-item-new-modal').addClass('hidden').removeClass('flex');
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
