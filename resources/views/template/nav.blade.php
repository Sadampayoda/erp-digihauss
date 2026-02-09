<nav class="flex items-center justify-between h-16 lg:h-20 px-4 lg:px-6  w-full overflow-x-hidden">

    <div class="flex items-center gap-3 min-w-0">
        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl bg-white shadow hover:bg-stone-100">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        <div class="flex flex-col justify-center min-w-0">
            <p class="text-sm lg:text-3xl tracking-tight text-stone-700 font-bold truncate">
                Dashboard
            </p>
            <p class="text-xs lg:text-base text-slate-400 leading-relaxed truncate">
                Hi, Administrasi Welcome Back
            </p>
        </div>
    </div>

    <div class="flex items-center gap-3 lg:gap-6 flex-shrink-0">
        <div class="hidden sm:flex rounded-xl shadow bg-stone-50 p-3">
            <a href="">
                <i data-lucide="log-out" class="w-5 h-5 text-slate-400 hover:text-slate-800 transition"></i>
            </a>
        </div>

        <div class="hidden sm:flex rounded-xl shadow bg-stone-50 p-3">
            <a href="">
                <i data-lucide="bell" class="w-5 h-5 text-slate-400 hover:text-slate-800 transition"></i>
            </a>
        </div>

        <img class="w-9 h-9 lg:w-14 lg:h-14 rounded-2xl border-2 lg:border-4 border-white shadow cursor-pointer object-cover"
            src="{{ asset('image/default-profile.jpg') }}" alt="">
    </div>
</nav>
