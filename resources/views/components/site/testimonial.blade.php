@props(['t', 'compact' => false])
<figure {{ $attributes->merge(['class' => 'card flex flex-col '.($compact ? 'p-6' : '')]) }}>
    <div class="flex items-center justify-between"><div class="flex gap-0.5 text-gold">@for ($i = 0; $i < $t->rating; $i++)<x-icon name="star" class="size-4 fill-current" />@endfor</div><x-icon name="quote" class="size-8 text-gold/40" /></div>
    <blockquote class="mt-5 flex-1 font-display text-xl leading-snug text-ink">“{{ $t->quote }}”</blockquote>
    <figcaption class="mt-6 flex items-center gap-3">
        <span class="inline-flex size-10 items-center justify-center rounded-full bg-sand font-display text-lg font-semibold text-gold-deep">{{ \Illuminate\Support\Str::of($t->name)->substr(0, 1) }}</span>
        <span><span class="block text-sm font-semibold">{{ $t->name }}</span><span class="block text-xs text-stone">{{ $t->treatment }}{{ $t->source ? ' · '.$t->source.' review' : '' }}</span></span>
    </figcaption>
</figure>
