<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\Inquiry;
use App\Models\Patient;
use App\Models\Subscriber;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;

/** Local demo data only: a few patients, bookings and inquiries so the admin area has something to show. */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (Patient::count() > 0) {
            return;
        }
        $types = AppointmentType::all();
        $dentists = TeamMember::where('is_dentist', true)->get();
        $people = [
            ['Ariel', 'Maloney', 'ariel@example.com', '+18685550101'], ['Tamika', 'Young', 'tamika@example.com', '+18685550102'], ['Achsah', 'Basha', 'achsah@example.com', '+18685550103'],
            ['Kevin', 'Ramdass', 'kevin@example.com', '+18685550104'], ['Priya', 'Mohammed', 'priya@example.com', '+18685550105'], ['Jordan', 'Lewis', 'jordan@example.com', '+18685550106'],
        ];
        $patients = collect($people)->map(fn ($p) => Patient::create(['first_name' => $p[0], 'last_name' => $p[1], 'email' => $p[2], 'phone' => $p[3], 'source' => 'website', 'marketing_opt_in' => true]));

        $slots = [[1, 9, 0, 'pending'], [1, 11, 30, 'confirmed'], [2, 8, 30, 'confirmed'], [3, 13, 0, 'pending'], [-3, 10, 0, 'completed'], [-7, 9, 30, 'completed'], [-1, 14, 0, 'no_show'], [5, 8, 0, 'pending']];
        foreach ($slots as $i => [$days, $h, $m, $status]) {
            $type = $types[$i % $types->count()];
            $start = now()->addDays($days)->setTime($h, $m);
            while ($start->isSunday()) {
                $start->addDay();
            }
            Appointment::create(['patient_id' => $patients[$i % $patients->count()]->id, 'appointment_type_id' => $type->id, 'team_member_id' => $type->team_member_id ?? $dentists[$i % $dentists->count()]->id, 'starts_at' => $start, 'ends_at' => $start->copy()->addMinutes($type->duration_minutes), 'status' => $status, 'patient_notes' => $i % 3 === 0 ? 'Slight sensitivity on the lower left.' : null, 'confirmed_at' => in_array($status, ['confirmed', 'completed']) ? now()->subDay() : null]);
        }
        Inquiry::create(['patient_id' => $patients[0]->id, 'name' => 'Ariel Maloney', 'email' => 'ariel@example.com', 'phone' => '+18685550101', 'service' => 'Invisalign', 'message' => 'I would love to know if I am a candidate for Invisalign and roughly how long it would take.', 'status' => 'new']);
        Inquiry::create(['name' => 'Marcus Joseph', 'email' => 'marcus@example.com', 'phone' => '+18685550199', 'service' => 'Cosmetic Dentistry', 'message' => 'Interested in whitening before a wedding in December.', 'status' => 'contacted']);
        foreach (['ariel@example.com', 'tamika@example.com', 'newsletter-fan@example.com'] as $e) {
            Subscriber::firstOrCreate(['email' => $e]);
        }
    }
}
