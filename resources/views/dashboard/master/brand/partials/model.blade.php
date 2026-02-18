<!-- Form -->

<x-modal id="brand-modal" title="Tambah Brand" onSubmit="submit">
    <form method="POST" action="{{ route('brands.store') }}" class="flex flex-col gap-4" id="form-modal">
        @csrf
        <input type="hidden" name="_method" id="form-method" value="POST">
        <x-input-text name="code" label="Brand Code" placeholder="APPLE" class="rounded-lg px-3 py-2" />

        <x-input-text name="name" label="Brand Name" placeholder="Apple" class="rounded-lg px-3 py-2" />
    </form>
</x-modal>


<script>
    const openBtn = document.getElementById('btn-open-brand-modal')
    const modal = document.getElementById('brand-modal')
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
                $('#brand-modal').addClass('hidden').removeClass('flex');
                showAlert('Sukses', res.message);

            },
            error: function(err) {
                const message = err.responseJSON.message;
                setButtonLoading(false);
                showAlert('Gagal', message, 'errors', true);
                resetErrors();

                if (err.responseJSON.errors) {
                    showValidationErrors(err.responseJSON.errors);
                }
            }
        });
    }
</script>
