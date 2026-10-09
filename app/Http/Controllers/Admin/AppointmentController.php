<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\Patient;
use App\Models\TeamMember;
use App\Services\Availability;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function __construct(private Availability $availability) {}

    public function index(Request $request): View
    {
        $term = trim((string) $request->query('q', ''));
        $appointments = Appointment::with('patient', 'type', 'provider')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->query('status')))
            ->when($request->filled('provider'), fn ($q) => $q->where('team_member_id', $request->integer('provider')))
            ->when($term !== '', fn ($q) => $q->where(fn ($w) => $w->where('reference', 'like', '%'.$term.'%')->orWhereHas('patient', fn ($p) => $p->where('first_name', 'like', '%'.$term.'%')->orWhere('last_name', 'like', '%'.$term.'%')->orWhere('email', 'like', '%'.$term.'%')->orWhere('phone', 'like', '%'.$term.'%'))))
            ->when($request->query('range') === 'past', fn ($q) => $q->where('starts_at', '<', now())->orderByDesc('starts_at'), fn ($q) => $q->where('starts_at', '>=', now()->startOfDay())->orderBy('starts_at'))
            ->paginate(25)->withQueryString();

        return view('admin.appointments.index', ['appointments' => $appointments, 'providers' => TeamMember::active()->where('accepts_bookings', true)->get(), 'statuses' => Appointment::STATUSES]);
    }

    public function calendar(Request $request): View
    {
        // A malformed ?week= falls back to the current week instead of a 500.
        $week = rescue(fn () => Carbon::parse((string) $request->query('week', 'now')), now(), false)->startOfWeek();
        $appointments = Appointment::with('patient', 'type', 'provider')->whereBetween('starts_at', [$week, $week->copy()->endOfWeek()])->whereIn('status', ['pending', 'confirmed', 'completed'])->orderBy('starts_at')->get()
            ->groupBy(fn (Appointment $a) => $a->starts_at->toDateString());

        return view('admin.appointments.calendar', ['week' => $week, 'days' => collect(range(0, 6))->map(fn ($i) => $week->copy()->addDays($i)), 'byDay' => $appointments, 'hours' => $this->availability->hours()]);
    }

    public function create(Request $request): View
    {
        return view('admin.appointments.form', $this->formData(new Appointment(['starts_at' => now()->addDay()->setTime(9, 0), 'status' => 'confirmed', 'patient_id' => $request->integer('patient') ?: null])));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $patient = $this->resolvePatient($data);
        $type = isset($data['appointment_type_id']) ? AppointmentType::find($data['appointment_type_id']) : null;
        $start = Carbon::createFromFormat('Y-m-d H:i', $data['date'].' '.$data['time']);
        // Staff may overbook deliberately, but say so (checked before this booking is counted).
        $note = '';
        if ($type) {
            $slot = collect($this->availability->slotsFor($start->copy()->startOfDay(), $type, applyLead: false))->firstWhere('time', $start->format('H:i'));
            $note = $slot === null ? ' Note: this time is outside the online booking hours.' : ($slot['available'] ? '' : ' Note: every chair was already taken in that slot.');
        }
        $appointment = Appointment::create([
            'patient_id' => $patient->id, 'appointment_type_id' => $type?->id, 'team_member_id' => $data['team_member_id'] ?? $type?->team_member_id,
            'starts_at' => $start, 'ends_at' => $start->copy()->addMinutes((int) ($data['duration'] ?? $type->duration_minutes ?? 30)), 'status' => $data['status'],
            'staff_notes' => $data['staff_notes'] ?? null, 'source' => 'admin', 'created_by' => $request->user()->id, 'confirmed_at' => $data['status'] === 'confirmed' ? now() : null,
        ]);
        if ($data['status'] === 'confirmed' && $request->boolean('notify')) {
            BookingController::notify($appointment, 'confirmed');
        }

        return redirect()->route('admin.appointments.show', $appointment)->with('saved', 'Appointment '.$appointment->reference.' booked.'.$note);
    }

    public function show(Appointment $appointment): View
    {
        return view('admin.appointments.show', ['appointment' => $appointment->load('patient.appointments', 'type', 'provider'), 'statuses' => Appointment::STATUSES]);
    }

    /** Reschedule, reassign, or edit notes. */
    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $data = $request->validate(['date' => ['required', 'date_format:Y-m-d'], 'time' => ['required', 'date_format:H:i'], 'duration' => ['required', 'integer', 'min:10', 'max:480'], 'team_member_id' => ['nullable', 'exists:team_members,id'], 'appointment_type_id' => ['nullable', 'exists:appointment_types,id'], 'staff_notes' => ['nullable', 'string', 'max:3000']]);
        $start = Carbon::createFromFormat('Y-m-d H:i', $data['date'].' '.$data['time']);
        $moved = ! $appointment->starts_at->equalTo($start);
        $appointment->update(['starts_at' => $start, 'ends_at' => $start->copy()->addMinutes((int) $data['duration']), 'team_member_id' => $data['team_member_id'] ?? null, 'appointment_type_id' => $data['appointment_type_id'] ?? null, 'staff_notes' => $data['staff_notes'] ?? null]);
        if ($moved && $appointment->isOpen() && $request->boolean('notify')) {
            BookingController::notify($appointment, 'rescheduled');
        }

        return back()->with('saved', $moved ? 'Appointment moved to '.$start->format('D j M, g:i A').'.' : 'Appointment updated.');
    }

    public function status(Request $request, Appointment $appointment): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:'.implode(',', array_keys(Appointment::STATUSES))], 'reason' => ['nullable', 'string', 'max:255']]);
        $status = $data['status'];
        $appointment->forceFill([
            'status' => $status,
            'confirmed_at' => $status === 'confirmed' ? ($appointment->confirmed_at ?? now()) : $appointment->confirmed_at,
            'cancelled_at' => $status === 'cancelled' ? now() : null,
            'cancellation_reason' => $status === 'cancelled' ? ($data['reason'] ?: 'Cancelled by clinic') : null,
        ])->save();
        if (in_array($status, ['confirmed', 'cancelled']) && $request->boolean('notify', true)) {
            BookingController::notify($appointment, $status);
        }

        return back()->with('saved', 'Marked '.strtolower($appointment->statusName()).'.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        abort_if($appointment->status === 'completed', 403, 'Completed appointments are part of the patient record.');
        $appointment->delete();

        return redirect()->route('admin.appointments.index')->with('saved', 'Appointment deleted.');
    }

    /** JSON slots for the admin booking form: the public engine without the minimum-notice rule. */
    public function slots(Request $request): JsonResponse
    {
        $data = $request->validate(['type' => ['required', 'exists:appointment_types,id'], 'date' => ['required', 'date_format:Y-m-d']]);

        return response()->json(['slots' => $this->availability->slotsFor(Carbon::createFromFormat('Y-m-d', $data['date'])->startOfDay(), AppointmentType::findOrFail($data['type']), applyLead: false)]);
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        return $request->validate([
            'patient_id' => ['nullable', 'exists:patients,id'],
            'first_name' => ['required_without:patient_id', 'nullable', 'string', 'max:80'], 'last_name' => ['required_without:patient_id', 'nullable', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:160'], 'phone' => ['nullable', 'string', 'max:40'],
            'appointment_type_id' => ['nullable', 'exists:appointment_types,id'], 'team_member_id' => ['nullable', 'exists:team_members,id'],
            'date' => ['required', 'date_format:Y-m-d'], 'time' => ['required', 'date_format:H:i'], 'duration' => ['nullable', 'integer', 'min:10', 'max:480'],
            'status' => ['required', 'in:pending,confirmed'], 'staff_notes' => ['nullable', 'string', 'max:3000'],
        ]);
    }

    /** @param array<string, mixed> $data */
    private function resolvePatient(array $data): Patient
    {
        if (! empty($data['patient_id'])) {
            return Patient::findOrFail($data['patient_id']);
        }

        return Patient::findOrCreateFrom(['first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email' => $data['email'] ?? null, 'phone' => $data['phone'] ?? null, 'source' => 'admin']);
    }

    /** @return array<string, mixed> */
    private function formData(Appointment $appointment): array
    {
        return ['appointment' => $appointment, 'types' => AppointmentType::active()->get(), 'providers' => TeamMember::active()->where('accepts_bookings', true)->get(), 'patients' => Patient::orderBy('last_name')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'phone', 'email'])];
    }
}
