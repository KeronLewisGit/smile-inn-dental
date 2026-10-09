<x-site.layout title="Journal" description="Oral health education, Invisalign tips and news from Smile Inn Dental.">
<section class="wrap py-16 lg:py-24">
    <x-site.heading eyebrow="The Smile Inn journal" title="Straight talk about <em class='italic font-normal text-gold-deep'>teeth</em>." lede="Education, Invisalign know-how and the occasional honest truth from the team." />
    <div class="mt-8 flex flex-wrap gap-2"><a href="{{ route('blog') }}" class="chip {{ request('category') ? 'bg-sand text-ink-soft' : 'bg-ink text-ivory' }}">All</a>@foreach ($categories as $c)<a href="{{ route('blog', ['category' => $c]) }}" class="chip {{ request('category') === $c ? 'bg-ink text-ivory' : 'bg-sand text-ink-soft hover:bg-gold-soft' }}">{{ $c }}</a>@endforeach</div>
</section>
@if ($featured)
<section class="wrap pb-12"><a href="{{ route('blog.show', $featured) }}" class="group grid overflow-hidden rounded-[2rem] bg-white ring-1 ring-line lg:grid-cols-2"><div class="relative min-h-72"><img src="{{ $featured->coverUrl() }}" alt="" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105"></div><div class="p-8 sm:p-12"><p class="eyebrow">Featured · {{ $featured->category }}</p><h2 class="h-section mt-4 group-hover:text-gold-deep">{{ $featured->title }}</h2><p class="mt-4 text-stone">{{ $featured->excerpt }}</p><p class="mt-6 text-xs font-semibold uppercase tracking-wider text-stone">{{ $featured->published_at->format('j F Y') }} · {{ $featured->readingMinutes() }} min read</p></div></a></section>
@endif
<section class="wrap pb-24">
    <div class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($posts as $p)
            @continue($featured && $p->is($featured))
            <a href="{{ route('blog.show', $p) }}" class="group reveal"><div class="frame aspect-[16/10] bg-sand"><img src="{{ $p->coverUrl() }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy"></div><p class="mt-4 text-xs font-semibold uppercase tracking-wider text-gold-deep">{{ $p->category }} · {{ $p->readingMinutes() }} min</p><h2 class="mt-2 font-display text-2xl font-medium leading-tight group-hover:text-gold-deep">{{ $p->title }}</h2><p class="mt-2 text-sm text-stone">{{ $p->excerpt }}</p></a>
        @empty<p class="text-stone">No posts yet.</p>@endforelse
    </div>
    <div class="mt-12">{{ $posts->links() }}</div>
</section>
</x-site.layout>
