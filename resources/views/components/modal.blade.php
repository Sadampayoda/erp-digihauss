@props([
    'id' => 'modal',
    'title' => 'Tambah Brand',
    'onSubmit',
    'width' => 'w-[95%] sm:max-w-md'
])

<div id="{{ $id ?? 'modal' }}" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 ">

    <div
        class="
        {{ $width }}
        bg-white
        rounded-2xl
        p-4 sm:p-6
        shadow-xl
        max-h-[90vh] overflow-y-auto
        transform transition-all duration-300
    ">

        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base sm:text-lg font-semibold text-slate-700" id="title-modal">
                {{ $title ?? ' ' }}
            </h2>

            <button type="button" id="btn-close-modal" class="text-slate-400 hover:text-slate-600 text-xl cursor-pointer" data-modal-close>
                ✕
            </button>
        </div>

        {{ $slot }}
        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-3">
            <button type="button" id="btn-cancel-modal"
                class="
                        w-full sm:w-auto
                        px-4 py-2 rounded-lg
                        bg-slate-200 text-slate-700
                        hover:bg-slate-300 transition
                    ">
                Batal
            </button>

            <button
                onclick="{{ $onSubmit }}()"
                id="btn-submit-modal"
                class="
                        w-full sm:w-auto
                        px-4 py-2 rounded-lg
                        bg-emerald-500 text-white
                        hover:bg-emerald-600 transition
                    ">
                <span class="btn-text-modal">Simpan</span>
            </button>
        </div>
    </div>
</div>

<script>
    {
        const idModal = @json($id)

        const closeBtn = document.getElementById('btn-close-modal');
        const cancelBtn = document.getElementById('btn-cancel-modal');
        const modal = document.getElementById(`${idModal}`);


        [closeBtn, cancelBtn].forEach(btn => {
            btn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        });
    }
</script>
