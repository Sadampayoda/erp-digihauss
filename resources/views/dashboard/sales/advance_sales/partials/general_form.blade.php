<div class="flex flex-col rounded-xl bg-white">
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
        <div>
            <p class="text-xl font-medium">Informasi Pelanggan</p>
            <p class="text-sm font-medium text-slate-400">Pelanggan untuk melakukan uang muka penjualan</p>
        </div>

        <a href="{{ route('contacts.create') }}"
            class="
                            group flex items-center justify-center gap-2
                            bg-emerald-400 text-white
                            px-3 py-2 rounded-md
                            sm:px-4 sm:py-2
                            lg:px-6 lg:py-3 lg:rounded-xl
                            transition-all duration-300
                            hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                            active:scale-95
                        ">
            <i data-lucide="plus" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
            <p class="hidden sm:block text-sm lg:text-base font-medium">
                Tambah Pelanggan
            </p>

        </a>
    </div>

    <form id="generalForm"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
                px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
        <div class="sm:col-span-2">
            <x-input-select name="customer" label="Pelanggan" :required="true"
                :route="route('contacts.index')" :params="['type' => 0]" class="rounded-sm" />
        </div>

        <div>
            <x-input-text type="date" :required="true" border_color="border-stone-300" name="transaction_date"
                label="Tanggal Uang Muka" class="rounded-sm p-1 md:p-2" :value="\Carbon\Carbon::now()->format('Y-m-d')" />
        </div>
    </form>


    <div class="p-3 sm:p-4 mx-2 sm:mx-3">
        <div
            class="w-full rounded-sm p-4 sm:p-5
                    bg-blue-50 text-blue-800 border border-blue-300
                    text-sm md:text-base">

            <div class="flex flex-col sm:flex-row gap-4 items-start">
                <img class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover"
                    src="{{ asset('image/default-profile.jpg') }}" alt="default">

                <div class="flex flex-col gap-1 sm:border-e sm:pe-4 border-slate-300">
                    <p id="customer-name" class="text-slate-700 font-bold text-lg sm:text-xl">
                        -
                    </p>
                    <p id="customer-email" class="text-slate-400 text-sm sm:text-md break-all">
                        Email : - @gmail.com
                    </p>
                </div>
                <div class="flex-1">
                    <p class="text-slate-500 font-medium text-sm sm:text-lg">
                        ASAL KOTA
                    </p>
                    <p id="customer-city" class="text-slate-400 text-sm sm:text-md">
                        -
                    </p>
                </div>

                <div class="flex-1">
                    <p class="text-slate-500 font-medium text-sm sm:text-lg">
                        ALAMAT
                    </p>
                    <p id="customer-address" class="text-slate-400 text-sm sm:text-md">
                        -
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('customer').addEventListener('change', function() {
        const customerId = this.value;
        if (!customerId) return;


        $.ajax({
            url: "{{ url('contacts') }}/" + customerId,
            method: 'GET',
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                const data = res.data;

                $('#customer-name').text(data.name ?? '-');
                $('#customer-email').text('Email : ' + (data.email ?? '-'));
                $('#customer-city').text(data.city ?? '-');
                $('#customer-address').text(data.address ?? '-');
            },
            error: function(err) {
                console.log(err.responseJSON.errors);
                console.log(err);
            }
        });
    })
</script>
