<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\Patient;
use App\Models\Setting;
use App\Notifications\AppointmentMail;
use App\Services\Availability;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private Availability $availability) {}

    public function create(Request $request): View
    {
        $types = AppointmentType::active()->with('provider')->get();
        $selected = $request->filled('type') ? $types->firstWhere('slug', $request->query('type')) : null;

        return view('site.book', ['types' => $types, 'selected' => $selected, 'hours' => $this->availability->hoursForDisplay(), 'horizon' => $this->availability->horizonDays()]);
    }

    /** JSON: open days for the type, and the slots for a given date. */
    public function slots(Request $request): JsonResponse
    {
        $data = $request->validate(['type' => ['required', 'exists:appointment_types,slug'], 'date' => ['nullable', 'date_format:Y-m-d']]);
        $type = AppointmentType::where('slug', $data['type'])->where('active', true)->firstOrFail();
        $out = ['open_days' => $this->availability->openDays($type)];
        if (! empty($data['date'])) {
            $out['slots'] = $this->availability->slotsFor(Carbon::createFromFormat('Y-m-d', $data['date'])->startOfDay(), $type);
        }

        return response()->json($out);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'exists:appointment_types,slug'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'new_patient' => ['nullable', 'boolean'],
            'marketing_opt_in' => ['nullable', 'boolean'],
            'website' => ['nullable', 'max:0'], // honeypot
        ], ['website.max' => 'Something went wrong. Please try again.', 'date.date_format' => 'Please pick a day from the calendar.']);

        $type = AppointmentType::where('slug', $data['type'])->where('active', true)->firstOrFail();
        $start = Carbon::createFromFormat('Y-m-d H:i', $data['date'].' '.$data['time']);

        // One booking at a time per day, so two patients cannot both take the last chair in the same slot.
        try {
            $appointment = Cache::lock('booking:'.$data['date'], 10)->block(5, function () use ($data, $type, $start, $request) {
                return DB::transaction(function () use ($data, $type, $start, $request) {
                    if (! $this->availability->isAvailable($start, $type)) {
                        throw ValidationException::withMessages(['time' => 'That time was just taken. Please pick another slot.']);
                    }
                    $patient = Patient::findOrCreateFrom($data + ['source' => 'website']);
                    $notes = trim(($request->boolean('new_patient') ? "New patient.\n" : '').($data['notes'] ?? ''));

                    return Appointment::create([
                        'patient_id' => $patient->id, 'appointment_type_id' => $type->id, 'team_member_id' => $type->team_member_id,
                        'starts_at' => $start, 'ends_at' => $start->copy()->addMinutes($type->duration_minutes), 'status' => 'pending', 'patient_notes' => $notes ?: null, 'source' => 'website',
                    ]);
                });
            });
        } catch (LockTimeoutException) {
            throw ValidationException::withMessages(['time' => 'We are very busy right now. Please try again in a moment.']);
        }

        $this->notify($appointment, 'requested');

        return redirect()->route('book.done', $appointment->manage_token);
    }

    /** Confirmation page, reached only through the private manage token so references cannot be guessed. */
    public function done(string $token): View
    {
        return view('site.book-done', ['appointment' => $this->byToken($token)]);
    }

    public function manage(string $token): View
    {
        return view('site.book-manage', ['appointment' => $this->byToken($token)]);
    }

    public function cancel(Request $request, string $token): RedirectResponse
    {
        $appointment = Appointment::where('manage_token', $token)->firstOrFail();
        if (! $appointment->isOpen()) {
            return back()->with('status', 'This appointment is already '.strtolower($appointment->statusName()).'.');
        }
        $reason = $request->validate(['reason' => ['nullable', 'string', 'max:255']])['reason'] ?? null;
        $appointment->forceFill(['status' => 'cancelled', 'cancelled_at' => now(), 'cancellation_reason' => $reason ?: 'Cancelled by patient'])->save();
        $this->notify($appointment, 'cancelled');

        return back()->with('status', 'Your appointment has been cancelled. We hope to see you another time.');
    }

    /** Email the patient and the clinic. A mail failure is reported but never blocks the booking. */
    public static function notify(Appointment $appointment, string $event): void
    {
        $appointment->loadMissing('patient');
        try {
            if ($appointment->patient->email) {
                Notification::route('mail', [$appointment->patient->email => $appointment->patient->fullName()])->notify(new AppointmentMail($appointment, $event));
            }
            if ($to = Setting::get('booking')['notify'] ?? null) {
                Notification::route('mail', $to)->notify(new AppointmentMail($appointment, $event, forClinic: true));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function byToken(string $token): Appointment
    {
        return Appointment::where('manage_token', $token)->with('patient', 'type', 'provider')->firstOrFail();
    }
}
