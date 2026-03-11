<h2 class="text-lg font-semibold text-slate-700 mb-5">Technical Specifications</h2>

<form id="infoForm">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">

        <x-input-select
            name="brand"
            label="Brand"
            route="{{ route('brands.index') }}"
            :selected="@$data->brand"
            :required="true"
        />

        <x-input-text
            name="model"
            label="Model"
            placeholder="13 Pro"
            :value="@$data->model"
            class="rounded-sm p-1 md:p-2"
            border_color="border-stone-300"
            :required="true"
        />

    </div>
</form>
