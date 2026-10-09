<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index(): View
    {
        return view('admin.subscribers', ['subscribers' => Subscriber::latest()->paginate(50), 'active' => Subscriber::whereNull('unsubscribed_at')->count()]);
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['email', 'name', 'subscribed_at']);
            Subscriber::whereNull('unsubscribed_at')->orderBy('created_at')->each(fn ($s) => fputcsv($out, [$s->email, $s->name, $s->created_at->toDateString()]));
            fclose($out);
        }, 'smile-inn-subscribers-'.now()->toDateString().'.csv', ['Content-Type' => 'text/csv']);
    }

    public function destroy(Subscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('saved', 'Subscriber removed.');
    }
}
