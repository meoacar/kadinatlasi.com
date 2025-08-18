<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Check enrollment status for authenticated users
        if (auth()->check()) {
            $userEnrollments = CourseEnrollment::where('user_id', auth()->id())
                ->pluck('course_id')
                ->toArray();
                
            $courses->each(function ($course) use ($userEnrollments) {
                $course->is_enrolled = in_array($course->id, $userEnrollments);
            });
        } else {
            $courses->each(function ($course) {
                $course->is_enrolled = false;
            });
        }

        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        
        // Check if user is enrolled or admin
        $isEnrolled = false;
        $isAdmin = false;
        
        if (auth()->check()) {
            $user = auth()->user();
            $isAdmin = $user->email === 'admin@kadinatlasi.com' || $user->hasRole('admin');
            
            if (!$isAdmin) {
                $isEnrolled = CourseEnrollment::where('user_id', auth()->id())
                    ->where('course_id', $id)
                    ->exists();
            } else {
                $isEnrolled = true; // Admin can access all courses
            }
        }
        
        // Hide content if not enrolled
        if (!$isEnrolled && !$isAdmin) {
            $course->content = null;
            $course->video_url = null;
        }
        
        return response()->json([
            'success' => true,
            'course' => $course,
            'is_enrolled' => $isEnrolled,
            'is_admin' => $isAdmin
        ]);
    }

    public function enroll(Request $request, $id)
    {
        // Check if user is admin (bypass premium check)
        $user = auth()->user();
        $isAdmin = $user->email === 'admin@kadinatlasi.com' || $user->hasRole('admin');
        
        if (!$isAdmin) {
            // Check premium membership for non-admin users
            $premiumSubscription = \App\Models\PremiumSubscription::where('user_id', auth()->id())
                ->where('status', 'active')
                ->whereIn('plan_type', ['premium', 'vip'])
                ->first();
                
            if (!$premiumSubscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kurslara katılmak için Premium veya VIP üyelik gereklidir!',
                    'requires_premium' => true
                ], 403);
            }
        }
        
        $course = Course::findOrFail($id);
        
        // Check if already enrolled
        $existingEnrollment = CourseEnrollment::where('user_id', auth()->id())
            ->where('course_id', $id)
            ->first();
            
        if ($existingEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Bu kursa zaten kayıtlısınız!'
            ], 400);
        }

        $enrollment = CourseEnrollment::create([
            'user_id' => auth()->id(),
            'course_id' => $id,
            'price_paid' => 0, // Free for premium members
            'enrollment_date' => now(),
            'status' => 'enrolled'
        ]);

        // Update enrollment count
        $course->increment('enrollment_count');

        return response()->json([
            'success' => true,
            'message' => 'Kursa başarıyla kaydoldunuz!',
            'enrollment' => $enrollment
        ]);
    }
}