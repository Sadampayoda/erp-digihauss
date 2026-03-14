@extends('template.dashboard')

@section('content')
    @php
        $editMode = isset($data->id);
    @endphp
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-semibold">Penilaian Kondisi Barang</h1>
                <p class="text-sm text-gray-500">
                    Catat kondisi fisik dan fungsi perangkat
                </p>
            </div>
            <button onclick="submit()" su id="submit-condition"
                class="px-4 py-2 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition">
                <span class="btn-text-condition">Simpan</span>
            </button>
        </div>


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT SIDE --}}
            <div class="space-y-6">

                {{-- IDENTIFICATION --}}
                <div class="bg-white rounded-xl p-5 space-y-4">
                    <h3 class="font-semibold text-sm">Identifikasi</h3>

                    <form id="identificationForm" class="space-y-3">
                        @if (isset($data->item_detail_id))
                            <x-input-text name="item_detail_id" label="SN / IMEI" placeholder="Barang detail"
                                class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :required="true"
                                :readonly="true" :value="@$data->detail?->item?->name . ' - ' . @$data->detail?->serial_number" />
                            <input type="hidden" name="item_detail_id" id="item_detail_id"
                                value="{{ @$data->item_detail_id }}">
                        @else
                            <x-input-select name="item_detail_id" label="SN / IMEI"
                                route="{{ route('item.details.index') }}" :params="['status' => 0]" :selected="@$data->item_detail_id" :required="true" />
                        @endif

                        <x-input-text name="battery_health" type="number" label="Kesehatan Baterai (%)"
                            placeholder="Contoh: 98" :value="@$data->battery_health" class="rounded-sm p-1 md:p-2"
                            border_color="border-stone-300" />

                    </form>
                </div>


                {{-- SALES READY --}}
                <div class="bg-white rounded-xl p-5">
                    <h3 class="font-semibold text-sm mb-3">Siap Dijual</h3>
                    <form id="readyForm" >
                        <label class="flex items-center gap-3">
                            <x-input-toggle name="ready" onValue="1" offValue="0" onLabel="Ready"
                                offLabel="Tidak" :value="@$data->ready ?? 1" />
                            <span class="text-sm text-gray-600 mt-3">
                                Aktifkan jika perangkat sudah lolos pemeriksaan
                            </span>
                        </label>
                    </form>
                </div>

            </div>


            {{-- RIGHT SIDE --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- PHYSICAL CONDITION --}}
                <div class="bg-white rounded-xl p-5">
                    <h3 class="font-semibold text-sm mb-4">Kondisi Fisik</h3>

                    <form id="physicalForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <x-input-select name="body_condition" label="Body" :options="[
                            '' => 'Pilih kondisi',
                            'excellent' => 'Sangat Baik',
                            'good' => 'Baik',
                            'fair' => 'Cukup',
                            'bad' => 'Rusak',
                        ]" :selected="@$data->body_condition" />

                        <x-input-select name="lcd_condition" label="LCD / Layar" :options="[
                            '' => 'Pilih kondisi',
                            'excellent' => 'Sangat Baik',
                            'good' => 'Baik',
                            'fair' => 'Cukup',
                            'bad' => 'Rusak',
                        ]" :selected="@$data->lcd_condition" />

                        <x-input-select name="housing_condition" label="Housing" :options="[
                            '' => 'Pilih kondisi',
                            'excellent' => 'Sangat Baik',
                            'good' => 'Baik',
                            'fair' => 'Cukup',
                            'bad' => 'Rusak',
                        ]" :selected="@$data->housing_condition" />

                    </form>
                </div>


                {{-- FUNCTIONAL CONDITION --}}
                <div class="bg-white rounded-xl p-5">
                    <h3 class="font-semibold text-sm mb-4">Kondisi Fungsi</h3>

                    <form id="functionalForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <x-input-select name="face_id_condition" label="Face ID / Touch ID" :options="[
                            '' => 'Pilih status',
                            'working' => 'Berfungsi',
                            'not_working' => 'Tidak Berfungsi',
                        ]"
                            :selected="@$data->face_id_condition" />

                        <x-input-select name="battery_condition" label="Pesan Baterai" :options="[
                            '' => 'Pilih status',
                            'normal' => 'Normal',
                            'service' => 'Perlu Service',
                            'unknown' => 'Tidak Diketahui',
                        ]"
                            :selected="@$data->battery_condition" />

                        <x-input-select name="front_camera_condition" label="Kamera Depan" :options="[
                            '' => 'Pilih status',
                            'working' => 'Berfungsi',
                            'not_working' => 'Tidak Berfungsi',
                        ]"
                            :selected="@$data->front_camera_condition" />

                        <x-input-select name="rear_camera_condition" label="Kamera Belakang" :options="[
                            '' => 'Pilih status',
                            'working' => 'Berfungsi',
                            'not_working' => 'Tidak Berfungsi',
                        ]"
                            :selected="@$data->rear_camera_condition" />

                        <x-input-select name="speaker_top_condition" label="Speaker Atas" :options="[
                            '' => 'Pilih status',
                            'clear' => 'Jernih',
                            'low' => 'Pelan',
                            'broken' => 'Rusak',
                        ]"
                            :selected="@$data->speaker_top_condition" />

                        <x-input-select name="speaker_bottom_condition" label="Speaker Bawah" :options="[
                            '' => 'Pilih status',
                            'clear' => 'Jernih',
                            'low' => 'Pelan',
                            'broken' => 'Rusak',
                        ]"
                            :selected="@$data->speaker_bottom_condition" />

                    </form>
                </div>


                {{-- TECHNICAL NOTES --}}
                <div class="bg-white rounded-xl p-5">
                    <h3 class="font-semibold text-sm mb-3">Catatan Teknis</h3>

                    <textarea name="notes" class="w-full rounded-lg p-3 border-stone-300 text-sm"
                        placeholder="Catatan tambahan mengenai kondisi perangkat..."></textarea>
                </div>

            </div>

        </div>

    </div>


    <script>
        const editMode = @json(@$editMode);
        const id = @json(@$data->id);
        const submit = () => {
            setButtonLoading(true, 'submit-condition', 'btn-text-condition');
            const data = new FormData();

            [
                'identificationForm',
                'physicalForm',
                'functionalForm',
                'readyForm'
            ].forEach(id => {
                const form = document.getElementById(id);
                if (!form) return;

                new FormData(form).forEach((value, key) => {
                    data.append(key, value);
                });
            });

            const notes = document.getElementById('notes')?.value;

            data.append('notes', notes);



            if (editMode) {
                data.append('_method', 'PUT');
            }

            const url = editMode ?
                `{{ url('item/conditions') }}/${id}` :
                `{{ route('item.conditions.store') }}`;

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
                    setButtonLoading(false, 'submit-condition', 'btn-text-condition');
                    showAlert('Sukses', res.message, 'success', false,
                        "{{ route('item.conditions.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'submit-condition', 'btn-text-condition');
                    showAlert('Gagal Modifikasi Barang Kondisi', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });

        }
    </script>
@endsection
