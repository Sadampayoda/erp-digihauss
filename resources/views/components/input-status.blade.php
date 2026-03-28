@props([
    'name',
    'label' => null,
    'transactionStatus' => 'transaction',
    'id' => null,
    'placeholder' => null,
    'class' => '',
    'error' => null,
    'selected' => null,
    'border_color' => null,
    'required' => false,
    'allowed' => [0, 1, 2],
])

@php
    $getStatusList = transactionStatus($transactionStatus);

    $getStatusList = collect($getStatusList)->only($allowed)->toArray();

    $isLocked = false;
    if($transactionStatus == 'transaction') {
        $isLocked = $selected !== null && $selected >= 2;
    }
@endphp


<div class="w-full flex flex-col justify-end gap-1">
    @if ($label)
        <label for="{{ $id ?? $name }}"
            class="text-sm md:text-base lg:text-base font-medium text-slate-700
                   dark:text-slate-600">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    @if ($isLocked)

        <x-input-text :name="$name" border_color="border-stone-300" class="rounded-sm p-1 md:p-2" :label="$label" :value="$getStatusList[$selected] ?? transactionStatus('transaction')[$selected]" readonly />

    <input type="hidden" name="{{ $name }}" value="{{ $selected }}">
    @else
        <select name="{{ $name }}" id="{{ $id ?? $name }}"
            class="w-full border {{ $errors->has($error ?? $name) ? 'border-red-400' : $border_color ?? 'border-stone-500' }}
    focus:border-stone-950 focus:ring-2 focus:ring-stone-300
    focus:outline-none transition-all duration-300 ease-in-out {{ $class }}">

            <option value="">{{ $placeholder ?? 'Pilih Status' }}</option>

            @foreach ($getStatusList as $key => $value)
                <option value="{{ $key }}" {{ $key == $selected ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach

        </select>
    @endif


    @error($error ?? $name)
        <p class="text-red-400 text-xs md:text-sm">{{ $message }}</p>
    @enderror
</div>
