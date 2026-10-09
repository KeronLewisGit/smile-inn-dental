<x-site.layout title="Our Team" description="Meet the dentists, hygienist, nurses and practice team at Smile Inn Dental in St. James, Trinidad.">
<section class="wrap py-16 lg:py-24">
    <x-site.heading eyebrow="Meet the team" title="Passionate about transforming <em class='italic font-normal text-gold-deep'>smiles and lives</em>." lede="At Smile Inn, our team isn't just experienced. We believe dental care is more than routine check-ups: it is a transformative journey toward your best, most confident self. Every treatment is rooted in a deep passion for precision and excellence, so your smile looks stunning and feels natural and healthy." />
    <div class="mt-8 flex flex-wrap gap-2">@foreach (['Beauty', 'Aesthetics', 'Confidence', 'Unique'] as $w)<span class="chip bg-gold-soft text-gold-deep">{{ $w }}</span>@endforeach</div>
</section>
<section class="wrap pb-20">
    <p class="eyebrow">The dentists</p>
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($dentists as $m)
            <a href="{{ route('team.show', $m) }}" class="group reveal"><div class="frame aspect-[4/5] bg-sand shadow-card"><img src="{{ $m->photoUrl() }}" alt="{{ $m->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy"></div><h2 class="mt-4 font-display text-2xl font-medium group-hover:text-gold-deep">{{ $m->name }}</h2><p class="text-sm text-stone">{{ $m->title }}</p></a>
        @endforeach
    </div>
    <p class="eyebrow mt-20">The care team</p>
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($staff as $m)
            <a href="{{ route('team.show', $m) }}" class="group card flex gap-5 reveal"><div class="size-24 shrink-0 overflow-hidden rounded-2xl bg-sand"><img src="{{ $m->photoUrl() }}" alt="{{ $m->name }}" class="h-full w-full object-cover" loading="lazy"></div><div><h2 class="font-display text-2xl font-medium leading-tight group-hover:text-gold-deep">{{ $m->name }}</h2><p class="mt-1 text-sm text-stone">{{ $m->title }}</p><span class="link-arrow mt-3 text-xs">Read more <x-icon name="arrow" /></span></div></a>
        @endforeach
    </div>
</section>
<x-site.cta title="Book an appointment with the team." />
</x-site.layout>
