<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
            'name' => 'nullable|string|max:255',
            'interests' => 'nullable|array'
        ]);

        $newsletter = Newsletter::create([
            'email' => $request->email,
            'name' => $request->name,
            'interests' => $request->interests ?? [],
            'subscribed_at' => now()
        ]);

        return response()->json([
            'message' => 'Newsletter aboneliğiniz başarıyla oluşturuldu!',
            'data' => $newsletter
        ]);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:newsletters,email'
        ]);

        $newsletter = Newsletter::where('email', $request->email)->first();
        $newsletter->update([
            'is_active' => false,
            'unsubscribed_at' => now()
        ]);

        return response()->json([
            'message' => 'Newsletter aboneliğiniz başarıyla iptal edildi.'
        ]);
    }
}