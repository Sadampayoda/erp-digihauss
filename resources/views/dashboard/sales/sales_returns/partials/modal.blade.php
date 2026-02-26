<!-- Form -->

<x-modal id="sales-invoices-modal" title="Tambah Barang" onSubmit="createItems" width="w-200vh sm:max-w-4xl">
    <x-table :labels="[
        'name' => 'Nama Barang',
        'model' => 'Model',
        'Varian' => 'variant',
        'sale_price' => 'Harga Jual',
        'purchase_price' => 'Harga Beli',
    ]" :data="$items" :checkbox="true" />
</x-modal>


<script>
    const openBtn = document.getElementById('btn-sales-invoices-modal')
    const modal = document.getElementById('sales-invoices-modal')
    const setupColumn = @json($setupColumn);
    openBtn.addEventListener('click', () => {

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });




    document.getElementById('btn-cancel-modal').addEventListener('click',function(){
        setButtonLoading(false);
    })

    document.getElementById('btn-close-modal').addEventListener('click',function(){
        setButtonLoading(false);
    })
</script>
