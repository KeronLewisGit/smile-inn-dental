<x-site.layout title="Your appointment">
<section class="wrap max-w-2xl py-20 lg:py-28">
    <p class="eyebrow">Your appointment</p>
    <h1 class="h-display mt-4">{{ $appointment->type?->name ?? 'Appointment' }}</h1>
    <div class="card mt-8"><dl class="grid gap-5 text-sm sm:grid-cols-2"><div><dt class="label">When</dt><dd class="font-semibold">{{ $appointment->starts_at->format('l j F Y') }}<br>{{ $appointment->starts_at->format('g:i A') }} – {{ $appointment->ends_at->format('g:i A') }}</dd></div><div><dt class="label">Status</dt><dd><span class="chip {{ ['pending' => 'tone-amber', 'confirmed' => 'tone-green', 'completed' => 'tone-blue', 'cancelled' => 'tone-red', 'no_show' => 'tone-red'][$appointment->status] }}">{{ $appointment->statusName() }}</span></dd></div>@if ($appointment->provider)<div><dt class="label">With</dt><dd>{{ $appointment->provider->name }}</dd></div>@endif<div><dt class="label">Reference</dt><dd class="font-mono">{{ $appointment->reference }}</dd></div><div class="sm:col-span-2"><dt class="label">Where</dt><dd>{{ config('clinic.address.line1') }}, {{ config('clinic.address.line2') }} · <a href="{{ config('clinic.map_url') }}" target="_blank" rel="noopener" class="text-gold-deep underline">Directions</a></dd></div></dl></div>
    @if ($appointment->isOpen())
        <div class="card mt-5"><h2 class="font-display text-2xl">Need to change it?</h2><p class="mt-2 text-sm text-stone">To move the time, call us on {{ config('clinic.phone') }} and we will find you a slot. If you cannot make it, cancel below so we can offer the time to someone else.</p>
            <form method="post" action="{{ route('book.cancel', $appointment->manage_token) }}" class="mt-5 flex flex-col gap-3 sm:flex-row" onsubmit="return confirm('Cancel this appointment?')">@csrf<input name="reason" class="input" placeholder="Reason (optional)"><button class="btn-rose shrink-0">Cancel appointment</button></form></div>
    @else
        <div class="mt-6"><a href="{{ route('book') }}" class="btn-gold">Book a new appointment</a></div>
    @endif
</section>
</x-site.layout>
