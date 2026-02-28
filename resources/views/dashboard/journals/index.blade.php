@extends('template.dashboard')

@section('content')
    <div class="flex flex-col  bg-white w-full h-full py-4 lg:py-7 px-1 lg:px-5 shadow rounded-2xl gap-5">
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
        </div>

        <x-table :data="$journals" titleEdit="Lihat" :labels="[
            'journal_number' => 'No. Transaksi',
        ]" onEdit="onEdit"  />
    </div>

    <script>
        const onEdit = (id, data) => {
            let url = "{{ route('journals.show', ':journal_number') }}";
            url = url.replace(':journal_number', data.journal_number);
            window.location.href = url;
        }

    </script>
@endsection
