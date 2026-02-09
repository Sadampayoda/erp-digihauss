@props(['key', 'action' => null, 'message' => null, 'justify_text' => 'text-center','errorList' => false])

@if (session()->has($key) || $message)
    @switch($action)

        @case('success')
            <div
                class="w-full rounded-xl p-4 mb-4
                       bg-green-50 text-green-800 border border-green-300
                       text-sm md:text-base
                       {{ $justify_text }}"
            >
                <strong class="block font-semibold text-base md:text-lg">
                    Sukses!
                </strong>
                <p class="mt-1">
                    {{ $message ?? session($key) }}
                </p>
            </div>
        @break

        @case('errors')
            <div
                class="w-full rounded-xl p-4 mb-4
                       bg-red-50 text-red-800 border border-red-300
                       text-sm md:text-base
                       {{ $justify_text }}"
            >
                <strong class="block font-semibold text-base md:text-lg">
                    Terjadi Kesalahan!
                </strong>

                @if ($errors->any() && $errorList)
                    <ul class="mt-2 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm md:text-base">
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-2 text-sm md:text-base">
                        {{ $message ?? session($key) }}
                    </p>
                @endif
            </div>
        @break

        @case('warning')
            <div
                class="w-full rounded-xl p-4 mb-4
                       bg-yellow-50 text-yellow-800 border border-yellow-300
                       text-sm md:text-base
                       {{ $justify_text }}"
            >
                <strong class="block font-semibold text-base md:text-lg">
                    Peringatan!
                </strong>
                <p class="mt-1">
                    {{ $message ?? session($key) }}
                </p>
            </div>
        @break

        @default
            <div
                class="w-full rounded-xl p-4 mb-4
                       bg-blue-50 text-blue-800 border border-blue-300
                       text-sm md:text-base
                       {{ $justify_text }}"
            >
                <strong class="block font-semibold text-base md:text-lg">
                    Pemberitahuan!
                </strong>
                <p class="mt-1">
                    {{ $message ?? session($key) }}
                </p>
            </div>

    @endswitch
@endif
