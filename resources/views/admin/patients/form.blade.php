<x-admin.layout :title="$patient->exists ? 'Edit patient' : 'Add patient'">
    <form method="post" action="{{ $patient->exists ? route('admin.patients.update', $patient) : route('admin.patients.store') }}" class="card grid max-w-3xl gap-4 sm:grid-cols-2">@csrf @if ($patient->exists) @method('patch') @endif
        <x-admin.field label="First name" name="first_name" required><input id="first_name" name="first_name" value="{{ old('first_name', $patient->first_name) }}" required class="input"></x-admin.field>
        <x-admin.field label="Last name" name="last_name" required><input id="last_name" name="last_name" value="{{ old('last_name', $patient->last_name) }}" required class="input"></x-admin.field>
        <x-admin.field label="Phone" name="phone"><input id="phone" name="phone" value="{{ old('phone', $patient->phone) }}" class="input"></x-admin.field>
        <x-admin.field label="Email" name="email"><input id="email" name="email" type="email" value="{{ old('email', $patient->email) }}" class="input"></x-admin.field>
        <x-admin.field label="Date of birth" name="date_of_birth"><input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', $patient->date_of_birth?->toDateString()) }}" class="input"></x-admin.field>
        <x-admin.field label="Gender" name="gender"><select id="gender" name="gender" class="input"><option value="">—</option>@foreach (['Female', 'Male', 'Prefer not to say'] as $g)<option @selected(old('gender', $patient->gender) === $g)>{{ $g }}</option>@endforeach</select></x-admin.field>
        <x-admin.field label="Notes" name="notes" class="sm:col-span-2" hint="Internal. Allergies, preferences, anything the team should know."><textarea id="notes" name="notes" rows="4" class="input">{{ old('notes', $patient->notes) }}</textarea></x-admin.field>
        <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" name="marketing_opt_in" value="1" class="check" @checked(old('marketing_opt_in', $patient->marketing_opt_in))>Opted in to marketing emails</label>
        <div class="flex gap-2 sm:col-span-2"><button class="btn-gold">Save</button><a href="{{ $patient->exists ? route('admin.patients.show', $patient) : route('admin.patients.index') }}" class="btn-light">Cancel</a></div>
    </form>
</x-admin.layout>
