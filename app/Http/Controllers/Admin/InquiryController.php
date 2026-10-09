<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        $inquiries = Inquiry::with('patient', 'assignee')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderByRaw("case status when 'new' then 0 when 'contacted' then 1 else 2 end")->latest()->paginate(25)->withQueryString();

        return view('admin.inquiries.index', ['inquiries' => $inquiries, 'statuses' => Inquiry::STATUSES]);
    }

    public function show(Inquiry $inquiry): View
    {
        return view('admin.inquiries.show', ['inquiry' => $inquiry->load('patient.appointments', 'assignee'), 'statuses' => Inquiry::STATUSES, 'staff' => User::orderBy('name')->get()]);
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:'.implode(',', array_keys(Inquiry::STATUSES))], 'assigned_to' => ['nullable', 'exists:users,id'], 'staff_notes' => ['nullable', 'string', 'max:3000']]);
        $inquiry->update($data);

        return back()->with('saved', 'Enquiry updated.');
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('saved', 'Enquiry deleted.');
    }
}
