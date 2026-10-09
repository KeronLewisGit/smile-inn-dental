<x-site.layout :title="$title">
<section class="wrap max-w-2xl py-24 text-center lg:py-32">
    <p class="eyebrow justify-center">Error {{ $code }}</p>
    <h1 class="h-display mt-4">{{ $title }}</h1>
    <p class="lede mt-5">{{ $message }}</p>
    <div class="mt-8 flex flex-wrap justify-center gap-3"><a href="{{ route('home') }}" class="btn-gold">Back to home</a><a href="{{ route('book') }}" class="btn-ghost">Book an appointment</a></div>
    <p class="mt-8 text-sm text-stone">Need us right now? Call <a href="tel:{{ config('clinic.phone_href') }}" class="font-semibold text-ink">{{ config('clinic.phone') }}</a>.</p>
</section>
</x-site.layout>
