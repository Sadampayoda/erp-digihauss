<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="{{ asset('image/logo.jpg') }}">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<body class="w-full overflow-hidden overflow-y-auto">
    <div class="flex min-h-screen w-full bg-slate-100 overflow-hidden">


        @include('template.aside')


        <div class="flex-1 flex flex-col px-6 lg:px-8 overflow-y-auto">

            @include('template.nav')

            <section class="flex flex-col gap-5">
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

        function setButtonLoading(isLoading, idSubmit = 'btn-submit-modal', classText = 'btn-text-modal') {
            const btn = document.getElementById(`${idSubmit}`);
            console.log(btn)
            const text = btn.querySelector(`.${classText}`);

            if (isLoading) {
                console.log(text, classText)
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

        const showAlert = (
            title,
            message = '',
            alert = 'success',
            reload = true,
            redirect = false
        ) => {
            Swal.fire({
                title: title,
                text: message,
                icon: alert === 'success' ? 'success' : 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (redirect) {
                        window.location.href = redirect;
                        return;
                    }

                    if (reload) {
                        location.reload();
                    }
                }
            });
        }



        const showValidationErrors = (errors) => {
            Object.keys(errors).forEach(field => {
                const wrapper = $(`[data-field="${field}"]`);
                const input = wrapper.find('input');
                const errorEl = wrapper.find('.error-message');

                input
                    .removeClass('border-stone-500')
                    .addClass('border-red-400');

                errorEl
                    .text(errors[field][0])
                    .removeClass('hidden');
            });
        }


        const resetErrors = () => {
            $('.input-field')
                .removeClass('border-red-400')
                .addClass('border-stone-500');

            $('.error-message')
                .text('')
                .addClass('hidden');
        }


        const previewImage = (event, previewId, placeholderId) => {
            const input = event.target;
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove("hidden");
                    placeholder.classList.add("hidden");
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>

</html>
