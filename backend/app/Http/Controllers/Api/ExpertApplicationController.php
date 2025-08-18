<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpertApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpertApplicationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'profession' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'specialization' => 'required|string',
            'motivation' => 'required|string',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $certificatePath = null;
        if ($request->hasFile('certificate')) {
            $certificatePath = $request->file('certificate')->store('expert-certificates', 'public');
        }

        $application = ExpertApplication::create([
            'name' => $request->name,
            'email' => $request->email,
            'profession' => $request->profession,
            'experience' => $request->experience,
            'specialization' => $request->specialization,
            'motivation' => $request->motivation,
            'certificate_path' => $certificatePath,
            'status' => 'pending',
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Başvurunuz başarıyla gönderildi!',
            'data' => $application
        ]);
    }
}