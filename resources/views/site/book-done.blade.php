<x-site.layout title="Booking received">
<section class="wrap max-w-2xl py-20 text-center lg:py-28">
    <span class="mx-auto inline-flex size-16 items-center justify-center rounded-full bg-sage text-sage-deep"><x-icon name="check" class="size-8" /></span>
    <p class="eyebrow mt-8 justify-center">Request received</p>
    <h1 class="h-display mt-4">Thank you, {{ $appointment->patient->first_name }}.</h1>
    <p class="lede mt-5">We have your request for <strong class="text-ink">{{ $appointment->type?->name ?? 'an appointment' }}</strong> on <strong class="text-ink">{{ $appointment->starts_at->format('l j F \a\t g:i A') }}</strong>{{ $appointment->provider ? ' with '.$appointment->provider->name : '' }}. We will confirm by email shortly.</p>
    <div class="card mt-10 text-left"><dl class="grid gap-4 text-sm sm:grid-cols-2"><div><dt class="label">Reference</dt><dd class="font-mono font-semibold">{{ $appointment->reference }}</dd></div><div><dt class="label">Status</dt><dd><span class="chip tone-amber">Pending confirmation</span></dd></div><div><dt class="label">Where</dt><dd>{{ config('clinic.address.line1') }}, {{ config('clinic.address.line2') }}</dd></div><div><dt class="label">Need to change it?</dt><dd><a href="{{ $appointment->manageUrl() }}" class="font-semibold text-gold-deep hover:underline">Manage this appointment</a></dd></div></dl></div>
    <div class="mt-8 flex flex-wrap justify-center gap-3"><a href="{{ route('home') }}" class="btn-ghost">Back to home</a><a href="{{ route('blog') }}" class="btn-light">Read the journal</a></div>
</section>
</x-site.layout>
