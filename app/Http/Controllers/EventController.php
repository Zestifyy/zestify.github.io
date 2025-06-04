<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Major;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // Tambahkan constructor untuk middleware (jika belum ada)
    public function __construct()
    {
        // Pindahkan 'show' ke 'except' juga, karena detail event juga harus publik
        $this->middleware('auth')->except(['front', 'show', 'detail']); // <-- PERUBAHAN DI SINI
        $this->middleware('role:admin')->except(['front', 'show', 'detail']);
    }

    /**
     * Show all events for admin dashboard.
     */
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('dashboard.admin.events.index', compact('events'));
    }

    /**
     * Show all events for public UI.
     */
    public function front()
    {
        $events = Event::where('audience_type', 'all')
                        ->where('event_date', '>=', Carbon::now()->toDateString())
                        ->latest()
                        ->paginate(9);
        return view('dashboard.admin.events.front', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $majors = Major::all();
        $currentYear = Carbon::now()->year;
        $years = range($currentYear + 10, $currentYear - 30);
        rsort($years);

        return view('dashboard.admin.events.create', compact('majors', 'years'));
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required',
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'rsvp_required' => 'required|boolean',
            'audience_type' => 'required|in:all,major_only,year_only,major_and_year',
            'max_attendees' => 'nullable|integer|min:1',
            'is_paid' => 'required|boolean',
            'price' => 'nullable|numeric|min:0.01|required_if:is_paid,1',
            'image' => 'nullable|image|max:2048',
        ];

        if ($request->audience_type == 'major_only' || $request->audience_type == 'major_and_year') {
            $rules['target_majors'] = 'required|array|min:1';
            $rules['target_majors.*'] = 'integer|exists:majors,id'; // Pastikan sudah integer di sini
        }
        if ($request->audience_type == 'year_only' || $request->audience_type == 'major_and_year') {
            $rules['target_years'] = 'required|array|min:1';
            $rules['target_years.*'] = 'integer|min:1900|max:' . (Carbon::now()->year + 10);
        }

        $validatedData = $request->validate($rules); // Validasi dan ambil data yang sudah bersih

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        $targetMajors = null;
        if (in_array($validatedData['audience_type'], ['major_only', 'major_and_year'])) {
            // Gunakan validatedData['target_majors'] yang sudah pasti array karena validasi 'required|array'
            // dan pastikan intval diterapkan
            $targetMajors = array_map('intval', $validatedData['target_majors']);
        }

        $targetYears = null;
        if (in_array($validatedData['audience_type'], ['year_only', 'major_and_year'])) {
            $targetYears = array_map('intval', $validatedData['target_years']);
        }

        Event::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'event_date' => $validatedData['event_date'],
            'event_time' => $validatedData['event_time'],
            'location' => $validatedData['location'],
            'rsvp_required' => $validatedData['rsvp_required'],
            'audience_type' => $validatedData['audience_type'],
            'target_majors' => $targetMajors,
            'target_years' => $targetYears,
            'max_attendees' => $validatedData['max_attendees'],
            'is_paid' => $validatedData['is_paid'],
            'price' => $validatedData['is_paid'] ? $validatedData['price'] : null,
            'image' => $imagePath,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('dashboard.admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing an event.
     */
    public function edit(Event $event)
    {
        $majors = Major::all();
        $currentYear = Carbon::now()->year;
        $years = range($currentYear + 10, $currentYear - 30);
        rsort($years);

        return view('dashboard.admin.events.edit', compact('event', 'majors', 'years'));
    }

    /**
     * Update an existing event.
     */
    public function update(Request $request, Event $event)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required',
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'rsvp_required' => 'required|boolean',
            'audience_type' => 'required|in:all,major_only,year_only,major_and_year',
            'max_attendees' => 'nullable|integer|min:1',
            'is_paid' => 'required|boolean',
            'price' => 'nullable|numeric|min:0.01|required_if:is_paid,1',
            'image' => 'nullable|image|max:2048',
        ];

        if ($request->audience_type == 'major_only' || $request->audience_type == 'major_and_year') {
            $rules['target_majors'] = 'required|array|min:1';
            $rules['target_majors.*'] = 'integer|exists:majors,id';
        }
        if ($request->audience_type == 'year_only' || $request->audience_type == 'major_and_year') {
            $rules['target_years'] = 'required|array|min:1';
            $rules['target_years.*'] = 'integer|min:1900|max:' . (Carbon::now()->year + 10);
        }

        $validatedData = $request->validate($rules);

        $imagePath = $event->image;
        if ($request->hasFile('image')) {
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            $imagePath = $request->file('image')->store('events', 'public');
        }

        $targetMajors = null;
        if (in_array($validatedData['audience_type'], ['major_only', 'major_and_year'])) {
            $targetMajors = array_map('intval', $validatedData['target_majors']);
        }

        $targetYears = null;
        if (in_array($validatedData['audience_type'], ['year_only', 'major_and_year'])) {
            $targetYears = array_map('intval', $validatedData['target_years']);
        }

        $event->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'event_date' => $validatedData['event_date'],
            'event_time' => $validatedData['event_time'],
            'location' => $validatedData['location'],
            'rsvp_required' => $validatedData['rsvp_required'],
            'audience_type' => $validatedData['audience_type'],
            'target_majors' => $targetMajors,
            'target_years' => $targetYears,
            'max_attendees' => $validatedData['max_attendees'],
            'is_paid' => $validatedData['is_paid'],
            'price' => $validatedData['is_paid'] ? $validatedData['price'] : null,
            'image' => $imagePath,
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Delete an event.
     */
    public function destroy(Event $event)
    {
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    public function detail(Event $event) // Method ini tetap 'detail'
    {
        return view('events.detailEvent', compact('event')); // <-- PERUBAHAN DI SINI
    }
}