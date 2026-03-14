@props([
    'name',
    'label' => null,
    'type' => 'text',
    'id' => null,
    'placeholder' => null,
    'class' => '',
    'error' => null,
    'value' => null,
    'border_color' => null,
    'required' => false,
    'readonly' => false,
    'description' => null,
])

<div class="w-full flex flex-col justify-end gap-1">
    @if ($label)
        <label for="{{ $id ?? $name }}" class="text-sm md:text-base font-medium text-slate-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- jika type number --}}
    @if ($type === 'number')
        <input type="number" name="{{ $name }}" id="{{ $id ?? $name }}" value="{{ $value }}"
            placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }}
            class="numeric-input hidden w-full text-right border {{ $border_color ?? 'border-stone-500' }} {{ $class }}">

        {{-- input tampilan rupiah --}}
        <input type="text" id="{{ $id ?? $name }}-label" placeholder="{{ $placeholder }}"
            class="rupiah-input w-full text-right border {{ $border_color ?? 'border-stone-500' }} {{ $class }}">
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id ?? $name }}"
            value="{{ $value }}" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            class="w-full border {{ $border_color ?? 'border-stone-500' }} {{ $class }}">
    @endif

    <p class="text-red-500 text-xs error-message hidden"></p>

    @error($error ?? $name)
        <p class="text-red-400 text-xs md:text-sm">{{ $message }}</p>
    @enderror

    @if ($description)
        <p class="text-stone-400 text-xs md:text-sm">{{ $description }}</p>
    @endif
</div>
<script>
    {

        document.addEventListener("DOMContentLoaded", function() {

            function formatRupiah(angka) {
                if (!angka) return '';
                return new Intl.NumberFormat('id-ID').format(angka);
            }

            document.querySelectorAll('.rupiah-input').forEach(labelInput => {

                const numericInput = document.getElementById(labelInput.id.replace('-label', ''));

                // set initial value
                if (numericInput.value) {
                    labelInput.value = formatRupiah(numericInput.value);
                }

                // klik label → ubah ke numeric
                labelInput.addEventListener('focus', () => {
                    labelInput.classList.add('hidden');
                    numericInput.classList.remove('hidden');
                    numericInput.focus();
                });

                // selesai edit numeric
                numericInput.addEventListener('blur', () => {

                    labelInput.value = formatRupiah(numericInput.value);

                    numericInput.classList.add('hidden');
                    labelInput.classList.remove('hidden');
                });

            });

        });
    }
</script>
