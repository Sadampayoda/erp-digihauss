@extends('template.dashboard')

@section('content')
    @php
        $settingCoa = \App\Models\SettingCoa::class;
    @endphp
    <div class="flex flex-col rounded-xl bg-white pb-5">
        <div
            class="flex flex-col sm:flex-row sm:items-end sm:justify-between mx-3 sm:mx-5 my-1 gap-4 mb-4 border-b border-slate-100 p-3 ">

            <div>
                <p class="text-xl font-medium">
                    Closing Day
                </p>
                <p class="text-sm font-medium text-slate-400">
                    Closing day pada tanggal
                </p>
            </div>

            <button onclick="submit()" id="sycns-modal-button"
                class="group flex items-center justify-center gap-2
                    bg-emerald-400 text-white
                    px-6 py-3 rounded-xl
                    transition-all duration-300
                    hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                    active:scale-95
                    w-full sm:w-auto
                    cursor-pointer">

                <span class="flex flex-row gap-2 text-sm lg:text-base font-medium btn-text-sycn">
                    <i data-lucide="save" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                    Syncronisasi Data
                </span>
            </button>
        </div>

        <form id="generalForm" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
        px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
            <div class="sm:col-span-2">
                <x-input-text name="user_name" label="User" placeholder="User" :readonly="true"
                    border_color="border-stone-300" :value="$closing->user_name" class="rounded-sm p-1 md:p-2" />
                <input type="hidden" name="user_id" id="user_id" value="{{ $closing->user_id }}">
            </div>
            <div class="sm:col-span-1">
                <x-input-text type="date" :readonly="true" border_color="border-stone-300" name="transaction_date"
                    label="Tanggal Closing" class="rounded-sm p-1 md:p-2" :value="\Carbon\Carbon::parse($closing->transaction_date)->format('Y-m-d')" />
            </div>
        </form>

    </div>
    @include('dashboard.closings.days.partials.cash')
    @include('dashboard.closings.days.partials.summary')
    <div class="px-3 sm:px-5 mt-1">
        <div class="flex items-center justify-between mb-3">
            <p class="text-lg font-semibold">Stock Summary</p>
        </div>
        @include('dashboard.closings.days.partials.stock')
    </div>
    <div class="bg-white rounded-xl shadow min-w-0 ">
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between
        p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
            <div>
                <p class="text-xl font-medium">Detail Transaksi</p>
                <p class="text-sm font-medium text-slate-400">Transaksi yang sudah dilakukan sebelum closing
                </p>
            </div>
        </div>
        @include('dashboard.closings.days.partials.transaction')
    </div>

    <script>
        const editMode = @json($closing);
        const id = @json($closing->id);
        const submit = () => {
            setButtonLoading(true, 'sycns-modal-button', 'btn-text-sycn');
            const data = new FormData();

            ['generalForm'].forEach(id => {
                const form = document.getElementById(id);
                if (!form) return;

                new FormData(form).forEach((value, key) => {
                    data.append(key, value);
                });
            });

            if (editMode) {
                data.append('_method', 'PUT');
            }

            const url = editMode ?
                `{{ url('daily-closings') }}/${id}` :
                `{{ route('daily-closings.create') }}`;

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
                    setButtonLoading(false, 'sycns-modal-button', 'btn-text-sycn');
                    showAlert('Sukses', res.message, 'success', false,
                        "{{ route('daily-closings.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'sycns-modal-button', 'btn-text-sycn');
                    showAlert('Gagal Syncronisasi Closing Day', message, 'errors', true);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });
        }
    </script>
@endsection
