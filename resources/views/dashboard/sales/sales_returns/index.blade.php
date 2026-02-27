@extends('template.dashboard')

@section('content')
    <div class="flex flex-col  bg-white w-full h-full py-4 lg:py-7 px-1 lg:px-5 shadow rounded-2xl gap-5">
        <div class="flex flex-row justify-between items-center">
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
            <a href="{{ route('sales-returns.create') }}"
                class="
                    group flex items-center gap-2
                    bg-emerald-400 text-white
                    px-3 py-2 rounded-md
                    sm:px-4 sm:py-2
                    lg:px-6 lg:py-3 lg:rounded-xl
                    transition-all duration-300 ease-in-out
                    hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                    active:scale-95 cursor-pointer
                    ">
                <i data-lucide="plus" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>

                <p class="hidden sm:block text-sm lg:text-base font-medium">
                    Tambah Pengembalian
                </p>
            </a>
        </div>

        <x-table :data="$sales_returns" :labels="[
            'transaction_number' => 'No. SR',
            'transaction_date' => 'Tgl SR',
            'customer_name' => 'Pelanggan',
            'status' => 'Status',
            'grand_total' => 'Total Transaksi',
            'paid_amount' => 'Total Pengembalian',
        ]" onEdit="onEdit" onDelete="onDelete" />
    </div>

    <script>
        const onEdit = (id, data) => {
            let url = "{{ route('sales-returns.edit', ':id') }}";
            url = url.replace(':id', id);
            window.location.href = url;
        }

        const onDelete = (id) => {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: 'Data return Penjualan ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/sales-returns/${id}`,
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
    </script>
@endsection
