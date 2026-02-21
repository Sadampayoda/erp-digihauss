<!-- Form -->

<x-modal id="coa-modal" title="Tambah COA" onSubmit="submit">
    <form method="POST" action="{{ route('coas.store') }}" class="flex flex-col gap-4" id="form-modal">
        @csrf
        <input type="hidden" name="_method" id="form-method" value="POST">

        <x-input-select name="type" label="Tipe Akun" :options="[
            'asset' => 'Aset',
            'liability' => 'Liabilitas',
            'equity' => 'Ekuitas',
            'income' => 'Pendapatan',
            'expense' => 'Beban',
        ]" required />

        <x-input-select name="parent_id" label="Parent Akun" route="{{ route('coas.index') }}" :params="['only_parent' => true]" />

        <x-input-text name="name" label="Nama Akun" placeholder="Contoh: Bank BCA" class="rounded-lg px-3 py-2"
            required />

        <x-input-text name="level" label="Level" type="number" class="rounded-lg px-3 py-2" readonly />

        <x-input-select name="is_postable" label="Bisa Dipakai Transaksi?" placeholder="Pilih" :options="[
            1 => 'Ya (Akun Transaksi)',
            0 => 'Tidak (Grouping)',
        ]"
            required />

        <x-input-text name="description" label="Deskripsi" placeholder="Opsional" class="rounded-lg px-3 py-2" />
    </form>
</x-modal>



<script>
    const openBtn = document.getElementById('btn-open-coa-modal')
    const modal = document.getElementById('coa-modal')
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

        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(res) {
                console.log('success', res);

                setButtonLoading(false);
                $('#coa-modal').addClass('hidden').removeClass('flex');
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
