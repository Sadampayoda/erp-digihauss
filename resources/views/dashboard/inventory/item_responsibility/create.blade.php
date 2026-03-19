@extends('template.dashboard')

@section('content')
    <div class="flex flex-col lg:flex-row w-full gap-5 overflow-hidden">
        <div class="flex flex-col w-full min-w-0 overflow-hidden gap-3 ">
            @include('dashboard.inventory.item_responsibility.partials.source_form')
            <div class="bg-white rounded-xl shadow min-w-0 pb-10 ">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
                    <div>
                        <p class="text-xl font-medium">Detail Tanggung Jawab</p>
                        <p class="text-sm font-medium text-slate-400">Modifikasi tanggung jawab dari beberapa barang unit
                        </p>
                    </div>
                    <button type="button" id="btn-item-responsibility-modal"
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
                <form id="generalForm" class="max-h-[40vh] overflow-x-auto overflow-y-auto custom-scroll">
                    <x-table-details :setupColumn="$setupColumn" />
                </form>
            </div>
        </div>
    </div>
    @include('dashboard.inventory.item_responsibility.partials.modal')

    <script>
        const editMode = @json(isset($data->id) ?? false);
        const id = @json($data->id ?? null);
        const setupColumn = @json($setupColumn);
        const submit = () => {
            setButtonLoading(true, 'item-responsibilitys-modal-button', 'btn-text-item-responsibility');
            const data = new FormData();

            ['userForm', 'assignedForm'].forEach(id => {
                const form = document.getElementById(id);
                if (!form) return;

                new FormData(form).forEach((value, key) => {
                    data.append(key, value);
                });
            });

            const items = getDetailTableData();
            items.forEach((item, index) => {
                Object.keys(item).forEach(key => {
                    data.append(`items[${index}][${key}]`, item[key]);
                });
            });


            if (editMode) {
                data.append('_method', 'PUT');
            }

            const url = editMode ?
                `{{ url('item-responsibilities') }}/${id}` :
                `{{ route('item-responsibilities.store') }}`;

            $.ajax({
                url,
                method: 'POST',
                data: data,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    setButtonLoading(false, 'item-responsibilitys-modal-button',
                        'btn-text-item-responsibility');
                    showAlert('Sukses', res.message, 'success', false,
                        "{{ route('item-responsibilities.index') }}");
                },
                error: function(err) {
                    const message = err.responseJSON.message;
                    setButtonLoading(false, 'item-responsibilitys-modal-button', 'btn-item-responsibility');
                    showAlert('Gagal Modifikasi Tanggug Jawab', message, 'errors', false);
                    resetErrors();

                    if (err.responseJSON.errors) {
                        showValidationErrors(err.responseJSON.errors);
                    }
                }
            });
        }


        const createItems = () => {
            setButtonLoading(true);
            const selected = Array.from(document.querySelectorAll('.rowCheckbox:checked'))
                .map(cb => cb.value);

            if (selected.length < 1) {
                showAlert('Terdapat Kesalahan', 'Pilih setidaknya 1 barang', 'errors', false);
                setButtonLoading(false);
            }

            console.log(selected);

            $.ajax({
                url: "{{ route('item.details.index') }}",
                type: 'GET',
                data: {
                    items: selected,
                    _token: '{{ csrf_token() }}',
                    transaction: true
                },
                success: function(res) {
                    res.data.forEach(item => {
                        item.image = item.item?.image
                        item.name = item.item?.name
                        item.item_detail_id = item.id
                        item.id = item?.item?.id
                        renderDetailRow(item, setupColumn)
                    })
                    showAlert('Sukses Tambah', res.message, 'success', false);
                    setButtonLoading(false);
                    $('#item-responsibility-modal').addClass('hidden').removeClass('flex');
                },
                error: function(err) {

                }
            });
        }

        window.onDelete = (id) => {

        }

        const itemResponsibility = @json(@$data->itemResponsibility);
        const setup = @json($setupColumn);
        console.log(itemResponsibility)
        itemResponsibility.forEach((item) => {
            const relatedItem = item?.item
            const relatedDetail = item?.item_detail
            item.detail_id = relatedItem.id
            item.id = relatedItem.id
            item.image = relatedItem.image
            item.name = relatedItem.name;
            item.serial_number = relatedDetail.serial_number;
            item.color = relatedDetail.color
            item.sale_price = relatedDetail.sale_price
            item.purchase_price = relatedDetail.purchase_price
            item.service = relatedDetail.service
            item.item_detail_id = relatedDetail.id
            renderDetailRow(item, setup)
        })
    </script>
@endsection
