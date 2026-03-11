@extends('template.dashboard')

@section('content')
    @php
        $editMode = isset($data);
    @endphp


    <div class="space-y-6">

        {{-- ITEM SELECTION --}}
        <div class="bg-white rounded-xl p-5">
            <h3 class="flex items-center gap-2 text-sm font-semibold mb-4">
                <i data-lucide="package" class="w-4 h-4"></i>
                Item Selection
            </h3>

            <div class="grid grid-cols-[1fr_auto] gap-3 items-end">

                <!-- FORM -->
                <form id="generalForm">
                    
                    <x-input-select name="item_detail_id" label="Model Name (Parent Item)" route="{{ route('items.index') }}"
                        :selected="@$data->item_detail_id" :required="true" />
                </form>

                <!-- BUTTON -->
                <div class="pb-7">
                    <button onclick="submit()" su id="submit-item"
                        class="px-4 py-2 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition">
                        <span class="btn-text-item">Simpan</span>
                    </button>
                </div>

            </div>
        </div>

        {{-- SPECIFICATIONS --}}
        <div class="bg-white  rounded-xl p-5">
            <h3 class="flex items-center gap-2 text-sm font-semibold mb-4">
                <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                Specifications
            </h3>

            <form id="specificaForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <x-input-text name="color" label="Color" placeholder="Silver" class="rounded-sm p-1 md:p-2"
                    border_color="border-stone-300" :required="true" :value="@$data->color" />

                <x-input-text type="number" name="internal_storage" label="Internal Storage" placeholder="128GB"
                    class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :required="true" :value="@$data->internal_storage" />

                <x-input-text type="number" name="network" label="Network" placeholder="5G" class="rounded-sm p-1 md:p-2"
                    border_color="border-stone-300" :value="@$data->network" :required="true" />

                <x-input-text name="region" label="Region" placeholder="e.g LL/A, ID/A" class="rounded-sm p-1 md:p-2"
                    border_color="border-stone-300" :value="@$data->region" />

            </form>
        </div>


        {{-- DEVICE IDENTITY + TYPE --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- DEVICE IDENTITY --}}
            <div class="bg-white rounded-xl p-5">
                <h3 class="flex items-center gap-2 text-sm font-semibold mb-4">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Device Identity
                </h3>

                <form id="deviceForm" class="space-y-4">

                    <x-input-text name="imei" label="IMEI Number" placeholder="Enter 15-digit IMEI"
                        class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :required="true"
                        :value="@$data->imei" />

                    <x-input-text name="serial_number" label="Serial Number" placeholder="Enter Serial Number"
                        class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :required="true"
                        :value="@$data->serial_number" />

                </form>
            </div>


            {{-- TYPE & CONDITION --}}
            <div class="bg-white rounded-xl p-5">
                <h3 class="flex items-center gap-2 text-sm font-semibold mb-4">
                    <i data-lucide="check-square" class="w-4 h-4"></i>
                    Type & Condition
                </h3>

                <form id="typeForm" class="space-y-4">

                    <div>
                        <label class="text-sm font-medium">Item Condition</label>

                        <div class="flex gap-4 mt-2">

                            <label class="flex items-center gap-2">
                                <input type="radio" name="type" value="new"
                                    {{ @$data->type == 'new' ? 'checked' : '' }}>
                                New
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="radio" name="type" value="second"
                                    {{ @$data->type == 'second' ? 'checked' : '' }}>
                                Second
                            </label>

                        </div>
                    </div>


                    <div>
                        <label class="text-sm font-medium">Completeness</label>

                        <div class="flex gap-6 mt-2">

                            <x-input-checkbox name="has_box" value="1" label="Box" :checked="@$data->has_box" />

                            <x-input-checkbox name="has_cable" value="1" label="Cable" :checked="@$data->has_cable" />

                            <x-input-checkbox name="has_adapter" value="1" label="Adapter" :checked="@$data->has_adapter" />

                        </div>
                    </div>

                </form>
            </div>

        </div>


        {{-- PRICING --}}
        <div class="bg-white rounded-xl p-5">
            <h3 class="flex items-center gap-2 text-sm font-semibold mb-4">
                <i data-lucide="credit-card" class="w-4 h-4"></i>
                Pricing
            </h3>

            <form id="priceForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <x-input-text type="number" name="purchase_price" label="Harga Beli" placeholder="IDR 0"
                    class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :value="@$data->purchase_price" />

                <x-input-text type="number" name="sale_price" label="Harga Jual" placeholder="IDR 0"
                    class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :value="@$data->sale_price" />

                <x-input-text type="number" name="service" label="Harga Service" placeholder="IDR 0"
                    class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :value="@$data->service" />

            </form>
        </div>


        {{-- SUPPLIER --}}
        <div class="bg-white rounded-xl p-5">
            <h3 class="flex items-center gap-2 text-sm font-semibold mb-4">
                <i data-lucide="truck" class="w-4 h-4"></i>
                Supplier & Logistics
            </h3>

            <form id="supplierForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <x-input-text name="distributor" label="Distributor" placeholder="Enter distributor name"
                    class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :value="@$data->distributor" />

                <x-input-text name="purchase_date" label="Purchase Date" type="date" class="rounded-sm p-1 md:p-2"
                    border_color="border-stone-300" :value="@$data->purchase_date" />

                <x-input-text name="sale_date" label="Sale Date" type="date" class="rounded-sm p-1 md:p-2"
                    border_color="border-stone-300" :value="@$data->sale_date" />

            </form>
        </div>


    </div>


    <script>
        const editMode = @json(@$editMode);
        const id = @json(@$data->id);
        const submit = () => {
            setButtonLoading(true, 'submit-item', 'btn-text-item');
            const data = new FormData();

            [
                'generalForm',
                'specificaForm',
                'deviceForm',
                'typeForm',
                'priceForm',
                'supplierForm'
            ].forEach(id => {
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
                `{{ url('item/details') }}/${id}` :
                `{{ route('item.details.store') }}`;

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
                    setButtonLoading(false, 'submit-item', 'btn-text-item');
                    showAlert('Sukses', res.message, 'success', false, "{{ route('item.details.index') }}");
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
