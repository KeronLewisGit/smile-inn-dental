<x-site.layout title="Services" description="General, cosmetic and children's dentistry, Invisalign, oral surgery and emergency care at Smile Inn Dental, St. James.">
<section class="wrap py-16 lg:py-24">
    <x-site.heading eyebrow="Our services" title="Everything your smile needs, <em class='italic font-normal text-gold-deep'>under one roof</em>." lede="We offer a wide variety of services. Pick one below to see exactly what is included, or message us to discuss the details and book an appointment." />
</section>
<section class="wrap pb-24 space-y-6">
    @foreach ($services as $s)
        <article class="grid overflow-hidden rounded-[2rem] bg-white ring-1 ring-line reveal lg:grid-cols-12">
            <div class="relative min-h-64 lg:col-span-5"><img src="{{ asset($s->image ?: 'images/clinic/lounge.webp') }}" alt="" class="absolute inset-0 h-full w-full object-cover" loading="lazy"></div>
            <div class="p-8 sm:p-10 lg:col-span-7">
                <div class="flex items-center gap-3"><span class="inline-flex size-10 items-center justify-center rounded-xl bg-gold-soft text-gold-deep"><x-icon :name="$s->icon ?? 'tooth'" class="size-5" /></span><p class="eyebrow">{{ $s->tagline }}</p></div>
                <h2 class="h-section mt-4"><a href="{{ route('services.show', $s) }}" class="hover:text-gold-deep">{{ $s->name }}</a></h2>
                <p class="mt-4 text-stone">{{ $s->intro }}</p>
                @if ($s->treatments)<ul class="mt-6 flex flex-wrap gap-2">@foreach (array_slice($s->treatments, 0, 7) as $t)<li class="chip bg-sand text-ink-soft">{{ $t['name'] }}</li>@endforeach @if (count($s->treatments) > 7)<li class="chip bg-sand text-stone">+{{ count($s->treatments) - 7 }} more</li>@endif</ul>@endif
                <div class="mt-8 flex flex-wrap gap-3"><a href="{{ route('services.show', $s) }}" class="btn-ink">Learn more</a><a href="{{ route('book') }}" class="btn-ghost">Book with us</a></div>
            </div>
        </article>
    @endforeach
</section>
<x-site.cta title="Not sure what you need? Start with a consultation." text="A general consultation is the easiest first step. We examine, explain and plan, and you decide." />
</x-site.layout>
