@extends('template.dashboard')

@section('content')
    <div class="flex flex-col lg:flex-row w-full gap-5 overflow-hidden">
        <div class="flex flex-col w-full min-w-0 overflow-hidden gap-3 ">
            @include('dashboard.master.permissions.partials.source_form')
            <div class="bg-white rounded-xl shadow min-w-0 pb-10 ">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
                    <div>
                        <p class="text-xl font-medium">Detail Hak Akses</p>
                        <p class="text-sm font-medium text-slate-400">Modifikasi Hak Akses User
                        </p>
                    </div>
                </div>
                <form id="generalForm" class="p-4 mx-3 sm:mx-5 space-y-6 max-h-[60vh] overflow-y-auto custom-scroll">
                    @foreach ($permissions as $module => $items)
                        <div class="border border-slate-200 rounded-sm p-4">
                            {{-- MODULE TITLE --}}
                            <div class="mb-3 sticky top-0 bg-white z-10 py-1">
                                <p class="text-lg font-semibold text-slate-800">
                                    {{ $moduleLabels[$module] ?? strtoupper(str_replace('.', ' › ', $module)) }}
                                </p>
                            </div>

                            {{-- ACTION LIST --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($items as $permission)
                                    <x-input-checkbox
                                        name="permissions[]"
                                        :value="$permission->name"
                                        :label="ucwords(str_replace('-', ' ', $permission->action))"
                                        :checked="in_array($permission->name, $userPermissions ?? [])" />
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>

    <script>
        const editMode = @json(isset($data->id) ?? false);
        const id = @json($data->id ?? null);
        const submit = () => {
            setButtonLoading(true, 'permissions-modal-button', 'btn-text-permission');
            const data = new FormData();

            ['generalForm', 'sourceForm'].forEach(id => {
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
                `{{ url('permissions') }}/${id}` :
                `{{ route('permissions.store') }}`;

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
                    setButtonLoading(false, 'permissions-modal-button', 'btn-text-permission');
                    showAlert('Sukses', res.message, 'success', false,
                        "{{ route('permissions.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'permissions-modal-button', 'btn-text-permission');
                    showAlert('Gagal Modifikasi Hak Akses', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });
        }
    </script>
@endsection
