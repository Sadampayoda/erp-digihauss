<div class="flex flex-col rounded-xl bg-white">
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
        <div>
            <p class="text-xl font-medium">Informasi Vendor</p>
            <p class="text-sm font-medium text-slate-400">Untuk melakukan pengembalian barang ke Vendor</p>
        </div>

    </div>

    <form id="generalForm"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
                px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
        <div class="sm:col-span-2">
            <x-input-select name="vendor" label="Vendor" :required="true" :route="route('contacts.index')" :params="['type' => 0]"
                :selected="@$data->vendor" class="rounded-sm" />
        </div>
        <x-input-select name="receipt_invoice_id" label="Pilih Transaksi RI" placeholder="Transaksi RI"
            columnShowView="transaction_number" :required="true" :route="route('receipt-invoices.index')" :selected="@$data->receipt_invoice_id" :paramsInput="['vendor']"
            :params="['status' => [2, 3, 4]]" class="rounded-sm" />
        <div>
            <x-input-text type="date" :required="true" border_color="border-stone-300" name="transaction_date"
                label="Tanggal Return Pembelian" class="rounded-sm p-1 md:p-2" :value="isset($data->transaction_date)
                    ? \Carbon\Carbon::parse($data->transaction_date)->format('Y-m-d')
                    : \Carbon\Carbon::now()->format('Y-m-d')" />
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
                    <p id="vendor-name" class="text-slate-700 font-bold text-lg sm:text-xl">
                        -
                    </p>
                    <p id="vendor-email" class="text-slate-400 text-sm sm:text-md break-all">
                        Email : - @gmail.com
                    </p>
                </div>
                <div class="flex-1">
                    <p class="text-slate-500 font-medium text-sm sm:text-lg">
                        ASAL KOTA
                    </p>
                    <p id="vendor-city" class="text-slate-400 text-sm sm:text-md">
                        -
                    </p>
                </div>

                <div class="flex-1">
                    <p class="text-slate-500 font-medium text-sm sm:text-lg">
                        ALAMAT
                    </p>
                    <p id="vendor-address" class="text-slate-400 text-sm sm:text-md">
                        -
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    const vendorId = @json(@$data->vendor);
    document.getElementById('vendor').addEventListener('change', function() {
        searchVendor(this.value)
    })


    const searchVendor = (vendorId) => {

        if (!vendorId) return;


        $.ajax({
            url: "{{ url('contacts') }}/" + vendorId,
            method: 'GET',
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                const data = res.data;

                $('#vendor-name').text(data.name ?? '-');
                $('#vendor-email').text('Email : ' + (data.email ?? '-'));
                $('#vendor-city').text(data.city ?? '-');
                $('#vendor-address').text(data.address ?? '-');
            },
            error: function(err) {
                console.log(err.responseJSON.errors);
                console.log(err);
            }
        });
    }

    searchVendor(vendorId)

    const receiptInvoiceSelect = document.getElementById('receipt_invoice_id');

    let previousReceiptInvoice = receiptInvoiceSelect.value || null;
    let isRollback = false;

    receiptInvoiceSelect.addEventListener('change', function() {
        if (isRollback) {
            isRollback = false;
            return;
        }

        const currentValue = this.value;

        if (!previousReceiptInvoice && currentValue) {
            previousReceiptInvoice = currentValue;
            return;
        }

        if (!currentValue || currentValue === previousReceiptInvoice) return;

        Swal.fire({
            title: 'Yakin menggunakan transaksi tersebut?',
            text: 'Data barang akan diambil berdasarkan sumber transaksi',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Lakukan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                clearDetailTable();
                previousReceiptInvoice = currentValue;
            } else {
                isRollback = true;
                const sourceTs = accessSelect('receipt_invoice_id');
                sourceTs.setValue(previousReceiptInvoice);
            }
        });
    });
</script>
