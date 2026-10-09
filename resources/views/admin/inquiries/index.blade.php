<x-admin.layout title="Enquiries">
    <form class="mb-5 flex gap-2"><select name="status" class="input input-sm max-w-40"><option value="">Any status</option>@foreach ($statuses as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach</select><button class="btn-ink btn-sm">Filter</button></form>
    <div class="card overflow-x-auto p-0"><table class="table min-w-[700px]"><thead><tr><th>Received</th><th>From</th><th>Interest</th><th>Message</th><th>Status</th><th>Assigned</th></tr></thead><tbody>
        @forelse ($inquiries as $i)<tr><td class="whitespace-nowrap text-xs text-stone">{{ $i->created_at->format('j M, g:i A') }}</td><td><a href="{{ route('admin.inquiries.show', $i) }}" class="font-semibold hover:text-gold-deep">{{ $i->name }}</a><span class="block text-xs text-stone">{{ $i->phone ?: $i->email }}</span></td><td>{{ $i->service ?: '—' }}</td><td class="max-w-xs truncate text-stone">{{ $i->message }}</td><td><x-admin.status :status="$i->status" /></td><td class="text-xs text-stone">{{ $i->assignee?->name ?? '—' }}</td></tr>
        @empty<tr><td colspan="6" class="text-stone">No enquiries.</td></tr>@endforelse
    </tbody></table></div>
    <div class="mt-5">{{ $inquiries->links() }}</div>
</x-admin.layout>
