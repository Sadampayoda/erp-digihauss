<!-- Form -->

<x-modal id="advance-item-modal" title="Tambah Barang" onSubmit="createItems" width="w-200vh sm:max-w-4xl">
    <x-table :labels="[
        'serial_number' => 'No. Seri',
        'color' => 'Warna',
        'imei' => 'Imei',
        'sale_price' => 'Harga Jual',
        'purchase_price' => 'Harga Beli',
    ]" :data="$items" onSearch="{{ route('items.index') }}" :onSearchParams="['status' => 1]" onPrefix="detail" :checkbox="true" />
</x-modal>


<script>
    const openBtn = document.getElementById('btn-advance-item-modal')
    const modal = document.getElementById('advance-item-modal')
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
