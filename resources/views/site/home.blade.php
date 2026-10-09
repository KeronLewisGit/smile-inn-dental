<x-site.layout>
@php($clinic = config('clinic'))
{{-- Hero --}}
<section class="relative overflow-hidden">
    <div class="wrap grid items-center gap-12 py-16 lg:grid-cols-12 lg:py-24">
        <div class="lg:col-span-6">
            <p class="eyebrow">St. James, Port of Spain · Est. {{ $clinic['founded'] }}</p>
            <h1 class="h-display mt-6">Precision care,<br><em class="font-normal italic text-gold-deep">beautiful</em> smiles.</h1>
            <p class="lede mt-6 max-w-xl">The Caribbean's #1 dental clinic and its first Emerald-certified Invisalign provider. Award-winning general, cosmetic and children's dentistry in a space designed to feel nothing like a dentist's office.</p>
            <div class="mt-9 flex flex-wrap items-center gap-3">
                <a href="{{ route('book') }}" class="btn-gold btn-lg">Book an appointment</a>
                <a href="{{ route('book', ['type' => 'free-invisalign-consultation']) }}" class="btn-ghost btn-lg">Free Invisalign consult</a>
            </div>
            <div class="mt-10 flex flex-wrap items-center gap-x-8 gap-y-4 text-sm text-stone">
                <span class="inline-flex items-center gap-2"><span class="flex text-gold">@for ($i = 0; $i < 5; $i++)<x-icon name="star" class="size-4 fill-current" />@endfor</span><strong class="text-ink">5.0</strong> on Google</span>
                <span class="inline-flex items-center gap-2"><x-icon name="instagram" class="size-4 text-gold-deep" /><strong class="text-ink">{{ $clinic['instagram_followers'] }}</strong> followers {{ $clinic['handle'] }}</span>
                <span class="inline-flex items-center gap-2"><x-icon name="shield" class="size-4 text-gold-deep" />Emerald certified</span>
            </div>
        </div>
        <div class="relative lg:col-span-6">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-7 frame aspect-[4/5] shadow-soft"><img src="{{ asset('images/clinic/doctors.jpg') }}" alt="The Smile Inn doctors in front of the gold heart logo" class="h-full w-full object-cover" fetchpriority="high"></div>
                <div class="col-span-5 flex flex-col gap-4">
                    <div class="frame aspect-square"><img src="{{ asset('images/clinic/invisalign-aligner.jpg') }}" alt="A clear Invisalign aligner" class="h-full w-full object-cover" loading="lazy"></div>
                    <div class="flex flex-1 flex-col justify-between rounded-3xl bg-ink p-5 text-ivory"><img src="{{ asset('images/badges/emerald.png') }}" alt="Invisalign Emerald provider" class="h-14 w-14 object-contain"><p class="mt-4 font-display text-2xl leading-tight">Emerald.<br><span class="text-gold">The highest Invisalign tier in the Caribbean.</span></p></div>
                </div>
            </div>
            @if ($nextSlot)<div class="absolute -bottom-6 -left-4 hidden rounded-2xl bg-white px-5 py-4 shadow-soft ring-1 ring-line sm:block">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-stone">Next available consultation</p>
                <p class="mt-1 font-display text-xl">{{ $nextSlot->isToday() ? 'Today' : ($nextSlot->isTomorrow() ? 'Tomorrow' : $nextSlot->format('D j M')) }}, {{ $nextSlot->format('g:i A') }} <a href="{{ route('book', ['type' => 'general-consultation']) }}" class="ml-2 font-sans text-sm font-semibold text-gold-deep hover:underline">Book →</a></p>
            </div>@endif
        </div>
    </div>
    {{-- Marquee --}}
    <div class="border-y border-line bg-sand/60 py-4" aria-hidden="true">
        <div class="overflow-hidden"><div class="marquee text-sm font-semibold uppercase tracking-[0.2em] text-ink-soft">@for ($r = 0; $r < 2; $r++)@foreach (['Invisalign', 'Gentle dentistry', 'Cosmetic dentistry', "Children's dentistry", 'Oral surgery', 'Dental emergencies', 'iTero 5D scanning', 'Digital Smile Design', 'Dental implants', 'Myofunctional therapy'] as $w)<span class="inline-flex items-center gap-10">{{ $w }}<span class="text-gold">✦</span></span>@endforeach @endfor</div></div>
    </div>
</section>

{{-- Press --}}
<section class="wrap py-12">
    <p class="text-center text-[11px] font-semibold uppercase tracking-[0.22em] text-stone">As featured in</p>
    <div class="mt-6 flex flex-wrap items-center justify-center gap-x-12 gap-y-6 opacity-70 grayscale">@foreach ($clinic['press'] as $p)<img src="{{ asset($p['image']) }}" alt="{{ $p['name'] }}" class="h-7 w-auto object-contain sm:h-9" loading="lazy">@endforeach</div>
