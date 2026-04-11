<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function unsubscribe(Request $request)
    {
        $email = $request->query('email');
        $key = $request->query('key');

        if (! $email || ! $key) {
            return view('emails.unsubscribe', [
                'success' => false,
                'message' => 'Invalid unsubscribe link. Please check the link in your email.',
            ]);
        }

        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if (! $subscriber || ! hash_equals($subscriber->secret_key, $key)) {
            return view('emails.unsubscribe', [
                'success' => false,
                'message' => 'Invalid unsubscribe link. The email address or key provided is incorrect.',
            ]);
        }

        if (! $subscriber->is_subscribed) {
            return view('emails.unsubscribe', [
                'success' => true,
                'message' => 'You are already unsubscribed from the newsletter.',
            ]);
        }

        $subscriber->unsubscribe();

        return view('emails.unsubscribe', [
            'success' => true,
            'message' => 'You have been successfully unsubscribed from the newsletter.',
        ]);
    }

    public function exportActiveSubscribers(): \Illuminate\Http\JsonResponse
    {
        $emails = NewsletterSubscriber::where('is_subscribed', true)
            ->orderBy('subscribed_at', 'desc')
            ->pluck('email');

        return response()->json(['active_subscribers' => $emails])
            ->header('Content-Disposition', 'inline; filename="active-subscribers.json"');
    }
}
