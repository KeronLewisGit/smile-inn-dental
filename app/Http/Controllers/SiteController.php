<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Services\Availability;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        return view('site.home', [
            'services' => Service::active()->where('featured', true)->get(),
            'team' => TeamMember::active()->where('is_dentist', true)->get(),
            'testimonials' => Testimonial::active()->where('featured', true)->get(),
            'posts' => Post::published()->take(3)->get(),
            'hours' => app(Availability::class)->hoursForDisplay(),
        ]);
    }

    public function clinic(): View
    {
        return view('site.clinic', ['team' => TeamMember::active()->get(), 'testimonials' => Testimonial::active()->where('featured', true)->take(3)->get()]);
    }

    public function team(): View
    {
        return view('site.team', ['dentists' => TeamMember::active()->where('is_dentist', true)->get(), 'staff' => TeamMember::active()->where('is_dentist', false)->get()]);
    }

    public function member(TeamMember $member): View
    {
        abort_unless($member->active, 404);

        return view('site.member', ['member' => $member, 'others' => TeamMember::active()->where('id', '!=', $member->id)->take(4)->get()]);
    }

    public function testimonials(): View
    {
        return view('site.testimonials', ['written' => Testimonial::active()->whereNull('video_url')->get(), 'videos' => Testimonial::active()->whereNotNull('video_url')->get()]);
    }

    public function contact(): View
    {
        return view('site.contact', ['hours' => app(Availability::class)->hoursForDisplay()]);
    }

    public function privacy(): View
    {
        return view('site.privacy');
    }
}
