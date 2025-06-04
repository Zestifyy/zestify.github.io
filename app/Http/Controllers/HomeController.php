<?php

namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\User;
use App\Models\Blog; 
use App\Models\Announcement;
use App\Models\About;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        // MEMPERBARUI BAGIAN INI:
        // Mengambil 6 event terbaru yang memiliki audience_type 'all'
        $events = Event::where('audience_type', 'all') // <-- Tambahkan filter ini
                        ->latest()
                        ->paginate(6);

        $blogs = Blog::latest()->paginate(6); 
        $announcements = Announcement::latest()->paginate(6);    

        // Di dalam metode controller Anda
        $alumniUsers = User::where('role', 'alumni')->limit(6)->get();
        $about = About::first();

        return view('welcome', compact('events', 'alumniUsers', 'blogs', 'announcements', 'about'));

    }

}
