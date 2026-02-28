<div class="flex flex-col rounded-xl bg-white">
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
        <div>
            <p class="text-xl font-medium">Informasi Sumber</p>
            <p class="text-sm font-medium text-slate-400">Informasi sumber data journal</p>
        </div>
    </div>

    <form id="sourceForm"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4
                px-2 py-1 mx-3 sm:mx-5 my-1 gap-4 mb-4">
        <div class="sm:col-span-2">
            <x-input-text name="journal_number" :value="$data[0]->journal_number" label="No. Transaksi" placeholder="Nomor Transaksi"  border_color="border-stone-300" :readonly="true"  class="rounded-sm p-1 md:p-2" />
        </div>
        <div class="sm:col-span-2">
            <x-input-text name="journal_date" :value="$data[0]->journal_date" label="Tanggal Transaksi" placeholder="Tgl Transaksi"  border_color="border-stone-300" :readonly="true"  class="rounded-sm p-1 md:p-2" />
        </div>
    </form>

</div>

