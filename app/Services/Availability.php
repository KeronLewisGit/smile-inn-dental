<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\Setting;
use Illuminate\Support\Carbon;

/**
 * Open booking slots from the clinic hours, the slot size, the number of chairs and existing appointments.
 * A slot is open when fewer than `chairs` appointments overlap it, and (for a fixed-provider type) that provider is free.
 */
class Availability
{
    public function hours(): array
    {
        return Setting::get('hours');
    }

    public function isOpenOn(Carbon $day): bool
    {
        return $this->hours()[strtolower($day->format('D'))] !== null;
    }

    /** @return array<int, array{time: string, label: string, available: bool}> */
    public function slotsFor(Carbon $day, AppointmentType $type): array
    {
        $window = $this->hours()[strtolower($day->format('D'))] ?? null;
        if (! $window) {
            return [];
        }
        $booking = Setting::get('booking');
        $step = (int) $booking['slot_minutes'];
        $chairs = (int) $booking['chairs'];
        $earliest = now()->addHours((int) $booking['lead_hours']);
        [$open, $close] = [$day->copy()->setTimeFromTimeString($window[0]), $day->copy()->setTimeFromTimeString($window[1])];

        $existing = Appointment::blocking()->onDate($day)->get(['starts_at', 'ends_at', 'team_member_id']);
        $slots = [];
        for ($t = $open->copy(); $t->copy()->addMinutes($type->duration_minutes)->lte($close); $t->addMinutes($step)) {
            $end = $t->copy()->addMinutes($type->duration_minutes);
            $overlapping = $existing->filter(fn ($a) => $a->starts_at->lt($end) && $a->ends_at->gt($t));
            $providerBusy = $type->team_member_id && $overlapping->contains(fn ($a) => $a->team_member_id === $type->team_member_id);
            $slots[] = ['time' => $t->format('H:i'), 'label' => $t->format('g:i A'), 'available' => $t->gte($earliest) && $overlapping->count() < $chairs && ! $providerBusy];
        }

        return $slots;
    }

    public function isAvailable(Carbon $start, AppointmentType $type): bool
    {
        foreach ($this->slotsFor($start->copy()->startOfDay(), $type) as $slot) {
            if ($slot['time'] === $start->format('H:i')) {
                return $slot['available'];
            }
        }

        return false;
    }

    /** Days in the booking horizon with at least one open slot, for the calendar. */
    public function openDays(AppointmentType $type): array
    {
        $out = [];
        $horizon = (int) Setting::get('booking')['horizon_days'];
        for ($d = now()->startOfDay(); $d->lte(now()->addDays($horizon)); $d->addDay()) {
            if ($this->isOpenOn($d) && collect($this->slotsFor($d->copy(), $type))->contains('available', true)) {
                $out[] = $d->toDateString();
            }
        }

        return $out;
    }

    /** The earliest open slot for a type within the horizon, or null. */
    public function nextSlot(AppointmentType $type): ?Carbon
    {
        $horizon = (int) Setting::get('booking')['horizon_days'];
        for ($d = now()->startOfDay(); $d->lte(now()->addDays($horizon)); $d->addDay()) {
            if (! $this->isOpenOn($d)) {
                continue;
            }
            foreach ($this->slotsFor($d->copy(), $type) as $slot) {
                if ($slot['available']) {
                    return $d->copy()->setTimeFromTimeString($slot['time']);
                }
            }
        }

        return null;
    }

    public function hoursForDisplay(): array
    {
        $names = ['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'];
        $out = [];
        foreach ($this->hours() as $key => $w) {
            $out[$names[$key]] = $w ? Carbon::parse($w[0])->format('g:i A').' – '.Carbon::parse($w[1])->format('g:i A') : 'Closed';
        }

        return $out;
    }
}
