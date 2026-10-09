@props(['eyebrow' => null, 'title', 'lede' => null, 'center' => false, 'light' => false])
<div {{ $attributes->merge(['class' => 'max-w-3xl '.($center ? 'mx-auto text-center' : '')]) }}>
    @if ($eyebrow)<p class="eyebrow {{ $light ? 'text-gold' : '' }} {{ $center ? 'justify-center' : '' }}">{{ $eyebrow }}</p>@endif
    <h2 class="h-section mt-4 {{ $light ? 'text-ivory' : '' }}">{!! $title !!}</h2>
    @if ($lede)<p class="lede mt-5 {{ $light ? 'text-ivory/70' : '' }}">{{ $lede }}</p>@endif
    {{ $slot }}
</div>
