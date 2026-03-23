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
            <button onclick="syncStatus()"
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
                <i data-lucide="refresh-cw" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>

                <p class="hidden sm:block text-sm lg:text-base font-medium">
                    Syncronisasi Status
                </p>
            </button>
            <a href="{{ route('item.details.create') }}"
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
                    Tambah Detail
                </p>
            </a>
        </div>

        <x-table :data="$details" :labels="[
            'item_code' => 'Kode Barang',
            'item_name' => 'Nama Barang',
            'serial_number' => 'No Seri',
            'color' => 'Warna',
            'internal_storage' => 'Storage',
            'status' => 'Status',
            'today_responsible_name' => 'Pic Today',
            'sale_price' => 'Harga Jual',
            'purchase_price' => 'Harga beli',
            'service' => 'Harga Service',
        ]" onEdit="openEditItemModal" onStatus="item_details" />

    </div>

    <script>
        function openEditItemModal(id, data) {
            if (data.status >= 2) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Bisa Edit',
                    text: 'Barang sudah diproses atau dijual sehingga tidak dapat diedit.',
                    confirmButtonText: 'OK'
                });

                return;
            }
            let url = "{{ route('item.details.edit', ':id') }}";
            url = url.replace(':id', id);
            window.location.href = url;
        }

        const syncStatus = () => {

            $.ajax({
                url : "{{ route('item.details.index',['sync_status' => true]) }}",
                method: 'GET',
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    showAlert('Sukses', res.message, 'success', true,
                        "{{ route('item.details.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    showAlert('Gagal Syncronisasi Status', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });
        }
    </script>
@endsection
