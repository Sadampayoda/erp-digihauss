@extends('template.dashboard')

@section('content')
    <div class="flex flex-col rounded-xl bg-white pb-5">
        <div
            class="flex flex-col sm:flex-row sm:items-end sm:justify-between mx-3 sm:mx-5 my-1 gap-4 mb-4 border-b border-slate-100 p-3 ">

            <div>
                <p class="text-xl font-medium">
                    Setting {{ \Illuminate\Support\Str::title($title) }}
                </p>
                <p class="text-sm font-medium text-slate-400">
                    Pengaturan pada aplikasi
                </p>
            </div>

            <button onclick="submit()" id="settings-modal-button"
                class="group flex items-center justify-center gap-2
                    bg-emerald-400 text-white
                    px-6 py-3 rounded-xl
                    transition-all duration-300
                    hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                    active:scale-95
                    w-full sm:w-auto
                    cursor-pointer">

                <span class="flex flex-row gap-2 text-sm lg:text-base font-medium btn-text-setting">
                    <i data-lucide="save" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                    Simpan
                </span>
            </button>
        </div>

        <form id="generalForm"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
        px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
            @csrf

            @foreach ($settings as $setting)
                <div class="w-full">

                    {{-- SELECT --}}
                    @if ($setting->type === 'select')
                        <x-input-select name="settings[{{ $setting->key }}]" label="{{ $setting->label }}" :options="$setting->options ?? []"
                            :selected="$setting->value" class="rounded-sm" :description="$setting->description" />
                    @elseif ($setting->type === 'boolean')
                        <x-input-select name="settings[{{ $setting->key }}]" label="{{ $setting->label }}" :options="[
                            1 => 'Aktif',
                            0 => 'Tidak Aktif',
                        ]"
                            :selected="$setting->value" class="rounded-sm" :description="$setting->description" />
                    @else
                        <x-input-text name="settings[{{ $setting->key }}]" label="{{ $setting->label }}"
                            type="{{ $setting->type }}" :value="$setting->value" :placeholder="$setting->label" :description="$setting->description"
                            border_color="border-stone-300" class="rounded-sm p-1 md:p-2" />
                    @endif

                </div>
            @endforeach

        </form>

    </div>

    <script>
        const submit = () => {
            setButtonLoading(true, 'settings-modal-button', 'btn-text-setting');
            const data = new FormData();

            ['generalForm'].forEach(id => {
                const form = document.getElementById(id);
                if (!form) return;

                new FormData(form).forEach((value, key) => {
                    data.append(key, value);
                });
            });

            const url = `{{ route('settings.store') }}`;

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
                    setButtonLoading(false, 'settings-modal-button', 'btn-text-setting');
                    showAlert('Sukses', res.message, 'success', false,
                        "{{ route('settings.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'settings-modal-button', 'btn-text-setting');
                    showAlert('Gagal Modifikasi Setting', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });
        }
    </script>
@endsection
