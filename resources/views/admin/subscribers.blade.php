<x-admin.layout title="Newsletter subscribers">
    <x-slot:actions><a href="{{ route('admin.subscribers.export') }}" class="btn-gold btn-sm"><x-icon name="download" class="size-4" />Export CSV</a></x-slot:actions>
    <p class="mb-4 text-sm text-stone">{{ $active }} active subscriber(s). The CSV import works with Mailchimp, Brevo and most newsletter tools.</p>
    <div class="card overflow-x-auto p-0"><table class="table"><thead><tr><th>Email</th><th>Name</th><th>Joined</th><th>Status</th><th></th></tr></thead><tbody>
        @forelse ($subscribers as $s)<tr><td class="font-semibold">{{ $s->email }}</td><td>{{ $s->name ?: '—' }}</td><td class="text-xs text-stone">{{ $s->created_at->format('j M Y') }}</td><td>@if ($s->unsubscribed_at)<span class="chip tone-slate">Unsubscribed</span>@else<span class="chip tone-green">Active</span>@endif</td><td class="text-right"><form method="post" action="{{ route('admin.subscribers.destroy', $s) }}" onsubmit="return confirm('Remove this subscriber?')">@csrf @method('delete')<button class="text-xs font-semibold text-rose hover:underline">Remove</button></form></td></tr>
        @empty<tr><td colspan="5" class="text-stone">No subscribers yet.</td></tr>@endforelse
    </tbody></table></div>
    <div class="mt-5">{{ $subscribers->links() }}</div>
</x-admin.layout>
