@props([
    'name',
    'label' => null,
    'id' => null,
    'placeholder' => null,
    'class' => '',
    'error' => null,
    'selected' => null,
    'options' => [],
    'route' => null,
    'params' => null,
    'required' => false,
])



<div class="w-full flex flex-col justify-end gap-1 py-1">
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
    <select name="{{ $name }}" id="{{ $id ?? $name }}" {{ $required ? 'required' : '' }}>
        <option class="p-4" {{ $selected ? 'selected' : '' }}>{{ $placeholder }}</option>
        @foreach ($options as $key => $value)
            <option value="{{ $key }}" {{ (string) $key === (string) $selected ? 'selected' : '' }}>
                {{ $value }}
            </option>
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
        const route = @json($route);
        const params = @json($params);
        const selected = @json($selected);
        const tomSelectInstance = new TomSelect(`#${selectId}`, {
            sortField: 'text',
            hideSelected: false,
            plugins: {
                'dropdown_header': {
                    title: placeholder ?? 'Select',
                }
            }
        });


        const getDataForSelect = (
            route,
            params
        ) => {
            const data = {
                ...params,
                select: true
            };
            $.ajax({
                url: route,
                data: data,
                type: 'GET',
                success: function(response) {
                    tomSelectInstance.clearOptions();
                    const result = response.data ?? null

                    result.forEach(item => {
                        tomSelectInstance.addOption({
                            value: item.id,
                            text: item.name
                        });
                    });

                    tomSelectInstance.refreshOptions(false);

                    if (selected) {
                        tomSelectInstance.setValue(selected);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            })
        }


        if (route) {
            getDataForSelect(route, params);
        }
    }
</script>
