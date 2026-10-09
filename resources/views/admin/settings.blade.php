<x-admin.layout title="Hours & booking">
    <form method="post" action="{{ route('admin.settings.update') }}" class="grid max-w-4xl gap-6 lg:grid-cols-2">@csrf @method('put')
        <section class="card"><h2 class="font-display text-2xl">Opening hours</h2><p class="mt-1 text-sm text-stone">Shown on the website and used to generate online booking slots.</p>
            <div class="mt-5 space-y-3">@foreach ($days as $key => $label)@php($h = $hours[$key] ?? null)<div class="grid grid-cols-[6rem_1fr_1fr_auto] items-center gap-2 text-sm" x-data="{ closed: {{ $h ? 'false' : 'true' }} }"><span class="font-semibold">{{ $label }}</span><input type="time" name="hours[{{ $key }}][open]" value="{{ old("hours.$key.open", $h[0] ?? '08:00') }}" class="input input-sm" :disabled="closed"><input type="time" name="hours[{{ $key }}][close]" value="{{ old("hours.$key.close", $h[1] ?? '15:30') }}" class="input input-sm" :disabled="closed"><label class="flex items-center gap-1.5 text-xs"><input type="checkbox" name="hours[{{ $key }}][closed]" value="1" x-model="closed" class="check">Closed</label></div>@endforeach</div>
        </section>
        <section class="card space-y-4"><h2 class="font-display text-2xl">Online booking rules</h2>
            <x-admin.field label="Slot size (minutes)" name="slot_minutes" hint="How often slots start."><select name="slot_minutes" class="input">@foreach ([15, 20, 30, 45, 60] as $m)<option value="{{ $m }}" @selected((int) old('slot_minutes', $booking['slot_minutes']) === $m)>{{ $m }}</option>@endforeach</select></x-admin.field>
            <x-admin.field label="Chairs (appointments at the same time)" name="chairs" hint="A slot stays open until this many appointments overlap it."><input name="chairs" type="number" min="1" max="20" value="{{ old('chairs', $booking['chairs']) }}" class="input"></x-admin.field>
            <x-admin.field label="Minimum notice (hours)" name="lead_hours"><input name="lead_hours" type="number" min="0" max="168" value="{{ old('lead_hours', $booking['lead_hours']) }}" class="input"></x-admin.field>
            <x-admin.field label="How far ahead patients can book (days)" name="horizon_days"><input name="horizon_days" type="number" min="7" max="365" value="{{ old('horizon_days', $booking['horizon_days']) }}" class="input"></x-admin.field>
            <x-admin.field label="Notification email" name="notify" hint="New bookings and enquiries are sent here."><input name="notify" type="email" value="{{ old('notify', $booking['notify']) }}" class="input"></x-admin.field>
            <button class="btn-gold">Save settings</button>
        </section>
    </form>
</x-admin.layout>
