<div class="flex-1 flex-col rounded-xl bg-white shadow px-8 pb-4 lg:mb-2">

    {{-- HEADER --}}
    <div class="py-8 border-b border-b-slate-300">
        <p class="text-xl font-medium">Ringkasan Informasi</p>
        <p class="text-sm font-medium text-slate-400">
            Informasi pembayaran ATK
        </p>
    </div>

    {{-- FORM CONTENT --}}
    <div class="flex flex-col gap-4 py-6">
        <form id="informationForm" class="flex flex-col gap-4">

            <x-input-select name="payment_method" label="Metode Pembayaran" :route="route('payment-methods.index')"
                placeholder="Pilih Metode Pembayaran" border_color="border-stone-300" class="rounded-sm p-1 md:p-2"
                :selected="$data->payment_method ?? 1" required />

            <x-input-status :allowed="$isAppoved ? [0,1,2] : [0,1]" name="status" border_color="border-stone-300" class="rounded-sm p-1 md:p-2"
                :selected="$data->status ?? 0" />

            <x-input-text name="purpose" label="Tujuan" placeholder="Uraian tujuan"
                border_color="border-stone-300" class="rounded-sm p-1 md:p-2" :value="$data->purpose ?? ''" />

        </form>

    </div>

    {{-- SUBMIT --}}
    <div class="flex pt-4 border-t border-slate-200 gap-3">
        <button onclick="submit()" id="atk-requests-modal-button"
            class="
                group flex items-center justify-center gap-2
                bg-emerald-400 text-white
                px-6 py-3 rounded-xl
                transition-all duration-300
                hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                active:scale-95 w-full
                cursor-pointer
            ">
            <span  class="flex flex-row gap-2 text-sm lg:text-base font-medium btn-text-atk-request">
                <i data-lucide="save" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                Simpan
            </span>
        </button>
        <button type="button" id="total-atk-requests-modal-button"
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



<x-summary-form summaryFormModalButton="total-atk-requests-modal-button" summaryFormModal="total-atk-requests-modal"
    columnPriceTable="price" columnQuantityTable="quantity_requested" columnSubTotalTable="sub_total"
    columnPurchasePriceTable="price"
    columnMarginPercentageTable="margin_percentage" parentPaidAmount="grand_total"
    parentPurchasePrice="purchase_price" />

<script></script>
