@extends('template.dashboard')

@section('content')
    <div class="flex-1 h-90 bg-white shadow rounded-xl p-8">
        <p class="text-2xl font-semibold">
            Security Detail
        </p>
        <div class="flex flex-row py-7 gap-10">
            <img src="{{ asset('assets/default-profile.jpg') }}" alt=""
                class="w-30 h-30 rounded-full border-4 border-white shadow cursor-pointer object-cover">
            <div class="flex flex-col justify-between py-3">
                <p class="text-xl font-medium">Administrator</p>
                <div>
                    <p class="text-md text-slate-400">ID:</p>
                    <p class="text-md font-medium">ID: 124</p>
                </div>
            </div>
            <div class="flex flex-col justify-end py-3">
                <p class="text-md text-slate-400">Number:</p>
                <p class="text-md font-medium">(+62) 85232137731</p>
            </div>
            <div class="flex flex-col justify-end py-3">
                <p class="text-md text-slate-400">Email:</p>
                <p class="text-md font-medium">sadampayodaa@gmail.com</p>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-6">
            <div class="flex items-center gap-4 bg-stone-50 rounded-xl p-6 shadow">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                    <i data-lucide="calendar-check" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div class="flex flex-col">
                    <p class="text-2xl font-semibold">26</p>
                    <p class="text-sm text-slate-400">Total Absensi</p>
                </div>
            </div>

            <div class="flex items-center gap-4 bg-stone-50 rounded-xl p-6 shadow">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                    <i data-lucide="clock-alert" class="w-5 h-5 text-red-600"></i>
                </div>
                <div class="flex flex-col">
                    <p class="text-2xl font-semibold">5</p>
                    <p class="text-sm text-slate-400">Total Telat</p>
                </div>
            </div>

            <div class="flex items-center gap-4 bg-stone-50 rounded-xl p-6 shadow">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-100">
                    <i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i>
                </div>
                <div class="flex flex-col">
                    <p class="text-2xl font-semibold">3</p>
                    <p class="text-sm text-slate-400">Total Toleransi</p>
                </div>
            </div>

            <div class="flex items-center gap-4 bg-stone-50 rounded-xl p-6 shadow">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex flex-col">
                    <p class="text-2xl font-semibold">18</p>
                    <p class="text-sm text-slate-400">Total Tepat Waktu</p>
                </div>
            </div>
        </div>
    </div>
@endsection
