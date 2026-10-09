<?php

namespace App\Notifications;

use App\Models\Inquiry;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InquiryMail extends Notification
{
    public function __construct(public Inquiry $inquiry, public bool $forClinic = false) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $i = $this->inquiry;
        if ($this->forClinic) {
            return (new MailMessage)->subject('New website enquiry: '.$i->name.($i->service ? ' · '.$i->service : ''))
                ->greeting('Hi team,')
                ->line($i->name.' sent a message'.($i->service ? ' about '.$i->service : '').'.')
                ->line('Email: '.($i->email ?: 'none').' · Phone: '.($i->phone ?: 'none'))
                ->line('"'.$i->message.'"')
                ->action('Reply from admin', route('admin.inquiries.show', $i));
        }

        return (new MailMessage)->subject('We got your message · '.config('clinic.name'))
            ->greeting('Hello '.explode(' ', $i->name)[0].',')
            ->line('Thanks for reaching out to '.config('clinic.name').'. One of the team will get back to you within one working day.')
            ->line('In a hurry? Call us on '.config('clinic.phone').' or book online.')
            ->action('Book an appointment', route('book'));
    }
}
