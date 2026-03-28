<div class="flex flex-col rounded-xl bg-white">
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
        <div>
            <p class="text-xl font-medium">Informasi Karyawan</p>
            <p class="text-sm font-medium text-slate-400">Karyawan yang ingin mengajukan ATK</p>
        </div>
    </div>

    <form id="generalForm"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
                px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
        <div class="sm:col-span-2">
            <x-input-select name="employee_id" label="Karyawan" :required="true" :route="route('users.index')"
                :selected="@$data->employee_id" class="rounded-sm" />
        </div>

        <div>
            <x-input-text type="date" :required="true" border_color="border-stone-300" name="transaction_date"
                label="Tanggal ATK" class="rounded-sm p-1 md:p-2" :value="isset($data->transaction_date)
                    ? \Carbon\Carbon::parse($data->transaction_date)->format('Y-m-d')
                    : \Carbon\Carbon::now()->format('Y-m-d')" />
        </div>
        <div>
            <x-input-text type="date" :required="true" border_color="border-stone-300" name="requested_fulfillment_date"
                label="Tanggal Kebutuhan" class="rounded-sm p-1 md:p-2" :value="isset($data->requested_fulfillment_date)
                    ? \Carbon\Carbon::parse($data->requested_fulfillment_date)->format('Y-m-d')
                    : \Carbon\Carbon::now()->format('Y-m-d')" />
        </div>
    </form>

</div>












































































