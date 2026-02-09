<div class="flex-1 flex-col rounded-xl bg-white h-screen shadow px-8 mb-20 lg:mb-2">
    <div class="py-8 border-b border-b-slate-300">
        <p class="text-xl font-medium">Ringkasan Uang Muka</p>
        <p class="text-sm font-medium text-slate-400">Tampilan untuk menampilkan total pembayaran uang muka</p>
    </div>
    <div class="flex flex-col gap-2 border-b border-b-slate-300 h-50  py-3 overflow-x-auto custom-scroll ">
        <div class="flex flex-row justify-between items-center text-md font-medium">
            <p class="text-slate-400">Subtotal (2 Barang)</p>
            <p class="text-slate-600">RP.20.000.000,-</p>
        </div>
        <div class="flex flex-row justify-between items-center text-md font-medium border-b border-b-slate-300 pb-3">
            <p class="text-slate-400">Total Pajak</p>
            <p class="text-slate-600">RP.344.000,-</p>
        </div>
        <div class="flex flex-row justify-between items-center text-md font-medium ">
            <p class="text-slate-400">Total Harga Beli</p>
            <p class="text-slate-600">RP.21.000.000,-</p>
        </div>
        <div class="flex flex-row justify-between items-center text-md font-medium border-b border-b-slate-300 pb-3">
            <p class="text-slate-400">Service</p>
            <p class="text-slate-600">RP.34.000,-</p>
        </div>
        <div class="flex flex-row justify-between items-center text-lg font-bold">
            <p class="text-slate-800">Total</p>
            <p class="text-slate-800">RP.34.000.000,-</p>
        </div>
        <div
            class="flex flex-row justify-between items-center rounded-sm px-2 py-1
                    bg-emerald-50 text-emerald-600
                    text-sm md:text-base font-md">
            <div class="flex flex-row gap-1 items-center">
                <i data-lucide="ban" class="w-5 h-5"></i>
                <p>Potongan DP</p>
            </div>
            <p class="text-emerald-800">- RP.3.000.000,-</p>
        </div>
        <div class="flex flex-row justify-between items-center text-lg font-bold">
            <p class="text-slate-800">Margin total</p>
            <p class="text-slate-800">RP.50.000,-</p>
        </div>
        <div class="flex flex-row justify-between items-center text-lg font-bold">
            <p class="text-slate-800">Margin (%)</p>
            <p class="text-slate-800">34%</p>
        </div>
    </div>
    <div class="flex flex-col py-3 gap-2 ">
        <p class="text-lg font-medium">SISA TAGIHAN</p>
        <p class="text-4xl font-bold text-blue-500">Rp.17.000.000</p>
        <p class="text-md text-slate-400 pb-1 md:pb-1">Ini adalah sisa tagihan yang akan dibayar yang akan datang.
            Pembayaran lunas dilakukan ketika melakukan invoice (Sales Invoice)</p>
        <x-input-text name="description" border_color="border-stone-300" placeholder=" Uraian"
            class="pb-10 rounded-xl" />
        <div class="flex flex-row gap-2">
            <a href="{{ route('advance-sales.create') }}"
                class="
                            group flex items-center justify-center gap-2
                            bg-emerald-400 text-white
                            px-3 py-2 rounded-md
                            sm:px-4 sm:py-2
                            lg:px-6 lg:py-3 lg:rounded-xl
                            transition-all duration-300
                            hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                            active:scale-95 w-1/2
                        ">
                <i data-lucide="save" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                <p class="hidden sm:block text-sm lg:text-base font-medium">
                    Simpan
                </p>
            </a>
            <div class="flex-1">
                <x-input-status name="status" class="rounded-xl p-3" border_color="border-stone-300" />
            </div>
        </div>
    </div>
</div>
