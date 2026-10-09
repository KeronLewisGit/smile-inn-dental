<x-admin.layout :title="$member->exists ? 'Edit '.$member->name : 'Add team member'">
    <form method="post" action="{{ $member->exists ? route('admin.team.update', $member) : route('admin.team.store') }}" enctype="multipart/form-data" class="card grid max-w-3xl gap-4 sm:grid-cols-2">@csrf @if ($member->exists) @method('patch') @endif
        <x-admin.field label="Name" name="name" required><input name="name" value="{{ old('name', $member->name) }}" required class="input" placeholder="Dr. First Last"></x-admin.field>
        <x-admin.field label="Title" name="title" required><input name="title" value="{{ old('title', $member->title) }}" required class="input"></x-admin.field>
        <x-admin.field label="Bio" name="bio" class="sm:col-span-2"><textarea name="bio" rows="5" class="input">{{ old('bio', $member->bio) }}</textarea></x-admin.field>
        <x-admin.field label="Photo" name="photo" hint="Portrait, at least 800px tall.">@if ($member->photo)<img src="{{ $member->photoUrl() }}" alt="" class="mb-2 size-20 rounded-2xl object-cover">@endif<input name="photo" type="file" accept="image/*" class="input"></x-admin.field>
        <x-admin.field label="Instagram URL" name="instagram"><input name="instagram" value="{{ old('instagram', $member->instagram) }}" class="input"></x-admin.field>
        <x-admin.field label="Display order" name="sort"><input name="sort" type="number" min="0" value="{{ old('sort', $member->sort) }}" class="input"></x-admin.field>
        <div class="space-y-2 text-sm sm:col-span-2"><label class="flex items-center gap-2"><input type="checkbox" name="is_dentist" value="1" class="check" @checked(old('is_dentist', $member->is_dentist))>Dentist (shown in the dentists row)</label><label class="flex items-center gap-2"><input type="checkbox" name="accepts_bookings" value="1" class="check" @checked(old('accepts_bookings', $member->accepts_bookings))>Can be assigned appointments</label><label class="flex items-center gap-2"><input type="checkbox" name="active" value="1" class="check" @checked(old('active', $member->active))>Visible on the website</label></div>
        <div class="flex gap-2 sm:col-span-2"><button class="btn-gold">Save</button><a href="{{ route('admin.team.index') }}" class="btn-light">Cancel</a></div>
    </form>
</x-admin.layout>
