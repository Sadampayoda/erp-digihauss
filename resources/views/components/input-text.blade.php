@props([
    'name',
    'label' => null,
    'type' => 'text',
    'id' => false,
    'placeholder' => null,
    'class' => '',
    'error' => null,
    'value' => null,
    'onSearch' => [],
    'border_color' => null,
    'required' => false,
    'readonly' => false,
])



<div class="w-full flex flex-col justify-end gap-1">
    @if ($label)
        <label for="{{ $id ?? $name }}"
            class="text-sm md:text-base lg:text-base font-medium text-slate-700
                   dark:text-slate-600">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}" value="{{ $value }}"
        placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }}
        class="w-full border {{ $errors->has($error ?? $name) ? 'border-red-400' : ($border_color ?? 'border-stone-500') }}
                focus:border-stone-950 focus:ring-2 focus:ring-stone-300
                focus:outline-none transition-all duration-300 ease-in-out
                {{ $class }}">
    <p class="text-red-500 text-xs error-message hidden"></p>
    @error($error ?? $name)
        <p class="text-red-400 text-xs md:text-sm">{{ $message }}</p>
    @enderror
</div>
