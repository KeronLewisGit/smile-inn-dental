<x-admin.layout :title="$post->exists ? 'Edit post' : 'New post'">
    <form method="post" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" enctype="multipart/form-data" class="grid max-w-5xl gap-6 lg:grid-cols-3">@csrf @if ($post->exists) @method('patch') @endif
        <div class="card space-y-4 lg:col-span-2">
            <x-admin.field label="Title" name="title" required><input name="title" value="{{ old('title', $post->title) }}" required class="input text-lg"></x-admin.field>
            <x-admin.field label="Excerpt" name="excerpt" hint="Shown on cards and as the meta description."><textarea name="excerpt" rows="2" class="input">{{ old('excerpt', $post->excerpt) }}</textarea></x-admin.field>
            <x-admin.field label="Body (HTML)" name="body" hint="Use <h2> for section headings and <p> for paragraphs."><textarea name="body" rows="22" class="input font-mono text-sm">{{ old('body', $post->body) }}</textarea></x-admin.field>
        </div>
        <div class="space-y-6">
            <div class="card space-y-4">
                <x-admin.field label="Category" name="category"><select name="category" class="input">@foreach (\App\Models\Post::CATEGORIES as $c)<option @selected(old('category', $post->category) === $c)>{{ $c }}</option>@endforeach</select></x-admin.field>
                <x-admin.field label="Publish date" name="published_at" hint="Blank keeps it as a draft."><input name="published_at" type="datetime-local" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" class="input"></x-admin.field>
                <x-admin.field label="Slug" name="slug" hint="Blank = generated from the title."><input name="slug" value="{{ old('slug', $post->slug) }}" class="input font-mono text-sm"></x-admin.field>
                <x-admin.field label="Cover image" name="cover_image">@if ($post->cover_image)<img src="{{ $post->coverUrl() }}" alt="" class="mb-2 h-24 w-full rounded-xl object-cover">@endif<input name="cover_image" type="file" accept="image/*" class="input"></x-admin.field>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="featured" value="1" class="check" @checked(old('featured', $post->featured))>Feature at the top of the journal</label>
                <div class="flex gap-2"><button class="btn-gold">Save</button><a href="{{ route('admin.posts.index') }}" class="btn-light">Back</a></div>
            </div>
            @if ($post->exists)<form method="post" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?')" class="text-right">@csrf @method('delete')<button class="text-xs font-semibold text-rose hover:underline">Delete post</button></form>@endif
        </div>
    </form>
</x-admin.layout>
