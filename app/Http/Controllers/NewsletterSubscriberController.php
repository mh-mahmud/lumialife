<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:191'],
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => strtolower($validated['email'])],
            [
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]
        );

        return response()->json([
            'message' => $subscriber->wasRecentlyCreated
                ? 'Thank you for subscribing!'
                : 'You are already subscribed.',
        ]);
    }

    public function index()
    {
        $subscribers = NewsletterSubscriber::latest()->paginate(30);

        return view('newsletter-subscribers.index', compact('subscribers'));
    }

    public function destroy(NewsletterSubscriber $newsletterSubscriber)
    {
        $newsletterSubscriber->delete();

        return redirect()->route('newsletter-subscribers.index')
            ->with('success', 'Subscriber removed successfully.');
    }
}
