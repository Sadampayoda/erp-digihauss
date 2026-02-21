@extends('template.dashboard')

@section('content')
    @php
        $editMode = isset($data->id);
    @endphp
    <div class="flex flex-col lg:flex-row w-full gap-5 overflow-hidden">
        <div class="flex flex-col w-full lg:w-2/3 min-w-0 overflow-hidden gap-3 ">
            @include('dashboard.settings.coa.partials.general_form')
        </div>
    </div>

    <script>

        const editMode = @json(@$editMode);
        const id = @json(@$data->id)

        const submit = () => {
            setButtonLoading(true, 'setting-coa-modal-button', 'btn-text-setting-coa');
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
                `{{ url('setting-coas') }}/${id}` :
                `{{ route('setting-coas.store') }}`;

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
                    setButtonLoading(false, 'setting-coa-modal-button', 'btn-text-setting-coa');
                    showAlert('Sukses', res.message, 'success', false,
                    "{{ route('setting-coas.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'setting-coa-modal-button', 'btn-text-setting-coa');
                    showAlert('Gagal Menambahkan Konfigurasi Coa', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });
        }
    </script>
@endsection
