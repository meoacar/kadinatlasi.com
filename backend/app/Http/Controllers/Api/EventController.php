<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::active()->withCount('attendees');
        
        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->upcoming) {
            $query->upcoming();
        }
        
        $events = $query->orderBy('start_date', 'asc')->paginate(12);
        
        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    public function show($id)
    {
        $event = Event::with(['participants.user'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    public function register(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user = auth()->user();
        
        if ($event->is_full) {
            return response()->json([
                'success' => false,
                'message' => 'Etkinlik dolu!'
            ], 400);
        }
        
        $existing = EventParticipant::where('event_id', $id)
            ->where('user_id', $user->id)
            ->first();
            
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Zaten kayıtlısınız!'
            ], 400);
        }
        
        EventParticipant::create([
            'event_id' => $id,
            'user_id' => $user->id,
            'registered_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Etkinliğe başarıyla kaydoldunuz!'
        ]);
    }

    public function unregister($id)
    {
        $user = auth()->user();
        
        $participant = EventParticipant::where('event_id', $id)
            ->where('user_id', $user->id)
            ->first();
            
        if (!$participant) {
            return response()->json([
                'success' => false,
                'message' => 'Kayıt bulunamadı!'
            ], 404);
        }
        
        $participant->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Kaydınız iptal edildi!'
        ]);
    }

    public function myEvents()
    {
        $user = auth()->user();
        
        $events = Event::whereHas('participants', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['participants' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->orderBy('start_date', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }
}