</section>

{{-- Services --}}
<section class="section bg-ivory">
    <div class="wrap">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <x-site.heading eyebrow="What we do" title="Everything your smile needs,<br>under one roof." />
            <a href="{{ route('services') }}" class="link-arrow">All services <x-icon name="arrow" /></a>
        </div>
        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">@foreach ($services as $s)<x-site.service-card :service="$s" class="reveal" />@endforeach</div>
    </div>
</section>

{{-- Values --}}
<section class="section bg-white">
    <div class="wrap grid gap-14 lg:grid-cols-12 lg:items-center">
        <div class="lg:col-span-5">
            <div class="frame aspect-[4/5] shadow-soft"><img src="{{ asset('images/clinic/lounge.webp') }}" alt="The minimalist Smile Inn lounge" class="h-full w-full object-cover" loading="lazy"></div>
        </div>
        <div class="lg:col-span-7">
            <x-site.heading eyebrow="Our values" title="Minimalist design, <em class='italic font-normal text-gold-deep'>maximum</em> comfort." lede="Every area of the clinic was planned around simplicity, elegance and care, so the only thing you have to think about is relaxing." />
            <dl class="mt-10 grid gap-8 sm:grid-cols-3">
                @foreach ([['shield', 'Award-winning expertise', "Emerald-certified Invisalign providers and a team of 10+ specialists, recognised across the region."], ['sparkle', 'Premium care', 'Personalised treatment plans, the latest technology and a spa-like environment.'], ['heart', 'Gentle by design', 'Patients who used to dread the dentist tell us they now look forward to coming in.']] as [$icon, $h, $t])
                    <div class="reveal"><dt><span class="inline-flex size-11 items-center justify-center rounded-2xl bg-gold-soft text-gold-deep"><x-icon :name="$icon" class="size-5" /></span><span class="mt-4 block font-display text-2xl font-medium">{{ $h }}</span></dt><dd class="mt-2 text-sm leading-relaxed text-stone">{{ $t }}</dd></div>
                @endforeach
            </dl>
            <div class="mt-10 grid grid-cols-3 gap-4 border-t border-line pt-8">
                @foreach ([[$clinic['stats']['years'], 'years of experience'], [$clinic['stats']['specialists'], 'specialists on the team'], [$clinic['stats']['smiles'], 'smiles transformed']] as [$n, $l])
                    <div><p class="font-display text-4xl font-medium text-ink sm:text-5xl">{{ $n }}</p><p class="mt-1 text-xs font-semibold uppercase tracking-wider text-stone">{{ $l }}</p></div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Invisalign feature --}}
<section class="section bg-ink text-ivory">
    <div class="wrap grid gap-12 lg:grid-cols-12 lg:items-center">
        <div class="lg:col-span-6">
            <x-site.heading light eyebrow="Invisalign specialist centre" title="See your future smile, <em class='italic font-normal text-gold'>now</em>." lede="Sit back while a trained nurse scans your mouth with the iTero 5D. In seconds you see a 3D preview of where your smile is going, before you commit to anything." />
            <ul class="mt-8 space-y-3 text-sm text-ivory/80">
                @foreach (['Free consultation and 3D smile preview', 'Clear, nearly invisible aligners you remove to eat and brush', 'Most treatments finish in 6 to 18 months', 'Dental Monitoring check-ins from your phone'] as $li)<li class="flex items-start gap-3"><x-icon name="check" class="mt-0.5 size-5 shrink-0 text-gold" />{{ $li }}</li>@endforeach
            </ul>
            <div class="mt-9 flex flex-wrap gap-3"><a href="{{ route('book', ['type' => 'free-invisalign-consultation']) }}" class="btn-gold btn-lg">Book a free consult</a><a href="{{ route('invisalign') }}" class="btn-lg btn bg-white/10 text-ivory ring-1 ring-white/20 hover:bg-white hover:text-ink">How it works</a></div>
            <div class="mt-10 flex flex-wrap items-center gap-5">@foreach ($clinic['invisalign_tiers'] as $tier)<img src="{{ asset($tier['image']) }}" alt="Invisalign {{ $tier['name'] }}" title="{{ $tier['name'] }}" class="h-12 w-12 object-contain {{ $loop->last ? 'scale-125' : 'opacity-60' }}" loading="lazy">@endforeach<span class="text-xs text-ivory/60">Gold → Platinum Elite → Diamond → Diamond Elite → <strong class="text-gold">Emerald</strong></span></div>
        </div>
        <div class="lg:col-span-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="frame aspect-[3/4] mt-10"><img src="{{ asset('images/clinic/invisalign-portrait.jpg') }}" alt="A patient smiling after Invisalign" class="h-full w-full object-cover" loading="lazy"></div>
                <div class="frame aspect-[3/4] bg-white"><img src="{{ asset('images/clinic/itero-scan.png') }}" alt="An iTero digital scan of a smile" class="h-full w-full object-cover" loading="lazy"></div>
            </div>
        </div>
    </div>
