<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        if (!$profile) {
            $profile = UserProfile::create([
                'user_id' => $user->id,
                'interests' => [],
                'health_info' => [],
                'goals' => [],
                'achievements' => [],
                'points' => 0,
                'level' => 1
            ]);
        }
        
        // Load premium subscription
        $premiumSubscription = \App\Models\PremiumSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();
            
        $premiumRank = null;
        if ($premiumSubscription) {
            $planNames = [
                'basic' => '⭐ Temel Üye',
                'premium' => '💎 Premium Üye', 
                'vip' => '👑 VIP Üye'
            ];
            $premiumRank = $planNames[$premiumSubscription->plan_type] ?? '⭐ Premium Üye';
        }

        return response()->json([
            'success' => true,
            'user' => $user->load('expertApplication'),
            'profile' => $profile,
            'expert_rank' => $user->expert_rank,
            'is_expert' => $user->is_expert,
            'premium_rank' => $premiumRank,
            'is_premium' => $premiumSubscription !== null
        ]);
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        if (!$profile) {
            $profile = UserProfile::create([
                'user_id' => $user->id,
                'interests' => [],
                'health_info' => [],
                'goals' => [],
                'achievements' => [],
                'points' => 0,
                'level' => 1
            ]);
        }
        
        // Update user fields
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        
        if ($request->has('birth_date')) {
            $user->birth_date = $request->birth_date;
        }
        
        if ($request->has('zodiac_sign')) {
            $user->zodiac_sign = $request->zodiac_sign;
        }
        
        $user->save();
        
        // Update profile fields
        if ($request->has('bio')) {
            $profile->bio = $request->bio;
        }
        
        if ($request->has('interests')) {
            $profile->interests = $request->interests;
        }
        
        if ($request->has('health_info')) {
            $profile->health_info = $request->health_info;
        }
        
        if ($request->has('last_period_date')) {
            $profile->last_period_date = $request->last_period_date;
        }
        
        if ($request->has('cycle_length')) {
            $profile->cycle_length = $request->cycle_length;
        }
        
        if ($request->has('goals')) {
            $profile->goals = $request->goals;
        }
        
        $profile->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Profil başarıyla güncellendi',
            'user' => $user->fresh(),
            'profile' => $profile->fresh()
        ]);
    }
    
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB max
        ]);
        
        $user = Auth::user();
        
        if ($request->hasFile('avatar')) {
            // Eski avatar'ı sil
            if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                unlink(public_path('storage/' . $user->avatar));
            }
            
            // Yeni avatar'ı kaydet
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $user->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Profil resmi başarıyla güncellendi',
            'user' => $user->fresh()
        ]);
    }
}
