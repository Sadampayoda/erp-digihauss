@props(['labels', 'data' => []])
<div class="overflow-x-auto w-screen lg:w-full h-120">
    <table class="w-full text-sm text-left ">
        <thead class="bg-slate-100 text-slate-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">No</th>
                @foreach ($labels as $key => $value)
                    <th class="px-4 py-3">{{ $value }}</th>
                @endforeach
                <th class="px-4 py-3 text-center">Action</th>
            </tr>
        </thead>


        <tbody class="divide-y">
            @foreach ($data as $item)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3">1</td>
                    @foreach ($label as $key => $value)
                        <td class="px-4 py-3">{{ $item->$key }}</td>
                    @endforeach
                    <td class="px-4 py-3 text-center relative">
                        <button onclick="toggleMenuAction(event, this)" class="p-2 rounded-full hover:bg-slate-200">
                            <i data-lucide="more-vertical" class="w-5 h-5"></i>
                        </button>
                        <div
                            class="action-menu hidden absolute right-0 mt-2 w-44
                                bg-white rounded-xl shadow-lg border z-50 border-slate-200">
                            <a href="#"
                                class="flex items-center text-slate-700 gap-2 px-4 py-2 text-sm hover:bg-slate-100">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                Edit
                            </a>

                            <a href="#"
                                class="flex items-center text-slate-700 gap-2 px-4 py-2 text-sm hover:bg-slate-100">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                Bukti
                            </a>

                            <form method="POST">
                                <button
                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm
                                        text-red-600 hover:bg-red-50">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="flex flex-row justify-end gap-3">
    <a class="py-3 px-4 border border-slate-400 hover:border-slate-700 rounded-lg cursor-pointer">
        <p class="text-slate-500 hover:text-slate-800">
            < </p>
    </a>
    <a class="py-3 px-4 border border-slate-400 hover:border-slate-700 rounded-lg cursor-pointer">
        <p class="text-slate-500 hover:text-slate-800">1</p>
    </a>
    <a class="py-3 px-4 border border-slate-700 hover:border-slate-700 rounded-lg cursor-pointer">
        <p class="text-slate-800 hover:text-slate-800">2</p>
    </a>
    <a class="py-3 px-4 border border-slate-400 hover:border-slate-700 rounded-lg cursor-pointer">
        <p class="text-slate-500 hover:text-slate-800">3</p>
    </a>
    <a class="py-3 px-4 border border-slate-400 hover:border-slate-700 rounded-lg cursor-pointer">
        <p class="text-slate-500 hover:text-slate-800">></p>
    </a>
</div>

<script>
    function toggleMenuAction(event, button) {
        event.stopPropagation();

        const menu = button.parentElement.querySelector('.action-menu');
        document.querySelectorAll('.action-menu').forEach(el => {
            if (el !== menu) el.classList.add('hidden');
        });

        menu.classList.toggle('hidden');

        lucide.createIcons();
    }

    document.addEventListener('click', function() {
        document.querySelectorAll('.action-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    });
</script>
