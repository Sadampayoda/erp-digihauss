<h2 class="text-lg font-semibold text-slate-700 mb-5">Technical Specifications</h2>

<form id="infoForm">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <x-input-select name="brand" label="Brand" route="{{ route('brands.index') }}" :selected="@$data->brand"
            :required="true" />

        <x-input-select name="series" label="Series" route="{{ route('series.index') }}" :selected="@$data->series"
            :required="true" />

        <x-input-text name="model" label="Model" placeholder="13 Pro" :value="@$data->model" class="rounded-sm p-1 md:p-2"
            border_color="border-stone-300" :required="true" />

        <x-input-text type="number" name="storage_gb" label="Storage" placeholder="128" :value="@$data->storage_gb"
            class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :required="true" />

        <x-input-text type="number" name="ram_gb" label="RAM" placeholder="6" :value="@$data->ram_gb"
            class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :required="true" />

        <x-input-text name="color" label="Color" placeholder="Silver" :value="@$data->color"
            class="rounded-sm p-1 md:p-2" border_color="border-stone-300" :required="true" />

        <x-input-text name="network" label="Network Status" placeholder="Factory Unlocked" :value="@$data->network"
            class="rounded-sm p-1 md:p-2" border_color="border-stone-300" />

        <x-input-text name="region" label="Region Spec" placeholder="ID/A (Indonesia)" :value="@$data->region"
            class="rounded-sm p-1 md:p-2" border_color="border-stone-300" />

        <x-input-text name="variant" label="Variant Code" placeholder="A2638" :value="@$data->variant"
            class="rounded-sm p-1 md:p-2" border_color="border-stone-300" />

    </div>
</form>
