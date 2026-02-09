@extends('template.dashboard')

@section('content')
    <div class="flex flex-col lg:flex-row w-screen lg:w-full h-screen overflow-y-auto custom-scroll gap-5">
        <div class="flex flex-col w-screen lg:h-1/2 lg:w-2/3 gap-3 ">
            @include('dashboard.sales.advance_sales.partials.general_form')
            <div class="flex flex-col rounded-xl bg-white ">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
                    <div>
                        <p class="text-xl font-medium">Detail Pesanan</p>
                        <p class="text-sm font-medium text-slate-400">Pesanan yang akan dipesan dengan pembayaran uang muka
                        </p>
                    </div>
                    <button onclick="openItemModal()"
                        class="
                            group flex items-center justify-center gap-2
                            bg-emerald-400 text-white
                            px-3 py-2 rounded-md
                            sm:px-4 sm:py-2
                            lg:px-6 lg:py-3 lg:rounded-xl
                            transition-all duration-300
                            hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                            active:scale-95 cursor-pointer
                        ">
                        <i data-lucide="plus" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                        <p class="text-sm lg:text-base font-medium">
                            Tambah Barang
                        </p>

                    </button>
                </div>
                <div class="bg-white rounded-xl shadow">
                    <div class="max-h-[40hv] md:max-h-[35vh] overflow-x-auto overflow-y-auto custom-scroll">
                        <table class="w-full min-w-[1000px] text-sm custom-scroll">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-xs sticky top-0 z-10">
                                <tr>
                                    <th class="px-6 py-4 text-left">Produk</th>
                                    <th class="px-6 py-4 text-left">Varian</th>
                                    <th class="px-6 py-4 text-right">Harga</th>
                                    <th class="px-6 py-4 text-center">Qty</th>
                                    <th class="px-6 py-4 text-center">Service</th>
                                    <th class="px-6 py-4 text-right">Subtotal</th>
                                    <th class="px-6 py-4 text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y">
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <div class="flex gap-3 items-center">
                                            <img src="{{ asset('image/default-image.jpg') }}"
                                                class="w-14 h-14 rounded-lg object-cover">

                                            <div>
                                                <p class="font-medium text-slate-800">iPhone 15 Pro</p>
                                                <p class="text-xs text-blue-500">SKU: IPH15P-256-BLU</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        Hitam, RAM 30GB, Storage 256GB
                                    </td>

                                    <td class="px-6 py-4 text-right text-slate-700">
                                        Rp 20.000.000
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <x-input-text type="number" name="quantity" value="1"
                                            class="w-16 text-center border rounded-md p-1" />
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <x-input-text type="number" name="service" value="1"
                                            class="w-16 text-center border rounded-md p-1" />
                                    </td>

                                    <td class="px-6 py-4 text-right font-medium">
                                        Rp 20.000.000
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <button class="text-red-500 hover:text-red-700">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <div class="flex gap-3 items-center">
                                            <img src="{{ asset('image/default-image.jpg') }}"
                                                class="w-14 h-14 rounded-lg object-cover">

                                            <div>
                                                <p class="font-medium text-slate-800">20W USB-C Adapter</p>
                                                <p class="text-xs text-blue-500">SKU: ACC-PWR-20W</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        Warna Putih
                                    </td>

                                    <td class="px-6 py-4 text-right text-slate-700">
                                        Rp 400.000
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <x-input-text name="quantity" type="number" value="1"
                                            class="w-16 text-center border rounded-md p-1" />
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <x-input-text name="service" type="number" value="1"
                                            class="w-16 text-center border rounded-md p-1" />
                                    </td>

                                    <td class="px-6 py-4 text-right font-medium">
                                        Rp 400.000
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <button class="text-red-500 hover:text-red-700">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('dashboard.sales.advance_sales.partials.invoice')
    </div>


    <div id="itemModal" class="fixed inset-0 z-50  hidden">
        <div id="itemBackdrop" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300"></div>

        <div class="absolute inset-x-0 bottom-0 sm:inset-0 flex items-end sm:items-center justify-center ">
            <div id="itemContent"
                class="
                bg-white w-full sm:max-w-lg
                rounded-t-2xl sm:rounded-2xl
                p-4 sm:p-6
                max-h-[90vh] overflow-y-auto
                transform translate-y-8 scale-95 opacity-0
                transition-all duration-300 ease-out
                custom-scroll
            ">

                <div class="flex justify-between items-center mb-4">
                    <p class="text-lg font-semibold">Tambah Barang</p>
                    <button onclick="closeItemModal()" class="text-slate-500 hover:text-red-500 text-xl">&times;</button>
                </div>

                <div class="flex flex-col gap-3">
                    <x-input-select name="coa" label="COA" placeholder="Pilih COA" :options="[
                        1101 => '1101 - Persediaan',
                        5101 => '5101 - HPP',
                    ]"
                        class="rounded-lg p-1 md:p-2" border_color="border-stone-300" />

                    <x-input-select name="item_id" label="Item" placeholder="Pilih Item" :options="[
                        1 => 'BRG001 - Laptop',
                        2 => 'BRG002 - Printer',
                    ]"
                        class="rounded-lg p-1 md:p-2" border_color="border-stone-300" />

                    <x-input-text name="item_code" label="Kode Item" value="BRG001" readonly class="rounded-lg p-1 md:p-2"
                        border_color="border-stone-300" />

                    <x-input-text name="item_name" label="Nama Item" value="Laptop" readonly class="rounded-lg p-1 md:p-2"
                        border_color="border-stone-300" />

                    <x-input-text type="number" name="quantity" label="Quantity" placeholder="0"
                        class="rounded-lg p-1 md:p-2" border_color="border-stone-300" />

                    <x-input-text type="number" name="sale_price" label="Harga Jual" placeholder="0"
                        class="rounded-lg p-1 md:p-2" border_color="border-stone-300" />

                    <x-input-text type="number" name="payment_amount" label="Jumlah Dibayar" placeholder="0"
                        class="rounded-lg p-1 md:p-2" border_color="border-stone-300" />

                    <x-input-text name="notes" label="Catatan" placeholder="Catatan tambahan" class="rounded-lg pb-16"
                        border_color="border-stone-300" />

                    <div class="flex gap-2 pt-2">
                        <button onclick="closeItemModal()"
                            class="flex-1 py-3 rounded-lg border text-slate-600 hover:bg-slate-100">
                            Batal
                        </button>
                        <button class="flex-1 py-3 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600">
                            Simpan
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>
        function openItemModal() {
            const modal = document.getElementById('itemModal')
            const backdrop = document.getElementById('itemBackdrop')
            const content = document.getElementById('itemContent')

            modal.classList.remove('hidden')

            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0')
                backdrop.classList.add('opacity-100')

                content.classList.remove('opacity-0', 'translate-y-8', 'scale-95')
                content.classList.add('opacity-100', 'translate-y-0', 'scale-100')
            })
        }

        function closeItemModal() {
            const modal = document.getElementById('itemModal')
            const backdrop = document.getElementById('itemBackdrop')
            const content = document.getElementById('itemContent')

            backdrop.classList.add('opacity-0')
            content.classList.add('opacity-0', 'translate-y-8', 'scale-95')

            setTimeout(() => {
                modal.classList.add('hidden')
            }, 300)
        }
    </script>
@endsection
