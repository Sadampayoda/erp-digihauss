<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
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

        function setButtonLoading(isLoading) {
            const btn = document.getElementById('btn-submit');
            const text = btn.querySelector('.btn-text');

            if (isLoading) {
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                text.innerHTML = `
            <span class="spinner"></span>
            <span>Menyimpan...</span>
        `;
            } else {
                btn.disabled = false;
                btn.classList.remove('opacity-70', 'cursor-not-allowed');
                text.innerHTML = 'Simpan';
            }
        }
    </script>

</body>

</html>
