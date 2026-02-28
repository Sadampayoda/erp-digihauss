@extends('template.dashboard')

@section('content')
@php
    $settingCoa = \App\Models\SettingCoa::class;
@endphp
    <div class="flex flex-col lg:flex-row w-full gap-5 overflow-hidden">
        <div class="flex flex-col w-full min-w-0 overflow-hidden gap-3 ">
            @include('dashboard.journals.partials.source_form')
            <div class="bg-white rounded-xl shadow min-w-0 ">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3">
                    <div>
                        <p class="text-xl font-medium">Detail Journal</p>
                        <p class="text-sm font-medium text-slate-400">Terbit Journal dari transaksi
                        </p>
                    </div>
                </div>
                @foreach ($data as $key => $item)
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                p-4 border-b border-slate-100 mx-3 sm:mx-5 gap-3 mt-2">
                        <div>
                            <p class="text-sm font-medium text-slate-700">{{ $loop->iteration }}. Journal {{ $settingCoa::$action[$item->journal_action] ?? '' }}
                            </p>
                        </div>
                    </div>
                    @include('dashboard.journals.partials.items_form')
                @endforeach
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            refreshSource()
        });
    </script>
@endsection
