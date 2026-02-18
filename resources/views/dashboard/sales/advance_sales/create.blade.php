@extends('template.dashboard')

@section('content')
    @php
        $editMode = isset($data->id);
    @endphp
    <div class="flex flex-col lg:flex-row w-full gap-5 overflow-hidden">
        <div class="flex flex-col w-full lg:w-2/3 min-w-0 overflow-hidden gap-3 ">
            @include('dashboard.sales.advance_sales.partials.general_form')
            <div class="bg-white rounded-xl shadow min-w-0 ">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
                    <div>
                        <p class="text-xl font-medium">Detail Pesanan</p>
                        <p class="text-sm font-medium text-slate-400">Pesanan yang akan dipesan dengan pembayaran uang muka
                        </p>
                    </div>
                    <a id="btn-advance-item-modal"
                        class="
                            group flex items-center justify-center gap-2
                            bg-emerald-400 text-white
                            px-3 py-2 rounded-md
                            sm:px-4 sm:py-2
                            lg:px-6 lg:py-3 lg:rounded-xl
                            transition-all duration-300
                            hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                            active:scale-95 cursor-pointer
                        ">
                        <i data-lucide="plus" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                        <p class="text-sm lg:text-base font-medium">
                            Tambah Barang
                        </p>

                    </a>
                </div>
                @include('dashboard.sales.advance_sales.partials.items_form')
            </div>
        </div>
        @include('dashboard.sales.advance_sales.partials.invoice')
    </div>

    @include('dashboard.sales.advance_sales.partials.modal')

    <script>
        function openItemModal() {
            const modal = document.getElementById('itemModal')
            const backdrop = document.getElementById('itemBackdrop')
            const content = document.getElementById('itemContent')

            modal.classList.remove('hidden')

            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0')
                backdrop.classList.add('opacity-100')

                content.classList.remove('opacity-0', 'translate-y-8', 'scale-95')
                content.classList.add('opacity-100', 'translate-y-0', 'scale-100')
            })
        }

        function closeItemModal() {
            const modal = document.getElementById('itemModal')
            const backdrop = document.getElementById('itemBackdrop')
            const content = document.getElementById('itemContent')

            backdrop.classList.add('opacity-0')
            content.classList.add('opacity-0', 'translate-y-8', 'scale-95')

            setTimeout(() => {
                modal.classList.add('hidden')
            }, 300)
        }

        const editMode = @json(@$editMode);
        const id = @json(@$data->id)

        const submit = () => {
            setButtonLoading(true, 'advance-sale-modal-button', 'btn-text-advance-sale');
            const data = new FormData();

            ['generalForm', 'informationForm', 'subTotalForm'].forEach(id => {
                const form = document.getElementById(id);
                if (!form) return;

                new FormData(form).forEach((value, key) => {
                    data.append(key, value);
                });
            });

            const items = getDetailTableData();
            items.forEach((item, index) => {
                Object.keys(item).forEach(key => {
                    data.append(`items[${index}][${key}]`, item[key]);
                });
            });

            if (editMode) {
                data.append('_method', 'PUT');
            }

            const url = editMode ?
                `{{ url('advance-sales') }}/${id}` :
                `{{ route('advance-sales.store') }}`;

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
                    setButtonLoading(false, 'advance-sale-modal-button', 'btn-text-advance-sale');
                    showAlert('Sukses', res.message, 'success', false,
                    "{{ route('advance-sales.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'advance-sale-modal-button', 'btn-text-advance-sale');
                    showAlert('Gagal Menambahkan Uang Muka', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });
        }
    </script>
@endsection
