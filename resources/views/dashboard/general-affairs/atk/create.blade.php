@extends('template.dashboard')

@section('content')
    @php
        $editMode = isset($data->id);
        $isAppoved = auth()->user()->id == setting('appoved_atk');
    @endphp
    <div class="flex flex-col lg:flex-row w-full gap-5 overflow-hidden">
        <div class="flex flex-col w-full lg:w-2/3 min-w-0 overflow-hidden gap-3 ">
            @include('dashboard.general-affairs.atk.partials.general_form')
            <div class="bg-white rounded-xl shadow min-w-0 ">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
                    <div>
                        <p class="text-xl font-medium">Detail Barang ATK</p>
                        <p class="text-sm font-medium text-slate-400">Pesanan karyawan untuk barang habis pakai
                        </p>
                    </div>
                    <a id="btn-atk-requests-item-new-modal"
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
                            Barang Baru
                        </p>

                    </a>
                    <button type="button" id="btn-atk-requests-modal"
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
                            Tambah
                        </p>

                    </button>
                </div>
                @include('dashboard.general-affairs.atk.partials.items_form')
            </div>
        </div>
        @include('dashboard.general-affairs.atk.partials.invoice')
    </div>

    @include('dashboard.general-affairs.atk.partials.modal')
    @include('dashboard.general-affairs.atk.partials.create_items')

    <script>
        const editMode = @json(@$editMode);
        const id = @json(@$data->id);
        const isAppoved = @json($isAppoved);
        console.log(isAppoved)

        if (!isAppoved) {
            lockDetailTableColumns(
                ['quantity_approved'], {
                    mode: 'readonly'
                }
            )
        }

        const submit = () => {
            setButtonLoading(true, 'atk-requests-modal-button', 'btn-text-atk-request');
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
                `{{ url('atk-requests') }}/${id}` :
                `{{ route('atk-requests.store') }}`;

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
                    setButtonLoading(false, 'atk-requests-modal-button', 'btn-text-atk-request');
                    showAlert('Sukses', res.message, 'success', false,
                        "{{ route('atk-requests.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'atk-requests-modal-button', 'btn-text-atk-request');
                    showAlert('Gagal Modifikasi Invoice Pembelian', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });
        }
    </script>
@endsection
