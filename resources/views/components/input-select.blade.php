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
    'columnShowView' => 'name',
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

        @foreach ($options as $key => $value)
            <option value="{{ $key }}" {{ (string) $key === (string) $selected ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>

    <input type="hidden" name="select-value-{{ $name ?? $id }}" id="select-value-{{ $name ?? $id }}"
        value="{{ $selected }}">

    <a class="reload-select-{{ $name ?? $id }} text-blue-400 text-end text-xs md:text-sm cursor-pointer"
        data-route="{{ $route }}" data-params='@json($params)'>
        <i data-lucide="refresh-cw" class="w-4 h-4 reload-icon"></i>
    </a>

    @error($error ?? $name)
        <p class="text-red-400 text-xs md:text-sm">{{ $message }}</p>
    @enderror
</div>

<script>
    {
        const selectId = @json($name ?? $id);
        const options = @json($options ?? []);
        const columnShowView = @json($columnShowView);
        document.querySelectorAll(`[class^="reload-select-${selectId}"]`).forEach(el => {
            el.addEventListener('click', () => {
                const icon = el.querySelector('.reload-icon');
                const route = el.dataset.route;
                const params = JSON.parse(el.dataset.params || '{}');

                icon.classList.add('spin');

                getDataForSelect(route, params, () => {
                    icon.classList.remove('spin');
                });
            });
        });

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
            params,
            done = null
        ) => {
            const data = {
                ...params,
                select: true
            };

            if (!route) {
                if (typeof done === 'function') done();
                return;
            };
            $.ajax({
                url: route,
                data: data,
                type: 'GET',
                success: function(response) {
                    tomSelectInstance.clearOptions();
                    const result = response.data ?? null
                    const valueOld = $(`#select-value-${selectId}`).val()

                    
                    result.forEach(item => {
                        tomSelectInstance.addOption({
                            value: item.id,
                            text: item[columnShowView]
                        });
                    });

                    tomSelectInstance.refreshOptions(false);

                    if (valueOld) {
                        tomSelectInstance.setValue(valueOld);
                    } else if (selected) {
                        tomSelectInstance.setValue(selected);
                    }
                    if (typeof done === 'function') done();
                },
                error: function(err) {
                    console.log(err);
                    if (typeof done === 'function') done();
                }
            })
        }


        if (route) {
            getDataForSelect(route, params);
        }

        window.tomSelectInstances = window.tomSelectInstances || {};
        window.tomSelectInstances[selectId] = tomSelectInstance;

        window.accessSelect = (name) => {
            return window.tomSelectInstances?.[name]
        }
    }
</script>
