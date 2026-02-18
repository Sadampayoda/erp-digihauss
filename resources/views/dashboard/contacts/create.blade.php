@extends('template.dashboard')

@section('content')
    @php
        $editMode = isset($data->id);
    @endphp
    <div class="flex flex-col lg:flex-col w-screen lg:w-full h-full overflow-y-auto custom-scroll gap-5">
        <div class="flex flex-row w-full h-1/2 gap-3">
            <div class="bg-white rounded-xl p-6 w-2/3">
                <h3 class="text-lg font-semibold text-slate-700 mb-4">
                    General Information
                </h3>
                <form id="generalForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  name="code" label="Contact Code" value="{{ $editMode ? $data->code : old('code') }}"
                            placeholder="e.g. C-00123" />

                        <x-input-select class="rounded-sm p-1 md:p-2" name="type" label="Type" :selected="$editMode ? $data->type : old('type')" :options="[
                            0 => 'Customer',
                            1 => 'Vendor',
                        ]" />

                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  name="name" label="Company / Contact Name"
                            value="{{ $editMode ? $data->name : old('name') }}"/>

                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  name="contact_person" label="Contact Person"
                            value="{{ $editMode ? $data->contact_person : old('contact_person') }}" />

                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  name="tax_id" label="Tax ID / VAT Number"
                            value="{{ $editMode ? $data->tax_id : old('tax_id') }}" />

                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl p-6">
                <h3 class="text-lg font-semibold text-slate-700 mb-4">
                    Contact Details
                </h3>

                <form id="contactForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  name="email" type="email" label="Email Address"
                            value="{{ $editMode ? $data->email : old('email') }}" />

                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  name="phone" label="Phone Number"
                            value="{{ $editMode ? $data->phone : old('phone') }}" />
                    </div>
                </form>
            </div>
        </div>

        <div class="flex-1 flex-col">
            <div class="bg-white rounded-xl p-6">
                <h3 class="text-lg font-semibold text-slate-700 mb-4">
                    Address Information
                </h3>

                <form id="addressForm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  name="address" label="Street Address"
                            value="{{ $editMode ? $data->address : old('address') }}" />

                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  name="city" label="City" value="{{ $editMode ? $data->city : old('city') }}" />

                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  name="province" label="Province / State"
                            value="{{ $editMode ? $data->province : old('province') }}" />

                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  name="postal_code" label="Postal Code"
                            value="{{ $editMode ? $data->postal_code : old('postal_code') }}" />

                        <x-input-select class="rounded-sm p-1 md:p-2" name="country" label="Country" :selected="$editMode ? $data->country : old('country')" :options="[
                            'Indonesia' => 'Indonesia',
                            'United States' => 'United States',
                            'Singapore' => 'Singapore',
                        ]" />
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-col mb-20">
            <div class="bg-white rounded-xl p-6">
                <h3 class="text-lg font-semibold text-slate-700 mb-4">
                    Financial Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <form id="financeForm">
                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  type="number" name="credit_limit" label="Limit Kredit"
                            value="{{ $editMode ? $data->credit_limit : old('credit_limit') }}" />

                        <x-input-text class="rounded-sm p-1 md:p-2" border_color="border-stone-300"  name="bank_name" label="Nama Bank"
                            value="{{ $editMode ? $data->bank_name : old('bank_name') }}" />

                    </form>

                    <div class="col-span-2">
                        <button onclick="submit()" id="submit-contact"
                            class="
                            w-full sm:w-auto
                            px-4 py-2 rounded-lg
                            bg-emerald-500 text-white
                            hover:bg-emerald-600 transition
                    ">
                            <span class="btn-text-contact">Simpan</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        const editMode = @json(@$editMode);
        const id = @json(@$data->id);
        const submit = () => {
            setButtonLoading(true, 'submit-contact', 'btn-text-contact');
            const data = new FormData();

            ['generalForm', 'addressForm', 'contactForm', 'financeForm'].forEach(id => {
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
                `{{ url('contacts') }}/${id}` :
                `{{ route('contacts.store') }}`;

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
                    setButtonLoading(false, 'submit-contact', 'btn-text-contact');
                    showAlert('Sukses', res.message, 'success', false, "{{ route('contacts.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'submit-contact', 'btn-text-contact');
                    showAlert('Gagal Menambahkan Kontak', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });

        }
    </script>
@endsection
