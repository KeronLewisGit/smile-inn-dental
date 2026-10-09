<x-admin.layout title="New appointment">
    <form method="post" action="{{ route('admin.appointments.store') }}" class="grid max-w-4xl gap-6 lg:grid-cols-2" x-data="{ ...adminSlots(@js(route('admin.appointments.slots'))), type: @js(old('appointment_type_id', '')), date: @js(old('date', $appointment->starts_at?->toDateString())), time: @js(old('time', '')), mode: @js(old('patient_id') ? 'existing' : 'new') }" x-init="$watch('type', () => load(type, date, time)); $watch('date', () => load(type, date, time)); load(type, date, time)">
        @csrf
        <section class="card space-y-4">
            <h2 class="font-display text-2xl">Patient</h2>
            <div class="flex gap-2"><button type="button" @click="mode = 'existing'" class="btn-sm btn" :class="mode === 'existing' ? 'bg-ink text-ivory' : 'bg-white ring-1 ring-line'">Existing patient</button><button type="button" @click="mode = 'new'" class="btn-sm btn" :class="mode === 'new' ? 'bg-ink text-ivory' : 'bg-white ring-1 ring-line'">New patient</button></div>
            <div x-show="mode === 'existing'"><x-admin.field label="Patient" name="patient_id"><select id="patient_id" name="patient_id" class="input" :disabled="mode !== 'existing'"><option value="">Choose…</option>@foreach ($patients as $p)<option value="{{ $p->id }}" @selected((int) old('patient_id', $appointment->patient_id) === $p->id)>{{ $p->last_name }}, {{ $p->first_name }} · {{ $p->phone ?: $p->email }}</option>@endforeach</select></x-admin.field></div>
            <div x-show="mode === 'new'" class="grid gap-4 sm:grid-cols-2">
                <x-admin.field label="First name" name="first_name"><input id="first_name" name="first_name" value="{{ old('first_name') }}" class="input" :disabled="mode !== 'new'"></x-admin.field>
                <x-admin.field label="Last name" name="last_name"><input id="last_name" name="last_name" value="{{ old('last_name') }}" class="input" :disabled="mode !== 'new'"></x-admin.field>
                <x-admin.field label="Phone" name="phone"><input id="phone" name="phone" value="{{ old('phone') }}" class="input" :disabled="mode !== 'new'"></x-admin.field>
                <x-admin.field label="Email" name="email"><input id="email" name="email" type="email" value="{{ old('email') }}" class="input" :disabled="mode !== 'new'"></x-admin.field>
            </div>
        </section>
        <section class="card space-y-4">
            <h2 class="font-display text-2xl">When</h2>
            <x-admin.field label="Type" name="appointment_type_id"><select id="appointment_type_id" name="appointment_type_id" x-model="type" class="input"><option value="">Choose…</option>@foreach ($types as $t)<option value="{{ $t->id }}">{{ $t->name }} ({{ $t->duration_minutes }} min)</option>@endforeach</select></x-admin.field>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.field label="Date" name="date" required><input id="date" name="date" type="date" x-model="date" required class="input"></x-admin.field>
                <x-admin.field label="Time" name="time" required><input id="time" name="time" type="time" x-model="time" required class="input" step="300"></x-admin.field>
            </div>
            <div x-show="slots.length"><p class="label">Open slots</p><div class="flex flex-wrap gap-1.5"><template x-for="s in slots" :key="s.time"><button type="button" :disabled="!s.available" @click="time = s.time" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold ring-1 disabled:opacity-30" :class="time === s.time ? 'bg-ink text-gold ring-ink' : 'bg-white ring-line'" x-text="s.label"></button></template></div></div>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.field label="Provider" name="team_member_id"><select id="team_member_id" name="team_member_id" class="input"><option value="">Any / unassigned</option>@foreach ($providers as $p)<option value="{{ $p->id }}" @selected((int) old('team_member_id') === $p->id)>{{ $p->name }}</option>@endforeach</select></x-admin.field>
                <x-admin.field label="Duration (min)" name="duration" hint="Blank = the type's default"><input id="duration" name="duration" type="number" min="10" max="480" step="5" value="{{ old('duration') }}" class="input"></x-admin.field>
            </div>
            <x-admin.field label="Status" name="status"><select id="status" name="status" class="input"><option value="confirmed">Confirmed</option><option value="pending" @selected(old('status') === 'pending')>Pending</option></select></x-admin.field>
            <x-admin.field label="Staff notes" name="staff_notes"><textarea id="staff_notes" name="staff_notes" rows="2" class="input">{{ old('staff_notes') }}</textarea></x-admin.field>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="notify" value="1" class="check" checked>Email the patient a confirmation</label>
            <div class="flex gap-2"><button class="btn-gold">Book appointment</button><a href="{{ route('admin.appointments.index') }}" class="btn-light">Cancel</a></div>
        </section>
    </form>
</x-admin.layout>
