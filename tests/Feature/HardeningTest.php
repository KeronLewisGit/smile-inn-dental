<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\Patient;
use App\Models\User;
use App\Services\Availability;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class HardeningTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        $this->seed(ContentSeeder::class);
        $this->admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'secret-password', 'role' => 'admin']);
    }

    /** @return array<string, mixed> */
    private function booking(array $extra = []): array
    {
        return $extra + ['type' => 'general-consultation', 'date' => now()->addWeek()->next('Monday')->toDateString(), 'time' => '09:00', 'first_name' => 'Ann', 'last_name' => 'Example', 'email' => 'ann@example.com', 'phone' => '555-0101'];
    }

    public function test_durations_are_positive_in_the_admin(): void
    {
        $patient = Patient::create(['first_name' => 'Ann', 'last_name' => 'Example', 'phone' => '+18685550101']);
        $a = Appointment::create(['patient_id' => $patient->id, 'starts_at' => now()->addDay()->setTime(10, 0), 'ends_at' => now()->addDay()->setTime(10, 45), 'status' => 'confirmed']);
        $this->assertSame(45, $a->durationMinutes());
        $this->actingAs($this->admin)->get(route('admin.appointments.index'))->assertOk()->assertSee('45 min')->assertDontSee('-45');
        $this->actingAs($this->admin)->get(route('admin.appointments.show', $a))->assertOk()->assertSee('value="45"', false);
    }

    public function test_confirmation_page_needs_the_private_token_not_the_short_reference(): void
    {
        $response = $this->post(route('book.store'), $this->booking())->assertRedirect();
        $appointment = Appointment::first();
        $this->assertStringContainsString($appointment->manage_token, $response->headers->get('Location'));
        $this->get(route('book.done', $appointment->manage_token))->assertOk()->assertSee($appointment->reference);
        $this->get('/book/done/'.$appointment->reference)->assertNotFound();
    }

    public function test_a_web_booking_never_overwrites_an_existing_patient_record(): void
    {
        $ann = Patient::create(['first_name' => 'Ann', 'last_name' => 'Example', 'email' => 'ann@example.com', 'phone' => '+18685550101', 'gender' => 'Female']);

        // Same email, different phone typed: Ann's phone on file is kept.
        $this->post(route('book.store'), $this->booking(['phone' => '555-0999']))->assertRedirect();
        $this->assertSame('+18685550101', $ann->fresh()->phone);
        $this->assertSame(1, Patient::count());

        // A relative sharing Ann's phone but with a different name gets their own record.
        $this->post(route('book.store'), $this->booking(['time' => '10:00', 'first_name' => 'Ben', 'last_name' => 'Example', 'email' => 'ben@example.com']))->assertRedirect();
        $this->assertSame(2, Patient::count());
        $this->assertSame('Ann', $ann->fresh()->first_name);

        // The same person matched by phone with no email on file: the blank is filled in.
        $cal = Patient::create(['first_name' => 'Cal', 'last_name' => 'Jones', 'phone' => '+18685550777']);
        $this->post(route('book.store'), $this->booking(['time' => '11:00', 'first_name' => 'cal', 'last_name' => 'JONES', 'email' => 'Cal@Example.com', 'phone' => '555-0777']))->assertRedirect();
        $this->assertSame(3, Patient::count());
        $this->assertSame('cal@example.com', $cal->fresh()->email);
    }

    public function test_bookings_outside_the_horizon_or_with_odd_dates_are_refused(): void
    {
        $this->post(route('book.store'), $this->booking(['date' => now()->addDays(200)->toDateString()]))->assertSessionHasErrors('time');
        $this->post(route('book.store'), $this->booking(['date' => 'next tuesday']))->assertSessionHasErrors('date');
        $this->post(route('book.store'), $this->booking(['time' => '09:07']))->assertSessionHasErrors('time');
        $this->assertSame(0, Appointment::count());
    }

    public function test_calendar_survives_a_bad_week_parameter(): void
    {
        $this->actingAs($this->admin)->get(route('admin.calendar', ['week' => 'garbage']))->assertOk();
        $this->actingAs($this->admin)->get(route('admin.calendar', ['week' => '2026-13-45']))->assertOk();
    }

    public function test_hours_are_grouped_for_the_header_and_footer(): void
    {
        $availability = app(Availability::class);
        $this->assertSame([['days' => 'Mon – Sat', 'hours' => '8:00 AM – 3:30 PM'], ['days' => 'Sunday', 'hours' => 'Closed']], $availability->hoursGrouped());
        $this->assertSame('Mon – Sat, 8:00 AM – 3:30 PM', $availability->hoursSummary());

        $this->actingAs($this->admin)->put(route('admin.settings.update'), ['hours' => ['mon' => ['open' => '09:00', 'close' => '17:00'], 'tue' => ['open' => '09:00', 'close' => '17:00'], 'sat' => ['open' => '09:00', 'close' => '13:00']], 'slot_minutes' => 30, 'chairs' => 2, 'lead_hours' => 1, 'horizon_days' => 30])->assertRedirect()->assertSessionHasNoErrors();
        $this->get(route('home'))->assertOk()->assertSee('Mon – Tue, 9:00 AM – 5:00 PM')->assertSee('Wed – Fri');

        // Closing before opening is rejected.
        $this->actingAs($this->admin)->put(route('admin.settings.update'), ['hours' => ['mon' => ['open' => '09:00', 'close' => '08:00']], 'slot_minutes' => 30, 'chairs' => 2, 'lead_hours' => 1, 'horizon_days' => 30])->assertSessionHasErrors('hours.mon.close');
    }

    public function test_staff_slot_picker_ignores_minimum_notice_and_store_warns_on_overbooking(): void
    {
        $type = AppointmentType::where('slug', 'general-consultation')->first();
        $day = now()->addWeek()->next('Monday');
        $this->actingAs($this->admin)->getJson(route('admin.appointments.slots', ['type' => $type->id, 'date' => $day->toDateString()]))->assertOk()->assertJsonPath('slots.0.time', '08:00');

        $payload = ['first_name' => 'New', 'last_name' => 'Person', 'phone' => '555-0123', 'appointment_type_id' => $type->id, 'date' => $day->toDateString(), 'time' => '08:00', 'status' => 'confirmed'];
        foreach (range(1, 3) as $i) {
            $this->actingAs($this->admin)->post(route('admin.appointments.store'), $payload + ['first_name' => 'P'.$i])->assertRedirect()->assertSessionHas('saved', fn ($m) => ! str_contains($m, 'Note:'));
        }
        $this->actingAs($this->admin)->post(route('admin.appointments.store'), $payload + ['first_name' => 'P4'])->assertRedirect()->assertSessionHas('saved', fn ($m) => str_contains($m, 'every chair was already taken'));
        $this->assertSame(4, Appointment::count(), 'staff can still overbook deliberately');
    }
}
