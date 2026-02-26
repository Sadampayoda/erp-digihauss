@props([
    'name',
    'label' => null,
    'onValue' => '1',
    'offValue' => '0',
    'onLabel' => 'On',
    'offLabel' => 'Off',
    'value' => null,
    'required' => false,
])

@php
    $currentValue = $value ?? $offValue;
    $isChecked = (string) $currentValue === (string) $onValue;
@endphp

<div class="flex flex-col gap-1 mt-2 toggle-wrapper">
    {{-- LABEL --}}
    @if ($label)
        <label class="text-sm md:text-base font-medium text-slate-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- TOGGLE --}}
    <div class="flex items-center gap-3 mt-1">
        <span class="text-sm text-slate-600">{{ $offLabel }}</span>

        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" class="sr-only peer toggle-input" {{ $isChecked ? 'checked' : '' }}
                data-on="{{ $onValue }}" data-off="{{ $offValue }}">

            <div
                class="w-11 h-6 bg-slate-300 rounded-full peer
                    peer-checked:bg-emerald-500
                    after:content-['']
                    after:absolute after:top-[2px] after:left-[2px]
                    after:bg-white after:rounded-full after:h-5 after:w-5
                    after:transition-all
                    peer-checked:after:translate-x-full">
            </div>
        </label>

        <span class="text-sm text-slate-600">{{ $onLabel }}</span>
    </div>

    {{-- HIDDEN VALUE (INI YANG DIKIRIM KE SERVER) --}}
    <input type="hidden" name="{{ $name }}" value="{{ $currentValue }}" class="toggle-value" />
</div>

<script>
    document.addEventListener('change', function(e) {
        if (!e.target.classList.contains('toggle-input')) return;

        const wrapper = e.target.closest('.toggle-wrapper');
        if (!wrapper) return;

        const hiddenInput = wrapper.querySelector('.toggle-value');
        if (!hiddenInput) return;

        hiddenInput.value = e.target.checked ?
            e.target.dataset.on :
            e.target.dataset.off;
    });
</script>
