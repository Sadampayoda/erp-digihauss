<!-- Form -->

<x-modal id="item-responsibility-modal" title="Tambah Barang" onSubmit="createItems" width="w-200vh sm:max-w-4xl">
    <x-table :labels="[
        'serial_number' => 'No. Seri',
        'color' => 'Warna',
        'imei' => 'Imei',
        'sale_price' => 'Harga Jual',
        'purchase_price' => 'Harga Beli',
    ]" :data="$items" onSearch="{{ route('items.index') }}" :onParamsInput="['user_id']" :onSearchParams="['status' => [0, 1]]" onPrefix="responsibility"
        :checkbox="true" />
</x-modal>


<script>
    const openBtn = document.getElementById('btn-item-responsibility-modal')
    const modal = document.getElementById('item-responsibility-modal')
    openBtn.addEventListener('click', () => {

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });




    document.getElementById('btn-cancel-modal').addEventListener('click', function() {
        setButtonLoading(false);
    })

    document.getElementById('btn-close-modal').addEventListener('click', function() {
        setButtonLoading(false);
    })
</script>
