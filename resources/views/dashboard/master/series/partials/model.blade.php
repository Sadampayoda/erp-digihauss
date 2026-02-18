<!-- Form -->

<x-modal id="series-modal" title="Tambah Series" onSubmit="submit">
    <form method="POST" action="{{ route('series.store') }}" class="flex flex-col gap-4" id="form-modal">
        @csrf
        <input type="hidden" name="_method" id="form-method" value="POST">
        <x-input-text name="code" label="Series Code" placeholder="IP11" class="rounded-lg px-3 py-2" />

        <x-input-text name="name" label="Series Name" placeholder="iPhone 11" class="rounded-lg px-3 py-2" />

        <x-input-text type="number" name="release_year" label="Series Name" placeholder="2025" class="rounded-lg px-3 py-2" />
    </form>
</x-modal>


<script>
    const openBtn = document.getElementById('btn-open-series-modal')
    const modal = document.getElementById('series-modal')
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
                $('#series-modal').addClass('hidden').removeClass('flex');
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
