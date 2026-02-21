@extends('template.dashboard')

@section('content')
    @php
        $editMode = isset($data->id);
    @endphp
    <div class="flex flex-col lg:flex-row w-full gap-5 overflow-hidden">
        <div class="flex flex-col w-full lg:w-2/3 min-w-0 overflow-hidden gap-3 ">
            @include('dashboard.sales.sales_invoices.partials.source_form')
            @include('dashboard.sales.sales_invoices.partials.general_form')
            <div class="bg-white rounded-xl shadow min-w-0 ">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
                    <div>
                        <p class="text-xl font-medium">Detail Pesanan</p>
                        <p class="text-sm font-medium text-slate-400">Pesanan yang akan dibayar oleh pelanggan
                        </p>
                    </div>
                    <button type="button" id="btn-sales-invoices-modal"
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

                    </button>
                </div>
                @include('dashboard.sales.sales_invoices.partials.items_form')
            </div>
        </div>
        @include('dashboard.sales.sales_invoices.partials.invoice')
    </div>

    @include('dashboard.sales.sales_invoices.partials.modal')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            refreshSource()
        });

        const editMode = @json(@$editMode);
        const id = @json(@$data->id)

        const submit = () => {
            setButtonLoading(true, 'sales-invoices-modal-button', 'btn-text-sales-invoice');
            const data = new FormData();

            ['generalForm','sourceForm', 'informationForm', 'subTotalForm'].forEach(id => {
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
                `{{ url('sales-invoices') }}/${id}` :
                `{{ route('sales-invoices.store') }}`;

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
                    setButtonLoading(false, 'sales-invoices-modal-button', 'btn-text-sales-invoice');
                    showAlert('Sukses', res.message, 'success', false,
                        "{{ route('sales-invoices.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'sales-invoices-modal-button', 'btn-text-sales-invoice');
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
