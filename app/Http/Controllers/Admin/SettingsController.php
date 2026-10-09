<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private const DAYS = ['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'];

    public function edit(): View
    {
        return view('admin.settings', ['days' => self::DAYS, 'hours' => Setting::get('hours'), 'booking' => Setting::get('booking')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hours' => ['required', 'array'], 'hours.*.open' => ['nullable', 'date_format:H:i'], 'hours.*.close' => ['nullable', 'date_format:H:i', 'after:hours.*.open'], 'hours.*.closed' => ['nullable', 'boolean'],
            'slot_minutes' => ['required', 'integer', 'in:15,20,30,45,60'], 'chairs' => ['required', 'integer', 'min:1', 'max:20'], 'lead_hours' => ['required', 'integer', 'min:0', 'max:168'], 'horizon_days' => ['required', 'integer', 'min:7', 'max:365'], 'notify' => ['nullable', 'email'],
        ], ['hours.*.close.after' => 'Closing time must be later than opening time.']);
        $hours = [];
        foreach (array_keys(self::DAYS) as $day) {
            $h = $data['hours'][$day] ?? [];
            $hours[$day] = ! empty($h['closed']) || empty($h['open']) || empty($h['close']) ? null : [$h['open'], $h['close']];
        }
        Setting::put('hours', $hours);
        Setting::put('booking', ['slot_minutes' => (int) $data['slot_minutes'], 'chairs' => (int) $data['chairs'], 'lead_hours' => (int) $data['lead_hours'], 'horizon_days' => (int) $data['horizon_days'], 'notify' => $data['notify'] ?? null]);

        return back()->with('saved', 'Settings saved.');
    }
}
