<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('site.services', ['services' => Service::active()->get()]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->active, 404);
        if ($service->slug === 'invisalign') {
            return $this->invisalign();
        }
        if ($service->slug === 'emergency') {
            return $this->emergency();
        }

        return view('site.service', ['service' => $service, 'others' => Service::active()->where('id', '!=', $service->id)->get()]);
    }

    public function invisalign(): View
    {
        $service = Service::where('slug', 'invisalign')->firstOrFail();

        return view('site.invisalign', ['service' => $service, 'testimonials' => Testimonial::active()->where('featured', true)->take(3)->get(), 'gallery' => collect(range(1, 12))->map(fn ($i) => "images/gallery/smile-{$i}.jpg")]);
    }

    public function emergency(): View
    {
        return view('site.emergency', ['service' => Service::where('slug', 'emergency')->firstOrFail()]);
    }
}
