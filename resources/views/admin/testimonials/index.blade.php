<x-admin.layout title="Testimonials">
    <x-slot:actions><a href="{{ route('admin.testimonials.create') }}" class="btn-gold btn-sm">Add testimonial</a></x-slot:actions>
    <div class="card overflow-x-auto p-0"><table class="table"><thead><tr><th>Name</th><th>Quote</th><th>Treatment</th><th>Flags</th><th></th></tr></thead><tbody>
        @foreach ($testimonials as $t)<tr><td class="font-semibold">{{ $t->name }}</td><td class="max-w-md truncate text-stone">{{ $t->quote }}</td><td class="text-xs">{{ $t->treatment }}</td><td class="space-x-1">@if ($t->featured)<span class="chip tone-gold">Home page</span>@endif @if ($t->video_url)<span class="chip tone-blue">Video</span>@endif @unless ($t->active)<span class="chip tone-slate">Hidden</span>@endunless</td><td class="text-right whitespace-nowrap"><a href="{{ route('admin.testimonials.edit', $t) }}" class="btn-light btn-sm">Edit</a> <form method="post" action="{{ route('admin.testimonials.destroy', $t) }}" class="inline" onsubmit="return confirm('Remove this testimonial?')">@csrf @method('delete')<button class="btn-light btn-sm text-rose">Remove</button></form></td></tr>@endforeach
    </tbody></table></div>
</x-admin.layout>
