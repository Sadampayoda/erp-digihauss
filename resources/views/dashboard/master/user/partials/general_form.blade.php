@php
    $settingCoa = App\Models\SettingCoa::class;
@endphp
<div class="flex flex-col rounded-xl bg-white">
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
        <div>
            <p class="text-xl font-medium">Form User Detail</p>
            <p class="text-sm font-medium text-slate-400">Modifikasi User perusahaan</p>
        </div>
    </div>

    <form id="generalForm" class="mb-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
                px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
            <div class="sm:col-span-2">
                <x-input-text type="email" name="email" label="Email" :required="true" :value="@$data->email"
                    border_color="border-stone-300" class="rounded-sm p-1 md:p-2" />
            </div>

            <div>
                <x-input-text name="name" label="Nama" :required="true" :value="@$data->name"
                    border_color="border-stone-300" class="rounded-sm p-1 md:p-2" />
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
            @if ($editMode)
                <div class="sm:col-span-2">
                    <x-input-text :required="true" type="password" name="current_password" label="Password Lama"
                        placeholder="Masukkan password lama" border_color="border-stone-300"
                        class="rounded-sm p-1 md:p-2" />
                </div>
            @endif

            <div>
                <x-input-text :required="true" type="password" name="password" label="Password Baru"
                    placeholder="Masukkan password baru" border_color="border-stone-300"
                    class="rounded-sm p-1 md:p-2" />
            </div>

            <div>
                <x-input-text :required="true" type="password" name="password_confirmation"
                    label="Konfirmasi Password Baru" placeholder="Ulangi password baru" border_color="border-stone-300"
                    class="rounded-sm p-1 md:p-2" />
            </div>
        </div>
    </form>
    <div class="px-2 py-3 mx-3 sm:mx-5">
        <div class="flex flex-col sm:flex-row justify-end gap-3">
            <a href="{{ route('users.index') }}"id="btn-cancel-modal"
                class="
                px-4 py-2 rounded-lg
                bg-slate-200 text-slate-700
                hover:bg-slate-300 transition
                w-full sm:w-auto
            ">
                Cancel
            </a>

            <button onclick="submit()" id="user-modal-button"
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
                <span class="text-sm lg:text-base font-medium btn-text-user">
                    Simpan
                </span>
            </button>
        </div>
    </div>
</div>
