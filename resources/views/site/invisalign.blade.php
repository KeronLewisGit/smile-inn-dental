<x-site.layout title="Invisalign" description="Start your Invisalign journey with the Caribbean's Emerald-certified Invisalign clinic. Free consultations with an iTero 5D scan and 3D smile preview.">
@php($clinic = config('clinic'))
<section class="relative overflow-hidden bg-ink text-ivory">
    <div class="wrap grid gap-12 py-16 lg:grid-cols-12 lg:items-center lg:py-28">
        <div class="lg:col-span-6">
            <p class="eyebrow text-gold">Emerald certified · The top Invisalign clinic in the Caribbean</p>
            <h1 class="h-display mt-6 text-ivory">Your smile journey <em class="italic font-normal text-gold">starts here</em>.</h1>
            <p class="lede mt-6 text-ivory/75">Straighter teeth without the ugly metal brackets. Invisalign uses clear, nearly invisible aligners to correct overcrowded, misaligned and crooked teeth, and at Smile Inn the whole journey is an experience.</p>
            <div class="mt-9 flex flex-wrap gap-3"><a href="{{ route('book', ['type' => 'free-invisalign-consultation']) }}" class="btn-gold btn-lg">Book your free consult</a><a href="#how" class="btn-lg btn bg-white/10 text-ivory ring-1 ring-white/20 hover:bg-white hover:text-ink">How it works</a></div>
            <div class="mt-10 flex flex-wrap items-center gap-4">@foreach ($clinic['invisalign_tiers'] as $tier)<img src="{{ asset($tier['image']) }}" alt="Invisalign {{ $tier['name'] }}" class="h-12 w-12 object-contain {{ $loop->last ? 'scale-125' : 'opacity-50' }}">@endforeach<span class="text-xs text-ivory/60">Emerald is the highest tier awarded by Invisalign.</span></div>
        </div>
        <div class="lg:col-span-6"><div class="frame aspect-[4/5] shadow-soft lg:ml-10"><img src="{{ asset('images/clinic/invisalign-portrait.jpg') }}" alt="A laughing patient with a straight smile" class="h-full w-full object-cover"></div></div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <x-site.heading center eyebrow="Is Invisalign right for you?" title="You get to decide." lede="Explore your smile status without commitment. Here is how aligners compare with traditional braces." />
        <div class="mt-12 grid gap-5 md:grid-cols-2">
            <div class="card-sand"><p class="eyebrow text-stone">Traditional braces</p><ul class="mt-5 space-y-3 text-sm text-ink-soft">@foreach (['Metal wires and brackets, visible all day', 'Cannot be removed until treatment ends', 'Tightened at monthly dentist visits', 'Treatment is generally about two years'] as $li)<li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-stone"></span>{{ $li }}</li>@endforeach</ul></div>
            <div class="card ring-gold/40"><p class="eyebrow">Invisalign at Smile Inn</p><ul class="mt-5 space-y-3 text-sm text-ink">@foreach (['Clear, nearly invisible SmartTrack aligners', 'Removed daily to eat, brush and floss', 'Trays change every 7 to 14 days with check-ins in between', 'As short as 6 to 18 months'] as $li)<li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-5 shrink-0 text-gold-deep" />{{ $li }}</li>@endforeach</ul></div>
        </div>
    </div>
</section>

<section id="how" class="section bg-white">
    <div class="wrap grid gap-14 lg:grid-cols-12 lg:items-center">
        <div class="lg:col-span-5"><div class="frame aspect-[4/5] bg-sand"><img src="{{ asset('images/clinic/itero-scan.jpg') }}" alt="iTero 5D scan" class="h-full w-full object-cover" loading="lazy"></div></div>
        <div class="lg:col-span-7">
            <x-site.heading eyebrow="The technology" title="See your future smile, <em class='italic font-normal text-gold-deep'>now</em>." lede="The Smile Inn Invisalign experience is just that: an experience. We were the first in the region with the iTero 5D scanner with integrated imaging." />
            <ol class="mt-10 space-y-6">
                @foreach ([['Sit back and get scanned', 'A trained nurse digitally scans your mouth with the iTero 5D. No impressions, no gagging, 3D images within seconds.'], ['Preview your result', 'The scanner shows a real-time preview of your smile from start to finish, so you know what you are working toward.'], ['Get your plan', 'Your dentist reviews your concerns and course of treatment. If you are a candidate, an Invisalign-certified dentist creates your treatment plan and your custom trays are made.'], ['Wear, switch, check in', 'Wear aligners about 22 hours a day, change every 7 to 14 days, and visit us for progress checks. Most treatments take 12 to 18 months with results visible far sooner.']] as $i => [$h, $t])
                    <li class="flex gap-5"><span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-ink font-display text-xl text-gold">{{ $i + 1 }}</span><div><h3 class="font-display text-2xl font-medium">{{ $h }}</h3><p class="mt-1 text-sm leading-relaxed text-stone">{{ $t }}</p></div></li>
                @endforeach
            </ol>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <x-site.heading center eyebrow="Results" title="See how Smile Inn patients have already transformed their smile." />
        <div class="mt-12 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">@foreach ($gallery as $g)<div class="frame aspect-[4/3] bg-sand reveal"><img src="{{ asset($g) }}" alt="Smile result" class="h-full w-full object-cover" loading="lazy"></div>@endforeach</div>
        <p class="mt-6 text-center text-xs text-stone">Real Smile Inn patients. Individual results vary.</p>
    </div>
</section>

<section class="section bg-sand/60">
    <div class="wrap grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5"><x-site.heading eyebrow="Your commitments" title="Three habits that keep you on track." /><ul class="mt-8 space-y-5">@foreach ([['22 hours a day', 'Consistency keeps alignment on schedule.'], ['Every 7 to 14 days', 'Each new aligner moves your teeth a little further.'], ['Regular check-ins', 'We check progress and make adjustments, in clinic or via Dental Monitoring.']] as [$h, $t])<li class="flex gap-4"><x-icon name="check" class="mt-1 size-5 shrink-0 text-gold-deep" /><div><p class="font-semibold">{{ $h }}</p><p class="text-sm text-stone">{{ $t }}</p></div></li>@endforeach</ul></div>
        <div class="lg:col-span-7"><x-site.faq :items="$service->faqs ?? []" /></div>
    </div>
</section>

<section class="section">
    <div class="wrap"><x-site.heading center eyebrow="Trusted by celebrities and people like you" title="Because everyone deserves a red-carpet smile." /><div class="mt-12 grid gap-5 md:grid-cols-3">@foreach ($testimonials as $t)<x-site.testimonial :t="$t" compact />@endforeach</div></div>
</section>
<x-site.cta title="You're on your way to Smile Inn ✨" text="Book a free Invisalign consultation. You will leave with a 3D preview of your smile and an honest answer." image="images/clinic/invisalign-portrait.jpg" />
</x-site.layout>
