@extends('template.dashboard')

@section('content')
    @php
        $editMode = isset($data);
        $image = @$data->image ?? null;
        $images = @$data->images ? json_decode($data->images, true) : [];
    @endphp
    <div class="flex flex-col w-full h-full overflow-y-auto custom-scroll gap-6 p-4 lg:p-6">

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <div class="xl:col-span-2 flex flex-col gap-6">

                <div class="bg-white rounded-xl p-5">
                    @include('dashboard.items.partials.general_form')
                </div>

                <div class="bg-white rounded-xl p-5">
                    @include('dashboard.items.partials.info_form')
                </div>

                <div class="bg-white rounded-xl p-5">
                    <form id="otherForm">
                        <h2 class="text-lg font-semibold text-slate-700 mb-1">
                            Condition & Grading
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

                            <!-- CONDITION -->
                            <div class="flex flex-wrap gap-3">

                                <label class="cursor-pointer">
                                    <input type="radio" name="condition" value="1" class="hidden peer"
                                        {{ old('condition', @$data->condition) == 1 ? 'checked' : '' }}>
                                    <div
                                        class="px-4 py-2 rounded-lg border
                                            peer-checked:border-blue-600
                                            peer-checked:bg-blue-50
                                            peer-checked:text-blue-600">
                                        New / Sealed
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="condition" value="2" class="hidden peer"
                                        {{ old('condition', @$data->condition) == 2 ? 'checked' : '' }}>
                                    <div
                                        class="px-4 py-2 rounded-lg border
                                            peer-checked:border-blue-600
                                            peer-checked:bg-blue-50
                                            peer-checked:text-blue-600">
                                        Grade A
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="condition" value="3" class="hidden peer"
                                        {{ old('condition', @$data->condition) == 3 ? 'checked' : '' }}>
                                    <div
                                        class="px-4 py-2 rounded-lg border
                                            peer-checked:border-blue-600
                                            peer-checked:bg-blue-50
                                            peer-checked:text-blue-600">
                                        Grade B
                                    </div>
                                </label>

                            </div>


                            <!-- HARGA BELI -->
                            <div>
                                <x-input-text type="number" name="purchase_price" label="Harga Beli" placeholder="6000000"
                                    :value="old('purchase_price', @$data->purchase_price)" class="rounded-sm p-2" :required="true"
                                    border_color="border-stone-300" />
                            </div>

                            <!-- HARGA JUAL -->
                            <div>
                                <x-input-text type="number" name="sale_price" label="Harga Jual" placeholder="8000000"
                                    :value="old('sale_price', @$data->sale_price)" class="rounded-sm p-2" :required="true"
                                    border_color="border-stone-300" />
                            </div>

                        </div>
                    </form>

                </div>


            </div>

            <div class="flex flex-col gap-6">
                <form id="photoForm">
                    <div class="bg-white rounded-xl p-2 mb-2">
                        <label for="image"
                            class="upload-box border-2 border-dashed rounded-lg
                                h-60 md:h-96 flex flex-col items-center justify-center
                                gap-2 text-slate-400 cursor-pointer overflow-hidden">

                            <img id="preview_main" src="{{ $image ? asset('storage/' . $image) : '' }}"
                                class="{{ $image ? '' : 'hidden' }} w-full h-full object-cover rounded-lg" />

                            <div id="placeholder_main"
                                class="{{ $image ? 'hidden' : '' }} flex flex-col items-center gap-2">
                                <span class="text-base font-medium">Upload Foto Utama</span>
                                <span class="text-xs">PNG, JPG up to 5MB</span>
                            </div>
                        </label>

                        <input type="file" name="image" id="image" class="hidden" accept="image/png, image/jpeg"
                            onchange="previewImage(event, 'preview_main', 'placeholder_main')">
                    </div>


                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        @for ($i = 1; $i <= 3; $i++)
                            @php
                                $smallImage = $images[$i - 1] ?? null;
                            @endphp

                            <div class="bg-white rounded-xl p-2">
                                <label for="images_{{ $i }}"
                                    class="border-2 border-dashed rounded-lg h-40
                                        flex flex-col items-center justify-center
                                        gap-2 text-slate-400 cursor-pointer overflow-hidden">

                                    <img id="preview_{{ $i }}"
                                        src="{{ $smallImage ? asset('storage/' . $smallImage) : '' }}"
                                        class="{{ $smallImage ? '' : 'hidden' }} w-full h-full object-cover rounded-lg" />

                                    <div id="placeholder_{{ $i }}"
                                        class="{{ $smallImage ? 'hidden' : '' }} flex flex-col items-center gap-2">
                                        <span class="text-sm">Upload</span>
                                        <span class="text-xs">PNG, JPG up to 5MB</span>
                                    </div>
                                </label>

                                <input type="file" name="images[]" id="images_{{ $i }}" class="hidden"
                                    accept="image/png, image/jpeg"
                                    onchange="previewImage(event, 'preview_{{ $i }}', 'placeholder_{{ $i }}')">
                            </div>
                        @endfor

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const editMode = @json(@$editMode);
        const id = @json(@$data->id);
        const submit = () => {
            setButtonLoading(true, 'submit-item', 'btn-text-item');
            const data = new FormData();

            ['generalForm', 'infoForm', 'otherForm', 'photoForm'].forEach(id => {
                const form = document.getElementById(id);
                if (!form) return;

                new FormData(form).forEach((value, key) => {
                    data.append(key, value);
                });
            });

            data.set('status', $('#status').is(':checked') ? 1 : 0);

            if (editMode) {
                data.append('_method', 'PUT');
            }

            const url = editMode ?
                `{{ url('items') }}/${id}` :
                `{{ route('items.store') }}`;

            $.ajax({
                url,
                method:'POST',
                data:data,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    setButtonLoading(false, 'submit-item', 'btn-text-item');
                    showAlert('Sukses', res.message, 'success', false, "{{ route('items.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'submit-item', 'btn-text-item');
                    showAlert('Gagal Menambahkan Barang', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });

        }
    </script>
@endsection
