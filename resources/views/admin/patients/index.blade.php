<x-admin.layout title="Patients">
    <x-slot:actions><a href="{{ route('admin.patients.create') }}" class="btn-gold btn-sm">Add patient</a></x-slot:actions>
    <form class="mb-5 flex gap-2"><input name="q" value="{{ request('q') }}" placeholder="Search name, phone or email" class="input input-sm max-w-sm"><button class="btn-ink btn-sm">Search</button></form>
    <div class="card overflow-x-auto p-0"><table class="table min-w-[700px]"><thead><tr><th>Name</th><th>Contact</th><th>Visits</th><th>Last / next</th><th>Source</th><th></th></tr></thead><tbody>
        @forelse ($patients as $p)<tr><td><a href="{{ route('admin.patients.show', $p) }}" class="font-semibold hover:text-gold-deep">{{ $p->fullName() }}</a>@if ($p->marketing_opt_in)<span class="ml-2 chip tone-gold">opted in</span>@endif</td><td class="text-xs text-stone">{{ $p->phone }}<br>{{ $p->email }}</td><td>{{ $p->appointments_count }}</td><td class="text-xs text-stone">{{ $p->appointments_max_starts_at ? \Illuminate\Support\Carbon::parse($p->appointments_max_starts_at)->format('j M Y') : '—' }}</td><td class="text-xs text-stone">{{ ucfirst($p->source) }}</td><td class="text-right"><a href="{{ route('admin.patients.show', $p) }}" class="btn-light btn-sm">Open</a></td></tr>
        @empty<tr><td colspan="6" class="text-stone">No patients yet.</td></tr>@endforelse
    </tbody></table></div>
    <div class="mt-5">{{ $patients->links() }}</div>
</x-admin.layout>
