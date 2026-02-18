@props([
    'name',
    'label' => null,
    'value' => 1,
    'checked' => false,
    'required' => false,
])

@php
    $isChecked = old($name, $checked) == $value;
@endphp

<div class="flex items-center gap-1">
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        {{ $isChecked ? 'checked' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-10 h-5 accent-blue-600']) }}
    >

    @if($label)
        <label for="{{ $name }}" class="text-sm text-slate-700">
            {{ $label }}
        </label>
    @endif
</div>
