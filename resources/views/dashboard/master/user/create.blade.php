@extends('template.dashboard')

@section('content')
    @php
        $editMode = isset($data->id);
    @endphp
    <div class="flex flex-col lg:flex-row w-full gap-5 overflow-hidden">
        <div class="flex flex-col w-full lg:w-2/3 min-w-0 overflow-hidden gap-3 ">
            @include('dashboard.master.user.partials.general_form')
        </div>
    </div>

    <script>

        const editMode = @json(@$editMode);
        const id = @json(@$data->id)

        const submit = () => {
            setButtonLoading(true, 'user-modal-button', 'btn-text-user');
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
                `{{ url('users') }}/${id}` :
                `{{ route('users.store') }}`;

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
                    setButtonLoading(false, 'user-modal-button', 'btn-text-user');
                    showAlert('Sukses', res.message, 'success', false,
                    "{{ route('users.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'user-modal-button', 'btn-text-user');
                    showAlert('Gagal Menambahkan User', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });
        }
    </script>
@endsection
