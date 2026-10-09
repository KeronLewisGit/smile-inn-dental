@props(['service'])
<a href="{{ route('services.show', $service) }}" {{ $attributes->merge(['class' => 'group card flex flex-col transition duration-300 hover:-translate-y-1 hover:shadow-soft hover:ring-gold/40']) }}>
    <span class="inline-flex size-12 items-center justify-center rounded-2xl bg-gold-soft text-gold-deep transition group-hover:bg-gold group-hover:text-ink"><x-icon :name="$service->icon ?? 'tooth'" class="size-6" /></span>
    <h3 class="h-card mt-6">{{ $service->name }}</h3>
    <p class="mt-3 flex-1 text-sm leading-relaxed text-stone">{{ $service->tagline ?: \Illuminate\Support\Str::limit($service->intro, 120) }}</p>
    <span class="link-arrow mt-6">Learn more <x-icon name="arrow" /></span>
</a>
