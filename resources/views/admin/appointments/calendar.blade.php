<x-admin.layout title="Calendar">
    <x-slot:actions><a href="{{ route('admin.calendar', ['week' => $week->copy()->subWeek()->toDateString()]) }}" class="btn-light btn-sm">← Prev</a><a href="{{ route('admin.calendar') }}" class="btn-light btn-sm">This week</a><a href="{{ route('admin.calendar', ['week' => $week->copy()->addWeek()->toDateString()]) }}" class="btn-light btn-sm">Next →</a><a href="{{ route('admin.appointments.create') }}" class="btn-gold btn-sm">New</a></x-slot:actions>
    <p class="mb-4 text-sm text-stone">Week of {{ $week->format('j F Y') }}</p>
    <div class="grid gap-3 md:grid-cols-7">
        @foreach ($days as $d)
            @php($closed = ($hours[strtolower($d->format('D'))] ?? null) === null)
            <div class="rounded-2xl {{ $d->isToday() ? 'bg-gold-soft ring-1 ring-gold' : 'bg-white ring-1 ring-line' }} {{ $closed ? 'opacity-60' : '' }} p-3 min-h-40">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-stone">{{ $d->format('D') }}</p><p class="font-display text-2xl">{{ $d->format('j') }}</p>
                @if ($closed)<p class="mt-2 text-xs text-stone">Closed</p>@endif
                <ul class="mt-2 space-y-1.5">@foreach ($byDay[$d->toDateString()] ?? [] as $a)<li><a href="{{ route('admin.appointments.show', $a) }}" class="block rounded-lg px-2 py-1.5 text-xs ring-1 {{ ['pending' => 'bg-amber-50 ring-amber-200', 'confirmed' => 'bg-emerald-50 ring-emerald-200', 'completed' => 'bg-sky-50 ring-sky-200'][$a->status] ?? 'bg-sand ring-line' }} hover:ring-gold"><span class="font-semibold">{{ $a->starts_at->format('g:i A') }}</span> {{ $a->patient->fullName() }}<span class="block truncate text-stone">{{ $a->type?->name }}</span></a></li>@endforeach</ul>
            </div>
        @endforeach
    </div>
</x-admin.layout>
