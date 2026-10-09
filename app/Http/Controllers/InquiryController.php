<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Patient;
use App\Models\Setting;
use App\Notifications\InquiryMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class InquiryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'service' => ['nullable', 'string', 'max:80'],
            'gender' => ['nullable', 'in:Female,Male,Prefer not to say'],
            'message' => ['required', 'string', 'max:3000'],
            'website' => ['nullable', 'max:0'],
        ], ['website.max' => 'Something went wrong. Please try again.']);

        [$first, $last] = array_pad(explode(' ', trim($data['name']), 2), 2, '');
        $patient = Patient::findOrCreateFrom(['first_name' => $first, 'last_name' => $last, 'email' => $data['email'], 'phone' => $data['phone'] ?? null, 'gender' => $data['gender'] ?? null, 'source' => 'website']);
        $inquiry = Inquiry::create(['patient_id' => $patient->id, 'name' => $data['name'], 'email' => $data['email'], 'phone' => Patient::normalisePhone($data['phone'] ?? null), 'service' => $data['service'] ?? null, 'gender' => $data['gender'] ?? null, 'message' => $data['message']]);

        try {
            Notification::route('mail', [$data['email'] => $data['name']])->notify(new InquiryMail($inquiry));
            if ($to = Setting::get('booking')['notify'] ?? null) {
                Notification::route('mail', $to)->notify(new InquiryMail($inquiry, forClinic: true));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('status', 'Thank you! Your message has been received. We will be in touch within one working day.');
    }
}
