<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\Inquiry;
use App\Models\Patient;
use App\Models\Post;
use App\Models\Service;
use App\Models\Subscriber;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SiteSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        $this->seed(ContentSeeder::class);
    }

    public function test_every_public_page_renders(): void
    {
        $service = Service::first();
        $member = TeamMember::first();
        $post = Post::first();
        foreach ([route('home'), route('clinic'), route('team'), route('team.show', $member), route('testimonials'), route('contact'), route('privacy'), route('services'), route('services.show', $service), route('invisalign'), route('emergency'), route('services.show', 'emergency'), route('blog'), route('blog', ['category' => 'Invisalign']), route('blog.show', $post), route('book'), route('book', ['type' => 'free-invisalign-consultation'])] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get(route('home'))->assertSee('Precision care')->assertSee('Dr. Shenilee Hazell')->assertSee('+1 868-241-3688');
    }

    public function test_slots_respect_hours_lead_time_and_capacity(): void
    {
        $type = AppointmentType::where('slug', 'general-consultation')->first();
        $sunday = now()->next('Sunday');
        $this->getJson(route('book.slots', ['type' => $type->slug, 'date' => $sunday->toDateString()]))->assertOk()->assertJson(['slots' => []]);
        $monday = now()->addWeek()->next('Monday');
        $json = $this->getJson(route('book.slots', ['type' => $type->slug, 'date' => $monday->toDateString()]))->assertOk()->json();
        $this->assertSame('08:00', $json['slots'][0]['time']);
        $this->assertTrue($json['slots'][0]['available']);
        $this->assertContains($monday->toDateString(), $json['open_days']);
    }

    public function test_patient_can_book_and_cancel_online(): void
    {
        $monday = now()->addWeek()->next('Monday');
        $payload = ['type' => 'general-consultation', 'date' => $monday->toDateString(), 'time' => '09:00', 'first_name' => 'Ann', 'last_name' => 'Example', 'email' => 'ann@example.com', 'phone' => '555-0101', 'notes' => 'Sensitive tooth', 'new_patient' => 1];
        $response = $this->post(route('book.store'), $payload)->assertRedirect();
        $appointment = Appointment::first();
        $this->assertSame('pending', $appointment->status);
        $this->assertSame('+18685550101', $appointment->patient->phone);
        $this->assertStringContainsString('New patient', $appointment->patient_notes);
        $this->get($response->headers->get('Location'))->assertOk()->assertSee($appointment->reference);

        // Booking the same slot three more times fills the three chairs; the fourth is refused.
        $this->post(route('book.store'), $payload + [])->assertRedirect();
        $this->post(route('book.store'), $payload)->assertRedirect();
        $this->post(route('book.store'), $payload)->assertSessionHasErrors('time');
        $this->assertSame(3, Appointment::count());
        $this->assertSame(1, Patient::count(), 'the same email never creates duplicate patients');

        $this->get($appointment->manageUrl())->assertOk()->assertSee('Cancel appointment');
        $this->post(route('book.cancel', $appointment->manage_token), ['reason' => 'Clash'])->assertRedirect();
        $this->assertSame('cancelled', $appointment->fresh()->status);
    }

    public function test_contact_form_creates_an_inquiry_and_patient(): void
    {
        $this->post(route('contact.store'), ['name' => 'Bob Builder', 'email' => 'bob@example.com', 'phone' => '868 555 0199', 'service' => 'Invisalign', 'message' => 'Am I a candidate?'])->assertRedirect()->assertSessionHas('status');
        $this->assertSame(1, Inquiry::count());
        $this->assertSame('Bob', Patient::first()->first_name);
        $this->post(route('newsletter.store'), ['email' => 'bob@example.com'])->assertSessionHas('newsletter');
        $this->assertSame(1, Subscriber::count());
    }

    public function test_admin_area_requires_login_and_renders_every_page(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'secret-password', 'role' => 'admin']);
        $patient = Patient::create(['first_name' => 'Ann', 'last_name' => 'Example', 'email' => 'ann@example.com', 'phone' => '+18685550101']);
        $type = AppointmentType::first();
        $appointment = Appointment::create(['patient_id' => $patient->id, 'appointment_type_id' => $type->id, 'starts_at' => now()->addDay()->setTime(10, 0), 'ends_at' => now()->addDay()->setTime(10, 30), 'status' => 'pending']);
        $inquiry = Inquiry::create(['patient_id' => $patient->id, 'name' => 'Ann Example', 'email' => 'ann@example.com', 'message' => 'Hi']);
        Subscriber::create(['email' => 'ann@example.com']);
        $this->post(route('admin.login.attempt'), ['email' => 'admin@example.com', 'password' => 'secret-password'])->assertRedirect(route('admin.dashboard'));

        foreach ([route('admin.dashboard'), route('admin.calendar'), route('admin.appointments.index'), route('admin.appointments.index', ['range' => 'past', 'q' => 'Ann']), route('admin.appointments.create'), route('admin.appointments.show', $appointment), route('admin.patients.index'), route('admin.patients.create'), route('admin.patients.show', $patient), route('admin.patients.edit', $patient), route('admin.inquiries.index'), route('admin.inquiries.show', $inquiry), route('admin.subscribers.index'), route('admin.team.index'), route('admin.team.create'), route('admin.team.edit', TeamMember::first()), route('admin.services.index'), route('admin.services.create'), route('admin.services.edit', Service::first()), route('admin.testimonials.index'), route('admin.testimonials.create'), route('admin.testimonials.edit', Testimonial::first()), route('admin.posts.index'), route('admin.posts.create'), route('admin.posts.edit', Post::first()), route('admin.appointment-types.index'), route('admin.appointment-types.create'), route('admin.appointment-types.edit', $type), route('admin.settings'), route('admin.users.index')] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
        $this->actingAs($admin)->get(route('admin.subscribers.export'))->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');

        // Confirm → completed, and a staff user cannot reach admin-only settings.
        $this->actingAs($admin)->patch(route('admin.appointments.status', $appointment), ['status' => 'confirmed'])->assertRedirect();
        $this->assertSame('confirmed', $appointment->fresh()->status);
        $staff = User::create(['name' => 'Staff', 'email' => 'staff@example.com', 'password' => 'secret-password', 'role' => 'staff']);
        $this->actingAs($staff)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($staff)->get(route('admin.settings'))->assertForbidden();
    }

    public function test_admin_can_book_for_a_new_patient_and_change_settings(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'secret-password', 'role' => 'admin']);
        $type = AppointmentType::first();
        $monday = now()->addWeek()->next('Monday');
        $this->actingAs($admin)->post(route('admin.appointments.store'), ['first_name' => 'New', 'last_name' => 'Person', 'phone' => '555-0123', 'appointment_type_id' => $type->id, 'date' => $monday->toDateString(), 'time' => '11:00', 'status' => 'confirmed'])->assertRedirect();
        $this->assertSame(1, Appointment::where('source', 'admin')->count());
        $this->actingAs($admin)->put(route('admin.settings.update'), ['hours' => ['mon' => ['open' => '09:00', 'close' => '17:00'], 'sun' => ['closed' => 1]], 'slot_minutes' => 30, 'chairs' => 2, 'lead_hours' => 1, 'horizon_days' => 30, 'notify' => 'front@example.com'])->assertRedirect();
        $json = $this->getJson(route('book.slots', ['type' => $type->slug, 'date' => $monday->toDateString()]))->json();
        $this->assertSame('09:00', $json['slots'][0]['time']);
    }
}
