@extends('template.dashboard')

@section('content')
    <div class="flex flex-col  bg-white w-full h-full py-4 lg:py-7 px-1 lg:px-5 shadow rounded-2xl gap-5">
        <x-alert action="success" key="success" />
        <x-alert action="errors" key="errors" />

        <div class="flex flex-row justify-between items-center">
            <button
                class="
                    group flex items-center gap-2
                    bg-slate-800 text-stone-200
                    px-3 py-2 rounded-md
                    sm:px-4 sm:py-2
                    lg:px-6 lg:py-3 lg:rounded-xl
                    transition-all duration-300 ease-in-out
                    hover:bg-slate-700 hover:shadow-lg hover:scale-105
                    active:scale-95 cursor-pointer
                    ">
                <i data-lucide="filter" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-6"></i>

                <p class="hidden sm:block text-sm lg:text-base">
                    Filter
                </p>
            </button>
            <a id="btn-open-coa-modal"
                class="
                    group flex items-center gap-2
                    bg-emerald-400 text-white
                    px-3 py-2 rounded-md
                    sm:px-4 sm:py-2
                    lg:px-6 lg:py-3 lg:rounded-xl
                    transition-all duration-300 ease-in-out
                    hover:bg-emerald-500 hover:shadow-xl hover:scale-105
                    active:scale-95 cursor-pointer
                ">
                <i data-lucide="plus" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"></i>
                <p class="hidden sm:block text-sm lg:text-base font-medium">
                    Tambah Coa
                </p>
            </a>
        </div>

        <x-table :labels="[
            'code' => 'Kode',
            'name' => 'Nama',
            'level' => 'Level',
        ]" :data="$coas" onEdit="onEdit" onDelete="onDelete" />
    </div>

    @include('dashboard.master.coa.partials.model')
    <script>
        function onEdit(id, data) {
            document.getElementById('title-modal').innerText = 'Edit COA';

            const form = document.getElementById('form-modal');
            form.action = `/coas/${id}`;
            document.getElementById('form-method').value = 'PUT';

            form.querySelector('[name="name"]').value = data.name ?? '';
            form.querySelector('[name="description"]').value = data.description ?? '';
            form.querySelector('[name="is_postable"]').value = data.is_postable ? 1 : 0;
            form.querySelector('[name="level"]').value = data.level;

            if (window.tomSelectInstances?.type) {
                window.tomSelectInstances.type.setValue(data.type);
            }

            if (window.tomSelectInstances?.parent_id) {
                window.tomSelectInstances.parent_id.setValue(data.parent_id ?? '');
            }

            const modal = document.getElementById('coa-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }


        const onDelete = (id) => {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: 'Data Coa ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/coas/${id}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(res) {
                            showAlert('Sukses', res.message);
                        },
                        error: function(err) {
                            showAlert('Gagal', message, 'errors', true);
                        }
                    });
                }
            });
        }
    </script>
@endsection
