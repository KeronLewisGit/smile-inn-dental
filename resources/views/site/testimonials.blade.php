<x-site.layout title="Patient Stories" description="Reviews and video testimonials from Smile Inn Dental patients.">
@php($clinic = config('clinic'))
<section class="wrap py-16 lg:py-24">
    <x-site.heading center eyebrow="Wall of love" title="Helping people smile with confidence since {{ $clinic['founded'] }}." lede="We have guided hundreds of Invisalign journeys and thousands of visits. These are their words, not ours." />
    <div class="mt-8 flex justify-center gap-3"><a href="{{ $clinic['review_url'] }}" target="_blank" rel="noopener" class="btn-light"><x-icon name="star" class="size-4 text-gold" />Leave a Google review</a><a href="{{ route('book') }}" class="btn-gold">Book your visit</a></div>
</section>
@if ($videos->isNotEmpty())
<section class="wrap pb-16">
    <p class="eyebrow">Video stories</p>
    <div class="mt-6 grid gap-5 sm:grid-cols-3">@foreach ($videos as $v)<a href="{{ $v->video_url }}" target="_blank" rel="noopener" class="group relative overflow-hidden rounded-3xl bg-ink p-7 text-ivory ring-1 ring-white/10"><span class="inline-flex size-12 items-center justify-center rounded-full bg-gold text-ink transition group-hover:scale-110"><x-icon name="video" class="size-5" /></span><h2 class="mt-6 font-display text-3xl">{{ $v->name }}</h2><p class="mt-1 text-sm text-ivory/70">{{ $v->treatment }}</p><p class="mt-5 text-sm text-ivory/80">{{ $v->quote }}</p><span class="link-arrow mt-5 text-gold">Watch on Instagram <x-icon name="arrow" /></span></a>@endforeach</div>
</section>
@endif
<section class="wrap pb-24">
    <p class="eyebrow">Written reviews</p>
    <div class="mt-6 columns-1 gap-5 md:columns-2 lg:columns-3">@foreach ($written as $t)<div class="mb-5 break-inside-avoid"><x-site.testimonial :t="$t" /></div>@endforeach</div>
    <div class="mt-12 flex flex-wrap items-center justify-center gap-6">@foreach ($clinic['invisalign_tiers'] as $tier)<img src="{{ asset($tier['image']) }}" alt="{{ $tier['name'] }}" class="h-14 w-14 object-contain {{ $loop->last ? '' : 'opacity-50' }}" loading="lazy">@endforeach</div>
</section>
<x-site.cta />
</x-site.layout>
