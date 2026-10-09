<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:160'], 'name' => ['nullable', 'string', 'max:120'], 'website' => ['nullable', 'max:0']]);
        $sub = Subscriber::firstOrNew(['email' => strtolower($data['email'])]);
        $sub->fill(['name' => $data['name'] ?? $sub->name, 'unsubscribed_at' => null])->save();

        return back()->with('newsletter', $sub->wasRecentlyCreated ? "You're on the list. Welcome to Smile Inn." : "You're already subscribed. Thank you!");
    }
}
