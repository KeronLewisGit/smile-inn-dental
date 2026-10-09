<x-admin.layout title="Journal posts">
    <x-slot:actions><a href="{{ route('admin.posts.create') }}" class="btn-gold btn-sm">New post</a></x-slot:actions>
    <form class="mb-5 flex gap-2"><input name="q" value="{{ request('q') }}" placeholder="Search titles" class="input input-sm max-w-sm"><button class="btn-ink btn-sm">Search</button></form>
    <div class="card overflow-x-auto p-0"><table class="table"><thead><tr><th>Title</th><th>Category</th><th>Published</th><th></th></tr></thead><tbody>
        @foreach ($posts as $p)<tr><td><span class="font-semibold">{{ $p->title }}</span>@if ($p->featured)<span class="ml-2 chip tone-gold">Featured</span>@endif</td><td>{{ $p->category }}</td><td class="text-xs text-stone">{{ $p->isPublished() ? $p->published_at->format('j M Y') : ($p->published_at ? 'Scheduled '.$p->published_at->format('j M Y') : 'Draft') }}</td><td class="text-right whitespace-nowrap"><a href="{{ route('blog.show', $p) }}" target="_blank" class="btn-light btn-sm">View</a> <a href="{{ route('admin.posts.edit', $p) }}" class="btn-light btn-sm">Edit</a></td></tr>@endforeach
    </tbody></table></div>
    <div class="mt-5">{{ $posts->links() }}</div>
</x-admin.layout>
