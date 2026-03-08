@extends('template.dashboard')

@section('content')
    @php
        $bank = App\Models\Bank::class;
    @endphp
    <div class="flex flex-col gap-5">
        <div class="flex flex-col lg:flex-row w-full gap-5 overflow-hidden">
            <div class="w-full lg:w-3/4">
                @include('dashboard.finance.bank.create')
            </div>

            <div class="w-full lg:w-1/4">
                @include('dashboard.finance.bank.partials.recent')
            </div>

        </div>


        <div class="bg-white py-4 lg:py-7 px-3 lg:px-5 shadow rounded-2xl flex flex-col gap-5">

            <div class="flex justify-between items-center">
                <button
                    class="
                group flex items-center gap-2
                bg-slate-800 text-stone-200
                px-3 py-2 rounded-md
                sm:px-4 sm:py-2
                lg:px-6 lg:py-3 lg:rounded-xl
                transition-all duration-300 ease-in-out
                hover:bg-slate-700 hover:shadow-lg hover:scale-105
                active:scale-95 cursor-pointer
                ">

                    <i data-lucide="filter" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-6"></i>

                    <p class="hidden sm:block text-sm lg:text-base">
                        Filter
                    </p>

                </button>
            </div>

            {{-- TABLE --}}
            <x-table :data="$banks" titleEdit="Edit" :labels="[
                'transaction_number' => 'No. Transaksi Bank',
                'transaction_date' => 'Tgl Transaksi Bank',
                'paid_amount' => 'Total Pembayaran',
                'status' => 'Status',
                'description' => 'Uraian',
                'created_by_name' => 'Pembuat Bank',
                'updated_by_name' => 'Modifikasi Bank',
            ]" onEdit="onEdit" onDelete="onDelete"  onPaymentProof="onPaymentProof" />

        </div>

    </div>

    <script>
        const onEdit = (id, data) => {
            const transactionNumber = document.getElementById('transaction_number');
            if (data.status >= 2) {
                showAlert('Gagal Edit', 'Bank tidak bisa di edit karena bukan draft/need appoved', 'error')
            }
            if (transactionNumber.value && transactionNumber.value !== data.transaction_number) {
                Swal.fire({
                    title: 'Yakin mengubah sumber ?',
                    text: 'Data input akan terhapus semua !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Lakukan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    } else {
                        fillForm(data)
                    }
                });
            } else {
                fillForm(data)
            }

        }

        const onDelete = (id) => {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: 'Data Bank {{ $type }} ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/cashs/${id}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(res) {
                            showAlert('Sukses', res.message);
                        },
                        error: function(err) {
                            const message = err.responseJSON.message;
                            showAlert('Gagal', message, 'errors', true);
                        }
                    });
                }
            });
        }

        const fillForm = (data) => {
            document.getElementById('transaction_number').value = data.transaction_number;

            document.getElementById('transaction_date').value = data.transaction_date;

            const coaCredit = accessSelect('coa_credit');
            const coaDebit = accessSelect('coa_debit');

            coaCredit.setValue(data.coa_credit);
            coaDebit.setValue(data.coa_debit);

            document.getElementById('status').value = data.status;

            document.getElementById('paid_amount').value = data.paid_amount;

            document.getElementById('description').value = data.description ?? '';

            document.getElementById('type').value = data.type;
            document.getElementById('id').value = data.id;

        }
    </script>
@endsection
