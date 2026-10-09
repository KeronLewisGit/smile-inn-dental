<x-admin.layout title="Dashboard">
    <x-slot:actions><a href="{{ route('admin.appointments.create') }}" class="btn-gold btn-sm">New appointment</a></x-slot:actions>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([['Today', $stats['today'], 'appointments'], ['This week', $stats['week'], 'booked'], ['Awaiting confirmation', $stats['pending'], 'pending requests'], ['New enquiries', $stats['new_inquiries'], 'to answer'], ['Patients', $stats['patients'], $stats['new_patients_month'].' new this month'], ['Subscribers', $stats['subscribers'], 'newsletter'], ['No-show rate', $stats['no_show_rate'].'%', 'last 90 days']] as [$l, $v, $s])
            <div class="stat"><p class="text-[11px] font-semibold uppercase tracking-wider text-stone">{{ $l }}</p><p class="mt-1 font-display text-4xl">{{ $v }}</p><p class="text-xs text-stone">{{ $s }}</p></div>
        @endforeach
        <div class="stat"><p class="text-[11px] font-semibold uppercase tracking-wider text-stone">Next 7 days</p><div class="mt-2 flex items-end gap-1.5">@foreach ($upcomingDays as $d)<div class="flex flex-1 flex-col items-center gap-1"><div class="w-full rounded-md bg-gold" style="height: {{ max(4, min(40, $d['count'] * 8)) }}px" title="{{ $d['count'] }}"></div><span class="text-[10px] text-stone">{{ $d['date']->format('D') }}</span></div>@endforeach</div></div>
    </div>
    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <section class="card p-0 xl:col-span-2"><div class="flex items-center justify-between px-5 py-4"><h2 class="font-display text-2xl">Today, {{ now()->format('l j F') }}</h2><a href="{{ route('admin.calendar') }}" class="link-arrow text-xs">Calendar <x-icon name="arrow" /></a></div>
            <table class="table"><thead><tr><th>Time</th><th>Patient</th><th>Type</th><th>With</th><th>Status</th></tr></thead><tbody>
                @forelse ($today as $a)<tr><td class="whitespace-nowrap font-semibold">{{ $a->starts_at->format('g:i A') }}</td><td><a href="{{ route('admin.appointments.show', $a) }}" class="font-semibold hover:text-gold-deep">{{ $a->patient->fullName() }}</a><span class="block text-xs text-stone">{{ $a->patient->phone }}</span></td><td>{{ $a->type?->name }}</td><td>{{ $a->provider?->name ?? '—' }}</td><td><x-admin.status :status="$a->status" /></td></tr>
                @empty<tr><td colspan="5" class="text-stone">Nothing booked today.</td></tr>@endforelse
            </tbody></table></section>
        <section class="space-y-6">
            <div class="card p-0"><div class="px-5 py-4"><h2 class="font-display text-2xl">Awaiting confirmation</h2></div><ul class="divide-y divide-line">@forelse ($pending as $a)<li class="flex items-center justify-between gap-3 px-5 py-3 text-sm"><div><a href="{{ route('admin.appointments.show', $a) }}" class="font-semibold hover:text-gold-deep">{{ $a->patient->fullName() }}</a><span class="block text-xs text-stone">{{ $a->starts_at->format('D j M, g:i A') }} · {{ $a->type?->name }}</span></div><form method="post" action="{{ route('admin.appointments.status', $a) }}">@csrf @method('patch')<input type="hidden" name="status" value="confirmed"><button class="btn-light btn-sm">Confirm</button></form></li>@empty<li class="px-5 py-4 text-sm text-stone">All caught up.</li>@endforelse</ul></div>
            <div class="card p-0"><div class="px-5 py-4"><h2 class="font-display text-2xl">New enquiries</h2></div><ul class="divide-y divide-line">@forelse ($newInquiries as $i)<li class="px-5 py-3 text-sm"><a href="{{ route('admin.inquiries.show', $i) }}" class="font-semibold hover:text-gold-deep">{{ $i->name }}</a> <span class="text-xs text-stone">· {{ $i->service ?: 'General' }} · {{ $i->created_at->diffForHumans() }}</span><p class="mt-1 line-clamp-2 text-stone">{{ $i->message }}</p></li>@empty<li class="px-5 py-4 text-sm text-stone">No new enquiries.</li>@endforelse</ul></div>
        </section>
    </div>
</x-admin.layout>
