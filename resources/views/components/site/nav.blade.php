@php($clinic = config('clinic'))
@php($links = [['home', 'Home'], ['services', 'Services'], ['invisalign', 'Invisalign'], ['emergency', 'Emergency'], ['team', 'Team'], ['clinic', 'The Clinic'], ['blog', 'Journal'], ['contact', 'Contact']])
<header x-data="{ open: false, scrolled: false }" @scroll.window.passive="scrolled = window.scrollY > 12" class="sticky top-0 z-50">
    <div class="bg-ink text-ivory">
        <div class="wrap flex h-9 items-center justify-between text-[11px] font-medium tracking-wide">
            <p class="truncate"><span class="text-gold">Emerald certified</span> · The Caribbean's #1 Invisalign clinic · Free Invisalign consults</p>
            <div class="hidden items-center gap-5 sm:flex">
                <a href="tel:{{ $clinic['phone_href'] }}" class="inline-flex items-center gap-1.5 hover:text-gold"><x-icon name="phone" class="size-3.5" />{{ $clinic['phone'] }}</a>
                <span class="inline-flex items-center gap-1.5 text-ivory/70"><x-icon name="clock" class="size-3.5" />{{ app(\App\Services\Availability::class)->hoursSummary() }}</span>
            </div>
        </div>
    </div>
    <div :class="scrolled ? 'bg-ivory/90 shadow-[0_1px_0_0_rgb(232_225_213)] backdrop-blur' : 'bg-ivory'" class="transition">
        <nav class="wrap flex h-18 items-center justify-between gap-6" aria-label="Main">
            <a href="{{ route('home') }}" class="flex items-center gap-3"><img src="{{ asset('images/logo-gold.png') }}" alt="{{ $clinic['name'] }}" class="h-9 w-auto sm:h-10"></a>
            <div class="hidden items-center gap-0.5 lg:flex">
                @foreach ($links as [$route, $label])<a href="{{ route($route) }}" class="nav-link {{ request()->routeIs($route) || ($route === 'services' && request()->routeIs('services.show')) || ($route === 'blog' && request()->routeIs('blog.show')) || ($route === 'team' && request()->routeIs('team.show')) ? 'active' : '' }}">{{ $label }}</a>@endforeach
            </div>
            <div class="flex items-center gap-2">
                <a href="tel:{{ $clinic['phone_href'] }}" class="btn-ghost btn-sm hidden md:inline-flex"><x-icon name="phone" class="size-4" />Call</a>
                <a href="{{ route('book') }}" class="btn-gold btn-sm sm:px-5 sm:py-2.5">Book online</a>
                <button type="button" @click="open = true" class="inline-flex size-10 items-center justify-center rounded-full ring-1 ring-line lg:hidden" aria-label="Open menu"><x-icon name="menu" /></button>
            </div>
        </nav>
    </div>
    <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 bg-ink/40 backdrop-blur-sm lg:hidden" @click="open = false"></div>
    <div x-cloak x-show="open" x-trap.noscroll="open" x-transition:enter="transition duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed inset-y-0 right-0 z-50 flex w-[86%] max-w-sm flex-col bg-ivory p-6 shadow-soft lg:hidden">
        <div class="flex items-center justify-between"><img src="{{ asset('images/logo-gold.png') }}" alt="" class="h-8"><button type="button" @click="open = false" class="inline-flex size-10 items-center justify-center rounded-full ring-1 ring-line" aria-label="Close menu"><x-icon name="close" /></button></div>
        <div class="mt-8 flex flex-col gap-1">@foreach ($links as [$route, $label])<a href="{{ route($route) }}" class="rounded-xl px-3 py-3 font-display text-2xl text-ink hover:bg-sand">{{ $label }}</a>@endforeach</div>
        <div class="mt-auto space-y-3 pt-6">
            <a href="{{ route('book') }}" class="btn-gold w-full">Book online</a>
            <a href="tel:{{ $clinic['phone_href'] }}" class="btn-ghost w-full"><x-icon name="phone" class="size-4" />{{ $clinic['phone'] }}</a>
        </div>
    </div>
</header>
