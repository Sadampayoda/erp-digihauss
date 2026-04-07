@extends('template.dashboard')

@section('content')
    <div class="flex flex-col lg:flex-row w-full h-400 gap-5 overflow-hidden">
        <div class="flex flex-col w-full lg:w-2/3 min-w-0 overflow-hidden gap-1 ">
            <div class="flex flex-col rounded-xl bg-white">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                    p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
                    <div>
                        <p class="text-xl font-medium">{{ $setupReport['title'] ?? ' ' }}</p>
                        <p class="text-sm font-medium text-slate-400">{{ $setupReport['description'] ?? ' ' }}</p>
                    </div>
                    <div class="flex flex-row pt-4 border-slate-200 gap-2">
                        @if ($setupReport['pdf'])
                            <button onclick="submitPdf()"
                                class="
                                group flex items-center justify-center gap-2
                                bg-red-400 text-white
                                px-6 py-2 rounded-xl
                                transition-all duration-300
                                hover:bg-red-500 hover:shadow-xl hover:scale-[1.02]
                                active:scale-95 w-full
                                cursor-pointer
                            ">
                                <span class="flex items-center gap-2 text-sm lg:text-base font-medium">
                                    <i data-lucide="file-text"
                                        class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                                    Export PDF
                                </span>
                            </button>
                        @endif

                        @if ($setupReport['excel'])
                            <button onclick="submitExcel()"
                                class="
                                group flex items-center justify-center gap-2
                                bg-emerald-500 text-white
                                px-6 py-2 rounded-xl
                                transition-all duration-300
                                hover:bg-emerald-600 hover:shadow-xl hover:scale-[1.02]
                                active:scale-95 w-full
                                cursor-pointer
                            ">
                                <span class="flex items-center gap-2 text-sm lg:text-base font-medium">
                                    <i data-lucide="file-spreadsheet"
                                        class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                                    Export Excel
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
                <x-alert action="error" />
                <form id="generalForm"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
                    px-2 py-1 mx-3 sm:mx-5 my-1 gap-8">
                    @foreach ($setupReport['filter'] as $name => $filter)
                        @switch($filter['input_type'])
                            @case('default')
                                <div>
                                    <x-input-text :type="$filter['type']" :name="$name" :label="$filter['label']" :placeholder="$filter['placeholder']"
                                        class="rounded-sm p-1 md:p-2" border_color="border-stone-300" />
                                </div>
                            @break

                            @case('select')
                                <div>
                                    <x-input-select :name="$name" :label="$filter['label']" :placeholder="$filter['placeholder']" :route="$filter['route']"
                                        :params="$filter['params']" class="rounded-sm" />
                                </div>
                            @break

                            @case('date_range')
                                <div class="sm:col-span-2 flex flex-col sm:flex-row gap-2">
                                    <div class="w-full">
                                        <x-input-text type="date" :name="$name . '_start'" :label="$filter['label']" :placeholder="$filter['placeholder']"
                                            class="rounded-sm p-1 md:p-2 w-full" border_color="border-stone-300"
                                            :value="$startDate" />
                                    </div>

                                    <span class="flex items-center justify-center px-1 pt-7 text-sm text-slate-500">
                                        S/D
                                    </span>

                                    <div class="w-full sm:mt-[26px]">
                                        <x-input-text type="date" :name="$name . '_end'" :placeholder="$filter['placeholder']"
                                            class="rounded-sm p-1 md:p-2 w-full" border_color="border-stone-300"
                                            :value="$endDate" />
                                    </div>
                                </div>
                            @break

                            @case('status')
                                <div>
                                    <x-input-status :allowed="$filter['allowed'] ?? [0, 1, 2]" :label="$filter['label']" :name="$name"
                                        border_color="border-stone-300" class="rounded-sm p-1 md:p-2" />
                                </div>
                            @break

                            @default
                        @endswitch
                    @endforeach
                    <input type="hidden" name="report_type" id="report_type" value="{{ $report_type }}">
                </form>

            </div>
        </div>
    </div>

    <script>
        const submitExcel = () => {
            const params = new URLSearchParams();

            const form = document.getElementById('generalForm');
            if (form) {
                new FormData(form).forEach((value, key) => {
                    params.append(key, value);
                });
            }

            const url = "{{ route('reports.excel') }}?" + params.toString();

            window.open(url, '_blank');
        };
    </script>
@endsection
