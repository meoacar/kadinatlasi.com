<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdvertisementController extends Controller
{
    public function getActiveAds(Request $request)
    {
        $position = $request->get('position');
        $type = $request->get('type');
        
        $query = Advertisement::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now());
        
        if ($position) {
            $query->where('position', $position);
        }
        
        if ($type) {
            $query->where('type', $type);
        }
        
        $ads = $query->orderBy('created_at', 'desc')->get();
        
        // Increment impressions
        foreach ($ads as $ad) {
            $ad->increment('impressions');
        }
        
        return response()->json([
            'success' => true,
            'data' => $ads
        ]);
    }
    
    public function trackClick(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id);
        $ad->increment('clicks');
        
        return response()->json([
            'success' => true,
            'message' => 'Click tracked successfully'
        ]);
    }
}