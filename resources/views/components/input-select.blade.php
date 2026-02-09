@props([
    'name',
    'label' => null,
    'id' => null,
    'placeholder' => null,
    'class' => '',
    'error' => null,
    'selected' => null,
    'options' => [],
])



<div class="w-full flex flex-col justify-end gap-1 py-1">
    @if ($label)
        <label for="{{ $id ?? $name }}"
            class="text-sm md:text-base lg:text-base font-medium text-slate-700
                   dark:text-slate-600">
            {{ $label }}
        </label>
    @endif
    <select name="{{ $name }}" id="{{ $id ?? $name }}">
        <option class="p-4" {{ $selected ? 'selected' : '' }}>{{ $placeholder }}</option>
        @foreach ($options as $key => $value)
            <option {{ $key == $selected ? 'selected' : '' }}>{{ $value }}</option>
        @endforeach
    </select>

    @error($error ?? $name)
        <p class="text-red-400 text-xs md:text-sm">{{ $message }}</p>
    @enderror
</div>

<script>
    {
        const selectId = @json($name ?? $id);
        const placeholder = @json($placeholder);
        new TomSelect(`#${selectId}`, {
            sortField: 'text',
            hideSelected: false,
            plugins: {
                'dropdown_header': {
                    title: placeholder ?? 'Select',
                }
            }
        });
    }
</script>
