<div class="flex flex-col rounded-xl bg-white">
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
        <div>
            <p class="text-xl font-medium">Coa Konfigurasi Detail</p>
            <p class="text-sm font-medium text-slate-400">Pengaturan Coa untuk journal berbagai transaksi</p>
        </div>
    </div>

    <form id="generalForm" class="mb-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
                px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
            <div class="sm:col-span-2">
                <x-input-select name="module" label="Fitur" :required="true" :options="[
                    'advance-sale' => 'Uang Muka Penjualan',
                    'sales-invoice' => 'Invoice Penjualan',
                    'advance-payment' => 'Uang Muka Pembelian',
                    'receipt-invoice' => 'Invoice Pembelian',
                    'trade-ins' => 'Tukar Tambah',
                    'service' => 'Service Iphone'
                ]" :selected="@$data->module"
                    class="rounded-sm" />
            </div>

            <div>
                <x-input-select name="action" label="Aksi" :required="true" :options="[
                    'payment' => 'Pembayaran',
                    'receivable' => 'Piutang Usaha',
                    'advance' => 'Uang Muka Penjualan',

                    'revenue' => 'Penjualan',
                    'discount' => 'Diskon Penjualan',
                    'tax' => 'Pajak Keluaran',

                    'hpp' => 'Harga Pokok Penjualan',
                    'service' => 'Biaya Service',

                    'rounding' => 'Pembulatan',
                    'other' => 'Penyesuaian Lainnya',
                ]" :selected="@$data->action"
                    class="rounded-sm" />
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2
                px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
            <div class="sm:col-span-1">
                <x-input-select name="payment_method" :route="route('payment-methods.index')" label="Metode Pembayaran" :required="true"
                    :selected="@$data->payment_method" class="rounded-sm" />
            </div>
            <div>
                <x-input-toggle name="position" label="Posisi Jurnal" onValue="debit" offValue="credit" onLabel="Debit"
                    offLabel="Kredit" :value="$data->position ?? 'debit'" required />
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2
                px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
            <div class="sm:col-span-2">
                <x-input-select name="coa_id" :route="route('coas.index')" :params="[
                    'level' => 3,
                ]" label="Pilih Coa"
                    :required="true" :selected="@$data->coa_id" class="rounded-sm" />
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2
                px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
            <div class="sm:col-span-2">
                <x-input-toggle name="is_active" label="Status" onValue="1" offValue="0" onLabel="Aktif"
                    offLabel="Nonaktif" :value="@$data->is_active ?? 1" />
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2
                px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
            <div class="sm:col-span-2">
                <x-input-text name="description" label="Deskripsi" placeholder="Deskripsi"
                    border_color="border-stone-300" class="rounded-sm p-1 md:p-2" :value="$data->description ?? ''" />
            </div>
        </div>
    </form>
    <div class="px-2 py-3 mx-3 sm:mx-5">
        <div class="flex flex-col sm:flex-row justify-end gap-3">
            <a href="{{ route('setting-coas.index') }}"id="btn-cancel-modal"
                class="
                px-4 py-2 rounded-lg
                bg-slate-200 text-slate-700
                hover:bg-slate-300 transition
                w-full sm:w-auto
            ">
                Cancel
            </a>

            <button onclick="submit()" id="setting-coa-modal-button"
                class="
                group flex items-center justify-center gap-2
                bg-emerald-400 text-white
                px-6 py-2 rounded-lg
                transition-all duration-300
                hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                active:scale-95
                w-full sm:w-auto
            ">
                <i data-lucide="save" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                <span class="text-sm lg:text-base font-medium btn-text-setting-coa">
                    Simpan
                </span>
            </button>
        </div>
    </div>
</div>
