@props([
    'name',
    'label' => null,
    'id' => false,
    'placeholder' => null,
    'class' => '',
    'error' => null,
    'selected' => null,
    'border_color' => null,
])

@php
    $getStatusList = transactionStatus('transaction');
@endphp


<div class="w-full flex flex-col justify-end gap-1">
    @if ($label)
        <label for="{{ $id ?? $name }}"
            class="text-sm md:text-base lg:text-base font-medium text-slate-700
                   dark:text-slate-600">
            {{ $label }}
        </label>
    @endif

    <select name="{{ $name }}" id="{{ $id ?? $name }}"
        class="w-full border {{ $errors->has($error ?? $name) ? 'border-red-400' : ($border_color ?? 'border-stone-500') }}
                focus:border-stone-950 focus:ring-2 focus:ring-stone-300
                focus:outline-none transition-all duration-300 ease-in-out
                {{ $class }}">
        <option {{ $selected ? 'selected' : '' }}>{{ $placeholder ?? 'Pilih Status'}}</option>
        @foreach ($getStatusList as $key => $value)
            <option {{ $key == $selected ? 'selected' : ''}} value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>

    @error($error ?? $name)
        <p class="text-red-400 text-xs md:text-sm">{{ $message }}</p>
    @enderror
</div>
