<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Inquiry;
use App\Models\Patient;
use App\Models\Subscriber;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = Appointment::with('patient', 'type', 'provider')->onDate(now())->whereIn('status', ['pending', 'confirmed', 'completed'])->orderBy('starts_at')->get();

        return view('admin.dashboard', [
            'today' => $today,
            'pending' => Appointment::with('patient', 'type')->where('status', 'pending')->where('starts_at', '>=', now()->startOfDay())->orderBy('starts_at')->take(8)->get(),
            'newInquiries' => Inquiry::with('patient')->where('status', 'new')->latest()->take(6)->get(),
            'stats' => [
                'today' => $today->count(),
                'week' => Appointment::blocking()->whereBetween('starts_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'pending' => Appointment::where('status', 'pending')->where('starts_at', '>=', now())->count(),
                'new_inquiries' => Inquiry::where('status', 'new')->count(),
                'patients' => Patient::count(),
                'new_patients_month' => Patient::where('created_at', '>=', now()->startOfMonth())->count(),
                'subscribers' => Subscriber::whereNull('unsubscribed_at')->count(),
                'no_show_rate' => ($done = Appointment::whereIn('status', ['completed', 'no_show'])->where('starts_at', '>=', now()->subDays(90))->count()) ? round(Appointment::where('status', 'no_show')->where('starts_at', '>=', now()->subDays(90))->count() / $done * 100) : 0,
            ],
            'upcomingDays' => collect(range(0, 6))->map(fn ($i) => ['date' => now()->addDays($i), 'count' => Appointment::blocking()->onDate(now()->addDays($i))->count()]),
        ]);
    }
}
