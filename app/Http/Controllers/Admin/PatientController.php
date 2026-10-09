<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $patients = Patient::withCount('appointments')->withMax('appointments', 'starts_at')
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w->where('first_name', 'like', '%'.$request->q.'%')->orWhere('last_name', 'like', '%'.$request->q.'%')->orWhere('email', 'like', '%'.$request->q.'%')->orWhere('phone', 'like', '%'.$request->q.'%')))
            ->orderBy('last_name')->orderBy('first_name')->paginate(30)->withQueryString();

        return view('admin.patients.index', ['patients' => $patients]);
    }

    public function create(): View
    {
        return view('admin.patients.form', ['patient' => new Patient(['source' => 'admin'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $patient = Patient::create($this->validated($request) + ['source' => 'admin']);

        return redirect()->route('admin.patients.show', $patient)->with('saved', 'Patient added.');
    }

    public function show(Patient $patient): View
    {
        return view('admin.patients.show', ['patient' => $patient->load('appointments.type', 'appointments.provider', 'inquiries')]);
    }

    public function edit(Patient $patient): View
    {
        return view('admin.patients.form', ['patient' => $patient]);
    }

    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $patient->update($this->validated($request));

        return redirect()->route('admin.patients.show', $patient)->with('saved', 'Patient updated.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'], 'last_name' => ['required', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:160'], 'phone' => ['nullable', 'string', 'max:40'],
            'date_of_birth' => ['nullable', 'date', 'before:today'], 'gender' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:5000'], 'marketing_opt_in' => ['nullable', 'boolean'],
        ]);
        $data['phone'] = Patient::normalisePhone($data['phone'] ?? null);
        $data['marketing_opt_in'] = $request->boolean('marketing_opt_in');

        return $data;
    }
}
