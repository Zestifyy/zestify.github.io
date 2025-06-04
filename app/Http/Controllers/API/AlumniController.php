<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AlumniProfile;
use App\Models\Event;
use App\Models\Blog;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    
    public function profile(Request $request)
    {
        $user = $request->user();
        $profile = AlumniProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        return response()->json($profile);
    }

   
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $profile = AlumniProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string',
            'phone' => 'sometimes|string|max:20',
            // Add other fields as needed
        ]);

        $profile->update($validatedData);

        return response()->json(['message' => 'Profile updated successfully', 'profile' => $profile]);
    }

  
    public function events()
    {
        $events = Event::orderBy('date', 'desc')->get();
        
        return response()->json($events);
    }


    public function blogs()
    {
        $blogs = Blog::where('is_active', true)->orderBy('published_at', 'desc')->get();
        return response()->json($blogs);
    }


    public function announcements()
    {
        $announcements = Announcement::where('is_active', true)->orderBy('published_at', 'desc')->get();
        return response()->json($announcements);
    }
}
