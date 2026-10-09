<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentType;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentTypeController extends Controller
{
    public function index(): View
    {
        return view('admin.types.index', ['types' => AppointmentType::with('provider')->orderBy('sort')->get()]);
    }

    public function create(): View
    {
        return view('admin.types.form', ['type' => new AppointmentType(['duration_minutes' => 30, 'active' => true, 'sort' => AppointmentType::max('sort') + 1]), 'providers' => TeamMember::active()->where('accepts_bookings', true)->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        AppointmentType::create($this->validated($request));

        return redirect()->route('admin.appointment-types.index')->with('saved', 'Appointment type added.');
    }

    public function edit(AppointmentType $type): View
    {
        return view('admin.types.form', ['type' => $type, 'providers' => TeamMember::active()->where('accepts_bookings', true)->get()]);
    }

    public function update(Request $request, AppointmentType $type): RedirectResponse
    {
        $type->update($this->validated($request));

        return redirect()->route('admin.appointment-types.index')->with('saved', 'Appointment type updated.');
    }

    public function destroy(AppointmentType $type): RedirectResponse
    {
        $type->delete();

        return back()->with('saved', 'Appointment type removed.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:120'], 'description' => ['nullable', 'string', 'max:255'], 'duration_minutes' => ['required', 'integer', 'min:10', 'max:480'], 'team_member_id' => ['nullable', 'exists:team_members,id'], 'sort' => ['nullable', 'integer', 'min:0']]);

        return $data + ['is_free' => $request->boolean('is_free'), 'is_virtual' => $request->boolean('is_virtual'), 'active' => $request->boolean('active')];
    }
}
