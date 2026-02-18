<div class="flex-1 flex-col rounded-xl bg-white shadow px-8 pb-4 lg:mb-2">

    {{-- HEADER --}}
    <div class="py-8 border-b border-b-slate-300">
        <p class="text-xl font-medium">Ringkasan Informasi</p>
        <p class="text-sm font-medium text-slate-400">
            Informasi pembayaran uang muka penjualan
        </p>
    </div>

    {{-- FORM CONTENT --}}
    <div class="flex flex-col gap-4 py-6">
        <form id="informationForm" >
            {{-- SALES (SELECT) --}}
            <x-input-select name="sales" label="Sales" :route="route('contacts.index')"  placeholder="Pilih Sales" border_color="border-stone-300"
                class="rounded-sm p-1 md:p-2" required />

            {{-- ADVANCE AMOUNT --}}
            <x-input-text type="number" name="advance_amount" label="Pembayaran Uang Muka" border_color="border-stone-300"
                class="rounded-sm p-1 md:p-2" value="0" required />

            {{-- PAYMENT METHOD --}}
            <x-input-select name="payment_method" label="Metode Pembayaran" selected="1"
                border_color="border-stone-300" class="rounded-sm p-1 md:p-2" :route="route('payment-methods.index')"  required />

            {{-- STATUS --}}
            <x-input-status name="status" border_color="border-stone-300" class="rounded-sm p-1 md:p-2" />

            {{-- DESCRIPTION --}}
            <x-input-text name="description" label="Deskripsi" placeholder="Uraian pembayaran"
                border_color="border-stone-300" class="rounded-sm p-1 md:p-2" />
        </form>

    </div>

    {{-- SUBMIT --}}
    <div class="flex pt-4 border-t border-slate-200 gap-3">
        <button
            onclick="submit()"
            id="advance-sale-modal-button"
            class="
                group flex items-center justify-center gap-2
                bg-emerald-400 text-white
                px-6 py-3 rounded-xl
                transition-all duration-300
                hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                active:scale-95 w-full
                cursor-pointer
            ">
            <span class="flex flex-row gap-2 text-sm lg:text-base font-medium btn-text-advance-sale">
                <i data-lucide="save" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                Simpan
            </span>
        </button>
        <button type="button"
            id="total-advance-sale-modal-button"
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



<x-summary-form summaryFormModalButton="total-advance-sale-modal-button" summaryFormModal="total-advance-sale-modal"  columnPriceTable="sale_price" columnQuantityTable="quantity" columnSubTotalTable="sub_total"
    columnServiceTable="service" columnPurchasePriceTable="purchase_price" columnMarginTable="margin"
    columnMarginPercentageTable="margin_percentage" />

<script></script>