</section>

{{-- Team --}}
<section class="section">
    <div class="wrap">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <x-site.heading eyebrow="Meet the experts" title="The team behind<br>your smile." />
            <a href="{{ route('team') }}" class="link-arrow">Everyone at Smile Inn <x-icon name="arrow" /></a>
        </div>
        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($team as $m)
                <a href="{{ route('team.show', $m) }}" class="group reveal">
                    <div class="frame aspect-[4/5] bg-sand"><img src="{{ $m->photoUrl() }}" alt="{{ $m->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy"></div>
                    <h3 class="mt-4 font-display text-2xl font-medium">{{ $m->name }}</h3><p class="text-sm text-stone">{{ $m->title }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Testimonials --}}
<section class="section bg-sand/60">
    <div class="wrap">
        <x-site.heading center eyebrow="Trusted by celebrities and people like you" title="Because everyone deserves a red-carpet smile." lede="No paparazzi required. Here is what patients say after a visit." />
        <div class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-4">@foreach ($testimonials as $t)<x-site.testimonial :t="$t" compact class="reveal" />@endforeach</div>
        <div class="mt-10 flex flex-wrap justify-center gap-3"><a href="{{ route('testimonials') }}" class="btn-ink">More patient stories</a><a href="{{ $clinic['review_url'] }}" target="_blank" rel="noopener" class="btn-light"><x-icon name="star" class="size-4 text-gold" />Leave a review</a></div>
    </div>
</section>

{{-- Emergency + hours --}}
<section class="section">
    <div class="wrap grid gap-6 lg:grid-cols-12">
        <div class="rounded-3xl bg-rose p-8 text-white sm:p-10 lg:col-span-7">
            <p class="eyebrow text-white/80">Dental emergency?</p>
            <h2 class="h-section mt-3 text-white">Toothache, broken tooth, knocked-out tooth. We keep time for you every day.</h2>
            <p class="mt-4 max-w-xl text-white/80">Call us the moment it happens and we will tell you what to do on the way. Knocked-out tooth? Keep it in milk or saline. Time is critical.</p>
            <div class="mt-7 flex flex-wrap gap-3"><a href="tel:{{ $clinic['phone_href'] }}" class="btn bg-white text-rose hover:bg-ivory btn-lg"><x-icon name="phone" class="size-4" />{{ $clinic['phone'] }}</a><a href="{{ route('emergency') }}" class="btn ring-1 ring-white/40 text-white hover:bg-white/10 btn-lg">What we treat</a></div>
        </div>
        <div class="card lg:col-span-5">
            <p class="eyebrow">Opening hours</p>
            <dl class="mt-5 divide-y divide-line text-sm">@foreach ($hours as $day => $h)<div class="flex justify-between py-2.5"><dt class="font-semibold">{{ $day }}</dt><dd class="{{ $h === 'Closed' ? 'text-stone' : '' }}">{{ $h }}</dd></div>@endforeach</dl>
            <p class="mt-5 flex items-start gap-2 text-sm text-stone"><x-icon name="map" class="mt-0.5 size-4 shrink-0 text-gold-deep" />{{ $clinic['address']['line1'] }}, {{ $clinic['address']['line2'] }}</p>
            <a href="{{ $clinic['map_url'] }}" target="_blank" rel="noopener" class="link-arrow mt-3">Directions <x-icon name="arrow" /></a>
        </div>
    </div>
</section>

{{-- Journal --}}
@if ($posts->isNotEmpty())
<section class="section bg-white">
    <div class="wrap">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between"><x-site.heading eyebrow="The Smile Inn journal" title="Straight talk about teeth." /><a href="{{ route('blog') }}" class="link-arrow">See all posts <x-icon name="arrow" /></a></div>
        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach ($posts as $p)
                <a href="{{ route('blog.show', $p) }}" class="group reveal"><div class="frame aspect-[16/10] bg-sand"><img src="{{ $p->coverUrl() }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy"></div><p class="mt-4 text-xs font-semibold uppercase tracking-wider text-gold-deep">{{ $p->category }} · {{ $p->readingMinutes() }} min read</p><h3 class="mt-2 font-display text-2xl font-medium leading-tight group-hover:text-gold-deep">{{ $p->title }}</h3><p class="mt-2 text-sm text-stone">{{ $p->excerpt }}</p></a>
            @endforeach
        </div>
    </div>
</section>
@endif

<x-site.cta />
</x-site.layout>
