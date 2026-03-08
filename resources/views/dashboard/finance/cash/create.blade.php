<div class="flex flex-col lg:flex-row w-full gap-5 overflow-hidden">
    <div class="flex flex-col w-full  min-w-0 overflow-hidden gap-3 ">
        <div class="flex flex-col rounded-xl bg-white">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
                <div>
                    <p class="text-xl font-medium">General Form {{ $cash::$typeDesc[$type] }} </p>
                    <p class="text-sm font-medium text-slate-400">Melakukan transaksi {{ $cash::$typeDesc[$type] }}</p>
                </div>
                <button onclick="submit()" id="cash-modal-button"
                    class="
                            group flex items-center justify-center gap-2
                            bg-emerald-400 text-white
                            px-6 py-3 rounded-xl
                            transition-all duration-300
                            hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                            active:scale-95
                            cursor-pointer
                        ">
                    <span class="flex flex-row gap-2 text-sm lg:text-base font-medium btn-text-cash">
                        <i data-lucide="save"
                            class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                        Simpan
                    </span>
                </button>
            </div>
            <form id="generalForm"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
                    px-2 py-1 mx-3 sm:mx-5 my-1 gap-2">

                <div>
                    <x-input-text border_color="border-stone-300" class="rounded-sm p-1 md:p-2" :readonly="true"
                        name="transaction_number" label="No. Transaksi {{ $cash::$typeDesc[$type] }}" />
                </div>
                <input type="hidden" name="type" id="type" value="{{ $type }}">
                <input type="hidden" name="id" id="id">

                <div>
                    <x-input-text border_color="border-stone-300" class="rounded-sm p-1 md:p-2" type="date"
                        name="transaction_date" label="Tanggal {{ $cash::$typeDesc[$type] }}" :required="true" />
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <x-input-select name="coa_debit" label="Kas Tujuan" :route="route('coas.index')" :params="['level' => 3, 'type' => 'asset']"
                        :required="true" />

                    <x-input-select name="coa_credit" label="Sumber Dana" :route="route('coas.index')" :params="['level' => 3]"
                        :required="true" />
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <x-input-status :required="true" label="Status" name="status" border_color="border-stone-300"
                        class="rounded-sm p-1 md:p-2" />

                    <x-input-text type="number" name="paid_amount" label="Pembayaran" border_color="border-stone-300"
                        class="rounded-sm p-1 md:p-2" required />
                </div>
                <div class="sm:col-span-2">
                    <x-input-text name="description" label="Deskripsi" placeholder="Uraian pembayaran"
                        border_color="border-stone-300" class="rounded-sm p-1 md:p-2" />
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    const submit = () => {
        setButtonLoading(true, 'cash-modal-button', 'btn-text-cash');
        const data = new FormData();

        ['generalForm'].forEach(id => {
            const form = document.getElementById(id);
            if (!form) return;

            new FormData(form).forEach((value, key) => {
                data.append(key, value);
            });
        });

        const id = document.getElementById('id').value;
        const editMode = !!id;

        if (editMode) {
            data.append('_method', 'PUT');
        }

        const url = editMode ?
            `{{ url('cashs') }}/${id}` :
            `{{ route('cashs.store') }}`;

        $.ajax({
            url,
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                setButtonLoading(false, 'cash-modal-button', 'btn-text-cash');
                showAlert('Sukses', res.message, 'success', false,
                    "{{ route('cashs.index', ['type' => $type]) }}");
            },
            error: function(err) {
                const message = err.responseJSON.message;
                setButtonLoading(false, 'cash-modal-button', 'btn-text-cash');
                showAlert('Gagal Menambahkan Uang Muka', message, 'errors', false);
                resetErrors();

                if (err.responseJSON.errors) {
                    showValidationErrors(err.responseJSON.errors);
                }
            }
        });
    }
</script>
