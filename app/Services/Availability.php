<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Open booking slots from the clinic hours, the slot size, the number of chairs and existing appointments.
 * A slot is open when fewer than `chairs` appointments overlap it, and (for a fixed-provider type) that provider is free.
 */
class Availability
{
    private const DAYS = ['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'];

    /** @return array<string, array{0: string, 1: string}|null> */
    public function hours(): array
    {
        $hours = Setting::get('hours');

        return is_array($hours) ? $hours : config('clinic.hours');
    }

    /** @return array{slot_minutes: int, chairs: int, lead_hours: int, horizon_days: int, notify: string|null} */
    public function rules(): array
    {
        $rules = Setting::get('booking');

        return [
            'slot_minutes' => max(5, (int) ($rules['slot_minutes'] ?? 30)),
            'chairs' => max(1, (int) ($rules['chairs'] ?? 1)),
            'lead_hours' => max(0, (int) ($rules['lead_hours'] ?? 0)),
            'horizon_days' => max(1, (int) ($rules['horizon_days'] ?? 60)),
            'notify' => $rules['notify'] ?? null,
        ];
    }

    public function horizonDays(): int
    {
        return $this->rules()['horizon_days'];
    }

    public function isOpenOn(Carbon $day): bool
    {
        return ($this->hours()[strtolower($day->format('D'))] ?? null) !== null;
    }

    /**
     * Slots for one day. With $applyLead false (staff use) the minimum-notice rule is ignored.
     *
     * @return array<int, array{time: string, label: string, available: bool}>
     */
    public function slotsFor(Carbon $day, AppointmentType $type, bool $applyLead = true): array
    {
        return $this->slotsWith($day, $type, Appointment::blocking()->onDate($day)->get(['starts_at', 'ends_at', 'team_member_id']), $applyLead);
    }

    /** Can a patient book this exact start online? Checks the horizon, the hours, the lead time and the capacity. */
    public function isAvailable(Carbon $start, AppointmentType $type): bool
    {
        if ($start->isPast() || $start->gt(now()->addDays($this->horizonDays())->endOfDay())) {
            return false;
        }
        foreach ($this->slotsFor($start->copy()->startOfDay(), $type) as $slot) {
            if ($slot['time'] === $start->format('H:i')) {
                return $slot['available'];
            }
        }

        return false;
    }

    /**
     * Days in the booking horizon with at least one open slot, for the calendar.
     *
     * @return array<int, string>
     */
    public function openDays(AppointmentType $type): array
    {
        $out = [];
        $this->eachOpenDay($type, function (Carbon $day, array $slots) use (&$out) {
            if (collect($slots)->contains('available', true)) {
                $out[] = $day->toDateString();
            }
        });

        return $out;
    }

    /** The earliest open slot for a type within the horizon, or null. */
    public function nextSlot(AppointmentType $type): ?Carbon
    {
        $found = null;
        $this->eachOpenDay($type, function (Carbon $day, array $slots) use (&$found) {
            foreach ($slots as $slot) {
                if ($slot['available']) {
                    $found = $day->copy()->setTimeFromTimeString($slot['time']);

                    return false;
                }
            }

            return true;
        });

        return $found;
    }

    /** @return array<string, string> Day name => "8:00 AM – 3:30 PM" or "Closed". */
    public function hoursForDisplay(): array
    {
        $out = [];
        foreach (self::DAYS as $key => $name) {
            $out[$name] = $this->formatWindow($this->hours()[$key] ?? null);
        }

        return $out;
    }

    /**
     * Consecutive days with the same hours, for compact display.
     *
     * @return array<int, array{days: string, hours: string}>
     */
    public function hoursGrouped(): array
    {
        $groups = [];
        foreach (array_keys(self::DAYS) as $key) {
            $label = $this->formatWindow($this->hours()[$key] ?? null);
            if ($groups !== [] && $groups[count($groups) - 1]['hours'] === $label) {
                $groups[count($groups) - 1]['to'] = $key;
            } else {
                $groups[] = ['from' => $key, 'to' => $key, 'hours' => $label];
            }
        }

        return array_map(fn (array $g) => [
            'days' => $g['from'] === $g['to'] ? self::DAYS[$g['from']] : ucfirst($g['from']).' – '.ucfirst($g['to']),
            'hours' => $g['hours'],
        ], $groups);
    }

    /** The first open group as one line, e.g. "Mon – Sat, 8:00 AM – 3:30 PM". */
    public function hoursSummary(): string
    {
        foreach ($this->hoursGrouped() as $g) {
            if ($g['hours'] !== 'Closed') {
                return $g['days'].', '.$g['hours'];
            }
        }

        return 'By appointment';
    }

    /** @param array{0: string, 1: string}|null $window */
    private function formatWindow(?array $window): string
    {
        return $window ? Carbon::parse($window[0])->format('g:i A').' – '.Carbon::parse($window[1])->format('g:i A') : 'Closed';
    }

    /**
     * Walk every open day in the horizon with its slots, loading all blocking appointments in one query.
     *
     * @param  callable(Carbon, array<int, array{time: string, label: string, available: bool}>): (bool|void)  $callback  return false to stop
     */
    private function eachOpenDay(AppointmentType $type, callable $callback): void
    {
        $from = now()->startOfDay();
        $to = now()->addDays($this->horizonDays())->endOfDay();
        $byDay = Appointment::blocking()->whereBetween('starts_at', [$from, $to])->get(['starts_at', 'ends_at', 'team_member_id'])
            ->groupBy(fn (Appointment $a) => $a->starts_at->toDateString());

        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            if (! $this->isOpenOn($d)) {
                continue;
            }
            /** @var Collection<int, Appointment> $existing */
            $existing = $byDay->get($d->toDateString(), new Collection);
            if ($callback($d->copy(), $this->slotsWith($d->copy(), $type, $existing, true)) === false) {
                return;
            }
        }
    }

    /**
     * @param  Collection<int, Appointment>  $existing  blocking appointments on that day
     * @return array<int, array{time: string, label: string, available: bool}>
     */
    private function slotsWith(Carbon $day, AppointmentType $type, Collection $existing, bool $applyLead): array
    {
        $window = $this->hours()[strtolower($day->format('D'))] ?? null;
        if (! $window) {
            return [];
        }
        $rules = $this->rules();
        $earliest = $applyLead ? now()->addHours($rules['lead_hours']) : now();
        $duration = max(5, (int) $type->duration_minutes);
        [$open, $close] = [$day->copy()->setTimeFromTimeString($window[0]), $day->copy()->setTimeFromTimeString($window[1])];

        $slots = [];
        for ($t = $open->copy(); $t->copy()->addMinutes($duration)->lte($close); $t->addMinutes($rules['slot_minutes'])) {
            $end = $t->copy()->addMinutes($duration);
            $overlapping = $existing->filter(fn (Appointment $a) => $a->starts_at->lt($end) && $a->ends_at->gt($t));
            $providerBusy = $type->team_member_id !== null && $overlapping->contains(fn (Appointment $a) => $a->team_member_id === $type->team_member_id);
            $slots[] = ['time' => $t->format('H:i'), 'label' => $t->format('g:i A'), 'available' => $t->gte($earliest) && $overlapping->count() < $rules['chairs'] && ! $providerBusy];
        }

        return $slots;
    }
}
