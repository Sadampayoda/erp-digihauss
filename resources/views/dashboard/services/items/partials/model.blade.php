<!-- Form -->

<x-modal id="item-service-modal" title="Tambah Service Barang" onSubmit="submit">
    <form method="POST" action="{{ route('services.store') }}" class="flex flex-col gap-4" id="form-modal">
        @csrf
        <input type="hidden" name="_method" id="form-method" value="POST">

        <x-input-select name="item_detail_id" label="SN / IMEI" route="{{ route('item.details.index') }}"
            :params="[
                'status' => 1,
            ]" :required="true" />

        <x-input-text type="date" :required="true" border_color="border-stone-300" name="transaction_date"
            label="Tanggal Service" class="rounded-sm p-1 md:p-2" />

        <x-input-select name="payment_method" :route="route('payment-methods.index')" label="Metode Pembayaran" :required="true"
            class="rounded-sm" />

        <x-input-text type="number" name="service" label="Biaya Service" border_color="border-stone-300"
            class="rounded-sm p-1 md:p-2" required />

        <x-input-text name="description" label="Deskripsi" placeholder="Uraian pembayaran"
            border_color="border-stone-300" class="rounded-sm p-1 md:p-2" />
    </form>
</x-modal>


<script>
    const openBtn = document.getElementById('btn-open-item-service-modal')
    const modal = document.getElementById('item-service-modal')
    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    const submit = () => {
        setButtonLoading(true);
        const $form = $('#form-modal');

        const url = $form.attr('action');
        const method = $form.find('[name="_method"]').val() || 'POST';
        const data = $form.serialize();

        console.log(url, method, data);

        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(res) {
                console.log('success', res);

                setButtonLoading(false);
                $('#item-service-modal').addClass('hidden').removeClass('flex');
                showAlert('Sukses', res.message);

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
