<div class="flex flex-col rounded-xl bg-white">
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
        <div>
            <p class="text-xl font-medium">Informasi Sumber</p>
            <p class="text-sm font-medium text-slate-400">Informasi sumber data pelanggan</p>
        </div>
    </div>

    <form id="sourceForm"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
                px-2 py-1 mx-3 sm:mx-5 my-1 gap-4">
        <div class="sm:col-span-2">
            <x-input-select name="source" label="Source (Sumber data)" placeholder="Sumber data" :required="true"
                :options="[
                    'sales-invoice' => 'Sales Invoice (Invoice Penjualan)',
                    'advance-sales' => 'Advance Sales (Uang Muka Penjualan)',
                ]" :selected="@$data->source ?? 'sales-invoice'" class="rounded-sm" />
        </div>

        <div>
            <x-input-select name="advance_sale_id" label="Pilih Transaksi AS" placeholder="Transaksi AS"
                columnShowView="transaction_number" :required="true" :route="route('advance-sales.index')" :selected="@$data->advance_sale_id"
                :paramsInput="['customer']"
                :params="['status' => [2,3]]" class="rounded-sm" />

        </div>
    </form>

</div>


<script>
    document.getElementById('source').addEventListener('change', function() {
        refreshSource()
    })

    const sourceSelect = document.getElementById('source')
    let previousSource = sourceSelect.value

    const refreshSource = () => {
        const source = document.getElementById('source').value;
        console.log(sourceSelect.value, previousSource)
        const ts = accessSelect('advance_sale_id')
        const createItems = document.getElementById('btn-sales-invoices-modal');

        if (!ts) return

        if (source === 'sales-invoice') {
            const getCountData = getDetailTableLength();
            if (getCountData > 0 && previousSource == 'advance-sales') {
                Swal.fire({
                    title: 'Yakin mengubah sumber ?',
                    text: 'Data barang akan terhapus semua !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Lakukan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearDetailTable()
                        ts.clear()
                        ts.disable()
                        createItems.disabled = false
                        previousSource = source
                        document.getElementById('remaining_amount').value = 0
                        document.getElementById('advance_amount').value = 0
                        unlockDetailTableColumns([ 'sale_price', 'service'])
                    } else {
                        const sourceTs = accessSelect('source');
                        sourceTs.setValue(previousSource)


                    }
                });

                return;
            }

            ts.clear()
            ts.disable()

            createItems.disabled = false;
            previousSource = source
        } else if (source === 'advance-sales') {
            const getCountData = getDetailTableLength();
            if (getCountData > 0 && previousSource == 'sales-invoice') {
                Swal.fire({
                    title: 'Yakin mengubah sumber ?',
                    text: 'Data barang akan terhapus semua !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Lakukan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearDetailTable()
                        ts.enable()
                        createItems.disabled = false
                        previousSource = source
                        document.getElementById('remaining_amount').value = 0
                        document.getElementById('advance_amount').value = 0
                    } else {
                        const sourceTs = accessSelect('source');
                        sourceTs.setValue(previousSource)
                    }
                });

                return;
            }
            createItems.disabled = true;
            ts.enable()
            previousSource = source
        }
    }

    document.getElementById('advance_sale_id').addEventListener('change', function() {
        const advanceSaleId = this.value

        if (!advanceSaleId) return;
        Swal.fire({
            title: 'Yakin menggunakan transaksi tersebut?',
            text: 'Data barang akan di ambil berdasarkan sumber transaksi ',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Lakukan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: `/advance-sales/${advanceSaleId}`,
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'GET'
                    },
                    success: function(res) {
                        showAlert('Sukses', res.message, 'success', false);
                        clearDetailTable();
                        const data = res.data
                        console.log(data);

                        const advanceAmount = document.getElementById('advance_amount')
                        if (data.advance_amount) {
                            advanceAmount.value = data.advance_amount
                        }

                        data.items.forEach((item) => {
                            item.advance_sale_items_id = item.id
                            item.id = item?.item?.id
                            item.image = item?.item?.image
                            item.name = item.item_name;
                            item.variant = item.item?.variant
                            item.quantity = item.outstanding_quantity
                            renderDetailRow(item, setup)
                        });

                        summaryForm();


                        const remainingAmount = document.getElementById(
                            'remaining_amount')
                        if (data.remaining_amount) {
                            remainingAmount.value = data.remaining_amount - data.advance_amount;
                        }

                        lockDetailTableColumns(
                            ['sale_price', 'service'], {
                                mode: 'readonly'
                            }
                        )
                    },
                    error: function(err) {
                        showAlert('Gagal', message, 'errors', true);
                    }
                });
            } else {
                const ts = accessSelect('advance_sale_id')
                ts.clear()
            }
        });
    })
</script>
