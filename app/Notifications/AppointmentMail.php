<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/** One email class for every appointment event: requested, confirmed, rescheduled, cancelled, reminder. */
class AppointmentMail extends Notification
{
    public function __construct(public Appointment $appointment, public string $event, public bool $forClinic = false) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $a = $this->appointment->loadMissing('patient', 'type', 'provider');
        $when = $a->starts_at->format('l j F Y \a\t g:i A');
        $what = ($a->type?->name ?? 'Appointment').($a->provider ? ' with '.$a->provider->name : '');
        $clinic = config('clinic.name');

        if ($this->forClinic) {
            return (new MailMessage)
                ->subject(match ($this->event) {
                    'requested' => 'New booking request: '.$a->patient->fullName(), 'cancelled' => 'Cancelled by patient: '.$a->patient->fullName(), default => 'Booking update: '.$a->patient->fullName()
                })
                ->greeting('Hi team,')
                ->line($a->patient->fullName().' ('.($a->patient->phone ?: $a->patient->email).') '.match ($this->event) {
                    'requested' => 'requested', 'cancelled' => 'cancelled', default => 'updated'
                }.' an appointment.')
                ->line($what.' on '.$when.'.')
                ->lineIf((bool) $a->patient_notes, 'Notes: '.$a->patient_notes)
                ->action('Open in admin', route('admin.appointments.show', $a))
                ->salutation($clinic);
        }

        $mail = (new MailMessage)->greeting('Hello '.$a->patient->first_name.',');

        return match ($this->event) {
            'requested' => $mail->subject('We received your booking request · '.$clinic)
                ->line('Thank you for choosing '.$clinic.'. We have your request for:')
                ->line($what.' on '.$when.'.')
                ->line('Our team will confirm it shortly. You will get another email once it is confirmed. Reference '.$a->reference.'.')
                ->action('View or change your appointment', $a->manageUrl())
                ->line('Need to reach us sooner? Call '.config('clinic.phone').'.'),
            'confirmed' => $mail->subject('Confirmed: '.$what.' · '.$clinic)
                ->line('Your appointment is confirmed.')
                ->line($what.' on '.$when.'.')
                ->line('We are at '.config('clinic.address.line1').', '.config('clinic.address.line2').'. Please arrive 10 minutes early for your first visit.')
                ->action('View or change your appointment', $a->manageUrl()),
            'reminder' => $mail->subject('Reminder: '.$what.' tomorrow · '.$clinic)
                ->line('A friendly reminder of your appointment tomorrow.')
                ->line($what.' on '.$when.'.')
                ->action('View or change your appointment', $a->manageUrl())
                ->line('If you cannot make it, please let us know so we can offer the time to someone else.'),
            'cancelled' => $mail->subject('Cancelled: '.$what.' · '.$clinic)
                ->line('Your appointment on '.$when.' has been cancelled.')
                ->lineIf((bool) $a->cancellation_reason, 'Reason: '.$a->cancellation_reason)
                ->action('Book another time', route('book')),
            default => $mail->subject('Your appointment was updated · '.$clinic)
                ->line($what.' is now on '.$when.'.')
                ->action('View or change your appointment', $a->manageUrl()),
        };
    }
}
