<div class="flex-1 flex-col rounded-xl bg-white shadow px-8 pb-4 lg:mb-2">

    {{-- HEADER --}}
    <div class="py-8 border-b border-b-slate-300">
        <p class="text-xl font-medium">Ringkasan Informasi</p>
        <p class="text-sm font-medium text-slate-400">
            Informasi pembayaran invoice penjualan
        </p>
    </div>

    {{-- FORM CONTENT --}}
    <div class="flex flex-col gap-4 py-6">
        <form id="informationForm" class="flex flex-col gap-4">
            {{-- SALES (SELECT) --}}
            <x-input-select name="sales" label="Sales" :route="route('contacts.index')" placeholder="Pilih Sales"
                border_color="border-stone-300" class="rounded-sm p-1 md:p-2" :selected="$data->sales ?? null" required />

            <x-input-text type="number" name="paid_amount" label="Pembayaran Invoice" border_color="border-stone-300"
                class="rounded-sm p-1 md:p-2" :value="$data->paid_amount ?? 0" required />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
                <x-input-text type="number" name="advance_amount" label="Biaya sesudah Uang Muka" description="Potongan dari uang muka sebelumnya"
                border_color="border-stone-300" class="rounded-sm p-1 md:p-2" :value="$data->advance_amount ?? 0" readonly />
                <x-input-text type="number" name="remaining_amount" label="Sisa Pembayaran" description="Sisa yang harus di bayar Uang Muka"
                    border_color="border-stone-300" class="rounded-sm p-1 md:p-2" :value="$data->remaining_amount ?? 0" readonly />
            </div>

            <x-input-select name="payment_method" label="Metode Pembayaran" :route="route('payment-methods.index')"
                placeholder="Pilih Metode Pembayaran" border_color="border-stone-300" class="rounded-sm p-1 md:p-2"
                :selected="$data->payment_method ?? 1" required />

            <x-input-status name="status" border_color="border-stone-300" class="rounded-sm p-1 md:p-2"
                :value="$data->status ?? 0" />

            <x-input-text name="description" label="Deskripsi" placeholder="Uraian pembayaran"
                border_color="border-stone-300" class="rounded-sm p-1 md:p-2" :value="$data->description ?? ''" />

        </form>

    </div>

    {{-- SUBMIT --}}
    <div class="flex pt-4 border-t border-slate-200 gap-3">
        <button onclick="submit()" id="sales-invoices-modal-button"
            class="
                group flex items-center justify-center gap-2
                bg-emerald-400 text-white
                px-6 py-3 rounded-xl
                transition-all duration-300
                hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                active:scale-95 w-full
                cursor-pointer
            ">
            <span  class="flex flex-row gap-2 text-sm lg:text-base font-medium btn-text-sales-invoice">
                <i data-lucide="save" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                Simpan
            </span>
        </button>
        <button type="button" id="total-sales-invoices-modal-button"
            class="
                group flex items-center justify-center gap-2
                bg-blue-400 text-white
                px-6 py-3 rounded-xl
                transition-all duration-300
                hover:bg-blue-500 hover:shadow-xl hover:scale-105
                active:scale-95 w-full
                cursor-pointer
            ">
            <i data-lucide="dollar-sign" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
            <span class="text-sm lg:text-base font-medium">
                Sub Total
            </span>
        </button>
    </div>

</div>



<x-summary-form summaryFormModalButton="total-sales-invoices-modal-button" summaryFormModal="total-sales-invoices-modal"
    columnPriceTable="sale_price" columnQuantityTable="quantity" columnSubTotalTable="sub_total"
    columnServiceTable="service" columnPurchasePriceTable="purchase_price" columnMarginTable="margin"
    columnMarginPercentageTable="margin_percentage" parentPaidAmount="grand_total" />

<script></script>
