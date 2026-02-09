<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('styles')
</head>

<body class="h-screen overflow-hidden">
    <div id="splash"
        class="
    fixed inset-0 bg-white z-50
    flex items-center justify-center
    transition-all duration-1000 ease-in-out
    opacity-100 scale-100
  ">
        <x-image-digihaus lg_width="100" lg_height="100" />
    </div>


    @yield('content')

    @yield('script')
    <script>
        lucide.createIcons();


            setTimeout(() => {
                const splash = document.getElementById('splash');

                splash.classList.remove('opacity-100', 'scale-100');
                splash.classList.add('opacity-0', 'scale-95');

                setTimeout(() => {
                    splash.remove();
                }, 1000);
            }, 2000);

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
    </script>

</body>

</html>
