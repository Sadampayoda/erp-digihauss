@extends('template.dashboard')

@section('content')
    @php
        $editMode = isset($data->id);
    @endphp
    <div class="flex flex-col lg:flex-row w-full gap-5 overflow-hidden">
        <div class="flex flex-col w-full lg:w-2/3 min-w-0 overflow-hidden gap-3 ">
            {{-- @include('dashboard.sales.sales_returns.partials.source_form') --}}
            @include('dashboard.sales.sales_returns.partials.general_form')
            <div class="bg-white rounded-xl shadow min-w-0 ">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
                    <div>
                        <p class="text-xl font-medium">Detail Pesanan</p>
                        <p class="text-sm font-medium text-slate-400">Pesanan yang akan dikembalikan
                        </p>
                    </div>
                    <button onclick="onTakeSi()" type="button" id="btn-sales-invoices-modal"
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
                            Ambil Barang
                        </p>
                    </button>
                </div>
                @include('dashboard.sales.sales_returns.partials.items_form')
            </div>
        </div>
        @include('dashboard.sales.sales_returns.partials.invoice')
    </div>

    {{-- @include('dashboard.sales.sales_returns.partials.modal') --}}

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            refreshSource()
        });

        const editMode = @json(@$editMode);
        const id = @json(@$data->id)

        const submit = () => {
            setButtonLoading(true, 'sales-returns-modal-button', 'btn-text-sales-return');
            const data = new FormData();

            ['generalForm','informationForm', 'subTotalForm'].forEach(id => {
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

            console.log(items);

            if (editMode) {
                data.append('_method', 'PUT');
            }

            const url = editMode ?
                `{{ url('sales-returns') }}/${id}` :
                `{{ route('sales-returns.store') }}`;

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
                    setButtonLoading(false, 'sales-returns-modal-button', 'btn-text-sales-return');
                    showAlert('Sukses', res.message, 'success', false,
                        "{{ route('sales-returns.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'sales-returns-modal-button', 'btn-text-sales-return');
                    showAlert('Gagal Mengembalikan', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });
        }

        const onTakeSi = () => {
            const salesInvoiceId = document.getElementById('sales_invoice_id').value;
            if (!salesInvoiceId) {
                showAlert('Gagal ambil data', 'Pilih data transaksi SI terlebih dahulu', 'errors', true);
                return;
            }
            Swal.fire({
                title: 'Yakin menggunakan transaksi tersebut?',
                text: 'Data barang akan di ambil berdasarkan sumber transaksi ',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Lakukan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/sales-invoices/${salesInvoiceId}`,
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'GET'
                        },
                        success: function(res) {
                            showAlert('Sukses', res.message, 'success', false);
                            clearDetailTable();
                            const data = res.data

                            // const advanceAmount = document.getElementById('advance_amount')
                            // if (data.advance_amount) {
                            //     advanceAmount.value = data.advance_amount
                            // }

                            console.log(data);
                            data.items.forEach((item) => {
                                item.sales_invoice_items_id = item.id
                                item.id = item?.item?.id
                                item.image = item?.item?.image
                                item.name = item.item_name;
                                item.variant = item.item?.variant
                                item.si_quantity = item.quantity
                                item.quantity = 0
                                renderDetailRow(item, setup)
                            });

                            summaryForm();


                            // const remainingAmount = document.getElementById(
                            //     'remaining_amount')
                            // if (data.remaining_amount) {
                            //     remainingAmount.value = data.remaining_amount - data.advance_amount;
                            // }

                            lockDetailTableColumns(
                                ['sale_price', 'service'], {
                                    mode: 'readonly'
                                }
                            )
                        },
                        error: function(err) {
                            showAlert('Gagal', message, 'errors', true);
                        }
                    });
                }
            });
        }
    </script>
@endsection
