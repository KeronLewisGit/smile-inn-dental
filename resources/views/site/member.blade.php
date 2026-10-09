<x-site.layout :title="$member->name" :description="$member->name.', '.$member->title.' at Smile Inn Dental.'">
<section class="wrap grid gap-12 py-16 lg:grid-cols-12 lg:py-24">
    <div class="lg:col-span-5"><div class="frame aspect-[4/5] bg-sand shadow-soft"><img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="h-full w-full object-cover"></div></div>
    <div class="lg:col-span-7">
        <a href="{{ route('team') }}" class="link-arrow text-stone"><x-icon name="arrow" class="rotate-180" /> All team members</a>
        <p class="eyebrow mt-8">{{ $member->title }}</p>
        <h1 class="h-display mt-4">{{ $member->name }}</h1>
        <div class="prose-site mt-8 text-lg">{!! nl2br(e($member->bio ?: 'Part of the Smile Inn team.')) !!}</div>
        <div class="mt-8 flex flex-wrap gap-3">
            @if ($member->accepts_bookings)<a href="{{ route('book') }}" class="btn-gold">Book with {{ $member->firstName() }}</a>@else<a href="{{ route('book') }}" class="btn-gold">Book an appointment</a>@endif
            @if ($member->instagram)<a href="{{ $member->instagram }}" target="_blank" rel="noopener" class="btn-ghost"><x-icon name="instagram" class="size-4" />Instagram</a>@endif
        </div>
    </div>
</section>
<section class="wrap pb-20">
    <p class="eyebrow">Also at Smile Inn</p>
    <div class="mt-6 grid grid-cols-2 gap-5 md:grid-cols-4">@foreach ($others as $m)<a href="{{ route('team.show', $m) }}" class="group"><div class="frame aspect-square bg-sand"><img src="{{ $m->photoUrl() }}" alt="{{ $m->name }}" class="h-full w-full object-cover transition group-hover:scale-105" loading="lazy"></div><p class="mt-3 font-semibold">{{ $m->name }}</p><p class="text-xs text-stone">{{ $m->title }}</p></a>@endforeach</div>
</section>
</x-site.layout>
