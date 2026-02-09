@props([
    'width' => 35,
    'height' => 15,
    'lg_width' => 35,
    'lg_height' => 15,
    'md_width' => 35,
    'md_height' => 35,

])

<img class="w-{{ $width }} h-{{ $height }} lg:w-{{ $lg_width }} lg:h-{{ $lg_height }} md:w-{{ $md_width }} md:h-{{ $md_height }} object-cover" src="{{ asset(config('app.url_image_app')) }}" alt="{{ config('app.base_name_app') }}">
