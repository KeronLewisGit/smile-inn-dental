<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonials.index', ['testimonials' => Testimonial::orderBy('sort')->get()]);
    }

    public function create(): View
    {
        return view('admin.testimonials.form', ['testimonial' => new Testimonial(['rating' => 5, 'active' => true, 'source' => 'Google', 'sort' => Testimonial::max('sort') + 1])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Testimonial::create($this->validated($request));

        return redirect()->route('admin.testimonials.index')->with('saved', 'Testimonial added.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', ['testimonial' => $testimonial]);
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->validated($request));

        return redirect()->route('admin.testimonials.index')->with('saved', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return back()->with('saved', 'Testimonial removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:120'], 'quote' => ['required', 'string', 'max:2000'], 'treatment' => ['nullable', 'string', 'max:80'], 'rating' => ['required', 'integer', 'min:1', 'max:5'], 'source' => ['nullable', 'string', 'max:40'], 'video_url' => ['nullable', 'url', 'max:255'], 'sort' => ['nullable', 'integer', 'min:0']]);

        return $data + ['featured' => $request->boolean('featured'), 'active' => $request->boolean('active')];
    }
}
