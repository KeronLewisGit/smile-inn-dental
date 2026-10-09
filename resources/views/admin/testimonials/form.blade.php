<x-admin.layout :title="$testimonial->exists ? 'Edit testimonial' : 'Add testimonial'">
    <form method="post" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" class="card grid max-w-3xl gap-4 sm:grid-cols-2">@csrf @if ($testimonial->exists) @method('patch') @endif
        <x-admin.field label="Name" name="name" required><input name="name" value="{{ old('name', $testimonial->name) }}" required class="input"></x-admin.field>
        <x-admin.field label="Treatment" name="treatment"><input name="treatment" value="{{ old('treatment', $testimonial->treatment) }}" class="input" placeholder="Invisalign"></x-admin.field>
        <x-admin.field label="Quote" name="quote" required class="sm:col-span-2"><textarea name="quote" rows="4" required class="input">{{ old('quote', $testimonial->quote) }}</textarea></x-admin.field>
        <x-admin.field label="Rating" name="rating"><select name="rating" class="input">@foreach ([5, 4, 3, 2, 1] as $r)<option value="{{ $r }}" @selected((int) old('rating', $testimonial->rating) === $r)>{{ $r }} stars</option>@endforeach</select></x-admin.field>
        <x-admin.field label="Source" name="source"><input name="source" value="{{ old('source', $testimonial->source) }}" class="input" placeholder="Google"></x-admin.field>
        <x-admin.field label="Video URL (optional)" name="video_url" hint="Instagram reel or YouTube link. Turns this into a video card."><input name="video_url" value="{{ old('video_url', $testimonial->video_url) }}" class="input"></x-admin.field>
        <x-admin.field label="Display order" name="sort"><input name="sort" type="number" min="0" value="{{ old('sort', $testimonial->sort) }}" class="input"></x-admin.field>
        <div class="space-y-2 text-sm sm:col-span-2"><label class="flex items-center gap-2"><input type="checkbox" name="featured" value="1" class="check" @checked(old('featured', $testimonial->featured))>Show on home page</label><label class="flex items-center gap-2"><input type="checkbox" name="active" value="1" class="check" @checked(old('active', $testimonial->active))>Visible</label></div>
        <div class="flex gap-2 sm:col-span-2"><button class="btn-gold">Save</button><a href="{{ route('admin.testimonials.index') }}" class="btn-light">Cancel</a></div>
    </form>
</x-admin.layout>
