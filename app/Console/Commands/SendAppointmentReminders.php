<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\AppointmentMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

/** Emails every confirmed patient the day before their appointment. Run from the scheduler. */
class SendAppointmentReminders extends Command
{
    protected $signature = 'clinic:remind';

    protected $description = 'Email reminders for tomorrow\'s confirmed appointments';

    public function handle(): int
    {
        $due = Appointment::with('patient')->where('status', 'confirmed')->whereNull('reminded_at')->whereDate('starts_at', now()->addDay()->toDateString())->get();
        foreach ($due as $a) {
            if (! $a->patient->email) {
                continue;
            }
            try {
                Notification::route('mail', [$a->patient->email => $a->patient->fullName()])->notify(new AppointmentMail($a, 'reminder'));
                $a->forceFill(['reminded_at' => now()])->save();
            } catch (\Throwable $e) {
                report($e);
            }
        }
        $this->info($due->count().' reminder(s) processed.');

        return self::SUCCESS;
    }
}
