@php($clinic = config('clinic'))
<footer class="mt-24 bg-ink text-ivory">
    <div class="wrap grid gap-12 py-16 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <img src="{{ asset('images/logo-gold.png') }}" alt="{{ $clinic['name'] }}" class="h-10 w-auto">
            <p class="mt-5 max-w-sm text-sm leading-relaxed text-ivory/70">{{ $clinic['tagline'] }} An award-winning, Emerald-certified Invisalign clinic in the heart of St. James, where precision meets a genuinely relaxing experience.</p>
            <div class="mt-6 flex gap-2">
                <a href="{{ $clinic['social']['instagram'] }}" target="_blank" rel="noopener" class="inline-flex size-10 items-center justify-center rounded-full ring-1 ring-white/15 transition hover:bg-gold hover:text-ink hover:ring-gold" aria-label="Instagram"><x-icon name="instagram" class="size-4.5" /></a>
                <a href="{{ $clinic['social']['tiktok'] }}" target="_blank" rel="noopener" class="inline-flex size-10 items-center justify-center rounded-full ring-1 ring-white/15 transition hover:bg-gold hover:text-ink hover:ring-gold" aria-label="TikTok"><x-icon name="tiktok" class="size-4.5" /></a>
                <a href="{{ $clinic['social']['x'] }}" target="_blank" rel="noopener" class="inline-flex size-10 items-center justify-center rounded-full ring-1 ring-white/15 transition hover:bg-gold hover:text-ink hover:ring-gold" aria-label="X"><x-icon name="x" class="size-4" /></a>
            </div>
        </div>
        <div class="grid gap-10 sm:grid-cols-3 lg:col-span-5">
            <div><p class="eyebrow mb-4 text-gold">Explore</p><ul class="space-y-2.5 text-sm text-ivory/80">@foreach ([['services', 'Services'], ['invisalign', 'Invisalign'], ['emergency', 'Emergency care'], ['clinic', 'The clinic'], ['team', 'Our team'], ['testimonials', 'Patient stories'], ['blog', 'Journal']] as [$r, $l])<li><a href="{{ route($r) }}" class="hover:text-gold">{{ $l }}</a></li>@endforeach</ul></div>
            <div><p class="eyebrow mb-4 text-gold">Visit</p><address class="space-y-2.5 text-sm not-italic text-ivory/80"><p>{{ $clinic['address']['line1'] }}<br>{{ $clinic['address']['line2'] }}<br>{{ $clinic['address']['country'] }}</p><p><a href="{{ $clinic['map_url'] }}" target="_blank" rel="noopener" class="link-arrow text-gold hover:text-gold-soft">Get directions <x-icon name="arrow" /></a></p></address></div>
            <div><p class="eyebrow mb-4 text-gold">Hours</p><ul class="space-y-2.5 text-sm text-ivory/80">@foreach (app(\App\Services\Availability::class)->hoursGrouped() as $g)<li class="flex justify-between gap-3"><span>{{ $g['days'] }}</span><span class="{{ $g['hours'] === 'Closed' ? 'text-ivory/50' : '' }}">{{ $g['hours'] }}</span></li>@endforeach</ul><p class="mt-4 text-sm"><a href="tel:{{ $clinic['phone_href'] }}" class="hover:text-gold">{{ $clinic['phone'] }}</a><br><a href="mailto:{{ $clinic['email'] }}" class="hover:text-gold">{{ $clinic['email'] }}</a></p></div>
        </div>
        <div class="lg:col-span-3">
            <p class="eyebrow mb-4 text-gold">Stay up to date</p>
            <p class="text-sm text-ivory/70">Oral health tips, Invisalign offers and clinic news. No spam, ever.</p>
            @if (session('newsletter'))<p class="mt-4 rounded-xl bg-gold/15 px-4 py-3 text-sm font-semibold text-gold">{{ session('newsletter') }}</p>@else
            <form method="post" action="{{ route('newsletter.store') }}" class="mt-4 flex gap-2">@csrf<input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden"><label for="nl-email" class="sr-only">Email</label><input id="nl-email" name="email" type="email" required placeholder="you@email.com" class="input bg-white/10 text-ivory ring-white/15 placeholder:text-ivory/40 focus:ring-gold"><button class="btn-gold shrink-0 px-4">Join</button></form>
            @error('email')<p class="error text-gold">{{ $message }}</p>@enderror
            @endif
            <a href="{{ $clinic['review_url'] }}" target="_blank" rel="noopener" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-ivory/80 hover:text-gold"><x-icon name="star" class="size-4 text-gold" />Leave us a Google review</a>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="wrap flex flex-col gap-3 py-6 text-xs text-ivory/50 sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ now()->year }} {{ $clinic['name'] }}. {{ $clinic['strap'] }}.</p>
            <div class="flex gap-5"><a href="{{ route('privacy') }}" class="hover:text-gold">Privacy policy</a><a href="{{ route('admin.login') }}" class="hover:text-gold">Staff login</a></div>
        </div>
    </div>
</footer>
