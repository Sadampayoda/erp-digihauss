<div class="flex items-center justify-between mb-5">
    <h2 class="text-lg font-semibold text-slate-700">Product Identity</h2>
    <div class="flex items-center gap-2">
        <x-input-checkbox name="status" label="Aktif" :checked="@$data->status" :value="1" />
        <button onclick="submit()" id="submit-item"
            class="
                w-full sm:w-auto
                px-4 py-2 rounded-lg
                bg-emerald-500 text-white
                hover:bg-emerald-600 transition
                    ">
            <span class="btn-text-item">Simpan</span>
        </button>
    </div>
</div>

<form id="generalForm">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <x-input-text name="item_code" label="Item Code" placeholder="IP13-PRO-128-GR" class="rounded-sm p-1 md:p-2"
            border_color="border-stone-300" :required="true" :value="@$data->item_code" />

        <x-input-text name="name" label="Product Name" placeholder="iPhone 13 Pro 128GB Graphite"
            class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :required="true" :value="@$data->name" />
    </div>
</form>
