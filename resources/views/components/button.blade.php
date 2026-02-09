@props(['label', 'type' => 'submit', 'name', 'id' => null, 'onClick' => null])

<button type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}" onclick="{{ $onClick }}"
    class="w-full h-13 bg-stone-800 border-none p-3 rounded-xl text-stone-50
    cursor-pointer hover:bg-stone-700 transition-all duration-300 ease-in-out flex items-center justify-center gap-2">
    <p class="btn-text">{{ $label }}</p>

    <svg class="btn-spinner hidden w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>
</button>
