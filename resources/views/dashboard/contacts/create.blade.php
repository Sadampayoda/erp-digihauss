@extends('template.dashboard')

@section('content')
    <div class="flex flex-col lg:flex-col w-screen lg:w-full h-full overflow-y-auto custom-scroll gap-5">
        <div class="flex flex-row w-full h-1/2 gap-3">
            <div class="bg-white rounded-xl p-6 w-2/3">
                <h3 class="text-lg font-semibold text-slate-700 mb-4">
                    General Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <x-input-text name="code" label="Contact Code" class="rounded-lg p-1 md:p-2"
                        border_color="border-stone-300" placeholder="e.g. C-00123" />

                    <x-input-select name="type" label="Type" placeholder="Select type" :options="[
                        0 => 'Customer',
                        1 => 'Vendor',
                    ]" />

                    <x-input-text name="name" label="Company / Contact Name" placeholder="e.g. Acme Corp"
                        class="rounded-lg p-1 md:p-2 md:col-span-2" border_color="border-stone-300" />

                    <x-input-text name="contact_person" label="Contact Person" placeholder="Full Name"
                        class="rounded-lg p-1 md:p-2" border_color="border-stone-300" />

                    <x-input-text name="tax_id" label="Tax ID / VAT Number" placeholder="XX-XXXXXXX"
                        class="rounded-lg p-1 md:p-2" border_color="border-stone-300" />

                </div>
            </div>

            <div class="bg-white rounded-xl p-6">
                <h3 class="text-lg font-semibold text-slate-700 mb-4">
                    Contact Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input-text name="email" type="email" label="Email Address" placeholder="contact@company.com"
                        class="rounded-lg p-1 md:p-2" border_color="border-stone-300" />

                    <x-input-text name="phone" label="Phone Number" placeholder="+62 812 xxxx xxxx"
                        class="rounded-lg p-1 md:p-2" border_color="border-stone-300" />

                    <x-input-text name="website" label="Website" placeholder="https://"
                        class="md:col-span-2 rounded-lg p-1 md:p-2" border_color="border-stone-300" />
                </div>
            </div>
        </div>

        <div class="flex-1 flex-col">
            <div class="bg-white rounded-xl p-6">
                <h3 class="text-lg font-semibold text-slate-700 mb-4">
                    Address Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <x-input-text name="address" label="Street Address" placeholder="Street, Building, etc"
                        class="rounded-lg p-1 md:p-2 md:col-span-4" border_color="border-stone-300" />

                    <x-input-text name="city" label="City" class="rounded-lg p-1 md:p-2"
                        border_color="border-stone-300" />

                    <x-input-text name="province" label="Province / State" class="rounded-lg p-1 md:p-2"
                        border_color="border-stone-300" />

                    <x-input-text name="postal_code" label="Postal Code" class="rounded-lg p-1 md:p-2"
                        border_color="border-stone-300" />

                    <x-input-select name="country" label="Country" placeholder="Select country" :options="[
                        'Indonesia' => 'Indonesia',
                        'United States' => 'United States',
                        'Singapore' => 'Singapore',
                    ]" />

                </div>
            </div>
        </div>
        <div class="flex flex-col mb-20">
            <div class="bg-white rounded-xl p-6">
                <h3 class="text-lg font-semibold text-slate-700 mb-4">
                    Financial Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                    <x-input-text type="number" name="credit_limit" label="Limit Kredit" placeholder="Limit Kredit"
                        class="rounded-lg p-1 md:p-2" border_color="border-stone-300" />

                    <x-input-text name="bank_name" label="Nama Bank" class="rounded-lg p-1 md:p-2"
                        border_color="border-stone-300" />

                    <div class="col-span-2">
                        <button
                            class="
                            group flex items-center justify-center gap-2
                            bg-emerald-400 text-white
                            px-3 py-2 rounded-md
                            sm:px-4 sm:py-2
                            lg:px-6 lg:py-3 lg:rounded-xl
                            transition-all duration-300
                            hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                            active:scale-95 w-1/2
                        ">
                            <i data-lucide="save"
                                class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                            <p class="hidden sm:block text-sm lg:text-base font-medium">
                                Simpan
                            </p>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
