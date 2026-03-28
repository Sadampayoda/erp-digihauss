<!-- Form -->

<x-modal id="atk-requests-modal" title="Tambah Barang" onSubmit="createItems" width="w-200vh sm:max-w-4xl">
    <x-table :labels="[
        'code' => 'Kode Barang',
        'name' => 'Nama Barang',
        'unit_name' => 'Unit',
    ]" :data="$items" :checkbox="true" />
</x-modal>


<script>
    const openBtn = document.getElementById('btn-atk-requests-modal')
    const modal = document.getElementById('atk-requests-modal')
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
