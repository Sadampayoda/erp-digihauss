<div class="flex flex-col rounded-xl bg-white mb-10">
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
        <div>
            <p class="text-xl font-medium">Informasi Sumber</p>
            <p class="text-sm font-medium text-slate-400">Informasi sumber User</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5
    px-2 py-1 mx-3 sm:mx-5 my-1 gap-4 mb-4 items-end">
        <form id="userForm" class="sm:col-span-2">
            @if (isset($data->id))
                <x-input-text name="user_name" label="User" placeholder="User" :required="true" :readonly="true"
                    :value="@$data->name" border_color="border-stone-300" class="rounded-sm p-1 md:p-2" />
                <input type="hidden" name="user_id" id="user_id" value="{{ @$data->id }}">
            @else
                <x-input-select name="user_id" label="User" placeholder="User" :required="true" :route="route('users.index')"
                    :selected="@$data->user_id" class="rounded-sm" />
            @endif
        </form>
        <form id="assignedForm" class="sm:col-span-1">
            <x-input-text type="date" :required="true" border_color="border-stone-300" name="assigned_at"
                label="Tanggal Penugasan" class="rounded-sm p-1 md:p-2" :value="isset($data->assigned_at)
                    ? \Carbon\Carbon::parse($data->assigned_at)->format('Y-m-d')
                    : \Carbon\Carbon::now()->format('Y-m-d')" />

        </form>
        <div>
            <button onclick="submit()" id="item-responsibilitys-modal-button"
                class="
                group flex items-center justify-center gap-2
                bg-emerald-400 text-white
                px-6 py-3 rounded-xl
                transition-all duration-300
                hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                active:scale-95 w-full
                cursor-pointer
            ">
                <span class="flex flex-row gap-2 text-sm lg:text-base font-medium btn-text-item-responsibility">
                    <i data-lucide="save" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                    Simpan
                </span>
            </button>
        </div>
    </div>
