<x-admin.layout :title="$service->exists ? 'Edit '.$service->name : 'Add service'">
    <form method="post" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" enctype="multipart/form-data" class="card grid max-w-4xl gap-4 sm:grid-cols-2">@csrf @if ($service->exists) @method('patch') @endif
        <x-admin.field label="Name" name="name" required><input name="name" value="{{ old('name', $service->name) }}" required class="input"></x-admin.field>
        <x-admin.field label="Tagline" name="tagline"><input name="tagline" value="{{ old('tagline', $service->tagline) }}" class="input"></x-admin.field>
        <x-admin.field label="Intro" name="intro" class="sm:col-span-2" hint="One or two sentences shown at the top of the page and on cards."><textarea name="intro" rows="3" class="input">{{ old('intro', $service->intro) }}</textarea></x-admin.field>
        <x-admin.field label="Body" name="body" class="sm:col-span-2"><textarea name="body" rows="5" class="input">{{ old('body', $service->body) }}</textarea></x-admin.field>
        <x-admin.field label="Treatments" name="treatments" class="sm:col-span-2" hint="One per line: Name | Description"><textarea name="treatments" rows="10" class="input font-mono text-sm">{{ old('treatments', $treatments) }}</textarea></x-admin.field>
        <x-admin.field label="FAQs" name="faqs" class="sm:col-span-2" hint="One per line: Question | Answer"><textarea name="faqs" rows="5" class="input font-mono text-sm">{{ old('faqs', $faqs) }}</textarea></x-admin.field>
        <x-admin.field label="Icon" name="icon"><select name="icon" class="input">@foreach (['tooth', 'sparkle', 'child', 'aligner', 'scalpel', 'alert', 'heart', 'shield', 'scan'] as $i)<option @selected(old('icon', $service->icon) === $i)>{{ $i }}</option>@endforeach</select></x-admin.field>
        <x-admin.field label="Image" name="image">@if ($service->image)<img src="{{ asset($service->image) }}" alt="" class="mb-2 h-20 rounded-xl object-cover">@endif<input name="image" type="file" accept="image/*" class="input"></x-admin.field>
        <x-admin.field label="Display order" name="sort"><input name="sort" type="number" min="0" value="{{ old('sort', $service->sort) }}" class="input"></x-admin.field>
        <div class="space-y-2 self-end text-sm"><label class="flex items-center gap-2"><input type="checkbox" name="featured" value="1" class="check" @checked(old('featured', $service->featured))>Show on home page</label><label class="flex items-center gap-2"><input type="checkbox" name="active" value="1" class="check" @checked(old('active', $service->active))>Visible on the website</label></div>
        <div class="flex gap-2 sm:col-span-2"><button class="btn-gold">Save</button><a href="{{ route('admin.services.index') }}" class="btn-light">Cancel</a></div>
    </form>
</x-admin.layout>
