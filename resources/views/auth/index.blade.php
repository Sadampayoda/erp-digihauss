@extends('index')

@section('content')
    <div class="h-full flex flex-col lg:flex-row">
        <div class="hidden lg:flex lg:relative text-white flex-col lg:w-1/2 bg-white py-5 px-10">
            <div class="text-slate-800 flex flex-row gap-4 items-center font-medium">
                <x-image-digihaus />
                <div class="ms-15 gap-5 flex">
                    <a href="">Document</a>
                    <a href="">Tutorial</a>
                    <a href="">Pertanyaan</a>
                </div>
            </div>
            <div class="relative z-10 flex flex-col justify-center h-full">
                <p class="text-7xl text-slate-800">Hello,</p>
                <p class="text-8xl text-slate-500">Welcome</p>
                <p class="text-md text-slate-500 py-3 font-medium">Sistem ERP Manajemen Transaksi HP
                    Kelola pembelian, penjualan, stok, dan laporan secara terintegrasi dan real-time..</p>

            </div>
        </div>
        <div class="flex flex-col w-full h-full lg:w-1/2 bg-white p-5 md:p-7 lg:p-5">
            <div class="text-xl md:text-2xl lg:hidden">
                <x-image-digihaus />
            </div>

            <div class="flex flex-col w-full h-full items-center justify-center px-10 md:px-20 lg:px-40">
                <p class="flex justify-center items-center mb-1">
                    <x-image-digihaus width="40" height="10" lg_width="60" lg_height="20" />
                </p>

                <p
                    class="
                        m-0
                        flex flex-wrap items-center
                        text-sm sm:text-sm lg:text-xl
                        text-slate-400
                        py-0
                        text-center sm:text-left
                        justify-center sm:justify-start
                    ">
                    Login untuk
                    <span class="font-bold text-slate-800 px-1">keamanan</span>
                    data dan
                    <span class="font-bold text-slate-800 px-1">perusahaan</span>
                </p>



                <x-alert action="success" key="success" />


                <form class="mt-4 w-full" action="{{ route('auth.login') }}" method="POST"
                    onsubmit="handleLoading(
                        document.getElementById('submit')
                    )">
                    @csrf
                    <div class="mb-8 ">
                        <x-input-text name="email" placeholder="Email" class="rounded-xl p-3 md:p-4" />
                    </div>
                    <div class="mb-8 ">
                        <x-input-text name="password" type="password" placeholder="Password" class="rounded-xl p-3 md:p-4" />
                    </div>
                    <div class="mb-4">
                        <x-button label="Login" name="submit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
