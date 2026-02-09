<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="{{ asset('image/logo.jpg') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
    <style>
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 9999px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .ts-control {
            height: 40px;
        }
    </style>
    @stack('styles')
</head>

<body class="h-screen w-full overflow-hidden ">
    <div class="flex flex-row h-full w-full bg-slate-100">


        @include('template.aside')


        <div class="flex-1 lg:p-5">

            @include('template.nav')

            <section class="h-full">
                @yield('content')
            </section>
        </div>
    </div>
    @yield('script')
    <script>
        lucide.createIcons();

        function handleLoading(button) {

            button.disabled = true;
            button.classList.add('opacity-70', 'cursor-not-allowed', 'cursor-progress');
            button.classList.remove('cursor-pointer');
            button.querySelector('.btn-text').classList.add('hidden');
            button.querySelector('.btn-spinner').classList.remove('hidden');

            setTimeout(() => {
                button.querySelector('.btn-text').classList.remove('hidden');
                button.querySelector('.btn-spinner').classList.add('hidden');
                button.classList.remove('opacity-70', 'cursor-not-allowed', 'cursor-progress');
                button.classList.add('cursor-pointer');
            }, 10000);

        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function toggleMenu(menu) {
            const el = document.getElementById('menu-' + menu);
            const icon = document.getElementById('icon-' + menu);

            if (el.style.maxHeight && el.style.maxHeight !== '0px') {
                el.style.maxHeight = '0px';
                icon.classList.remove('rotate-180');
            } else {
                el.style.maxHeight = el.scrollHeight + 'px';
                icon.classList.add('rotate-180');
            }
        }

    </script>

</body>

</html>
