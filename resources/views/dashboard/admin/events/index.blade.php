{{-- resources/views/admin/events/index.blade.php --}}

@extends('layouts.admindashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-gray-50 min-h-screen">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <h2 class="text-4xl font-extrabold text-gray-800 mb-4 sm:mb-0">
            <i class="fas fa-calendar-check text-purple-600 mr-4"></i> My Events
        </h2>
        <a href="{{ route('events.create') }}"
           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-lg
                 hover:bg-blue-700 transform hover:scale-105 transition-all duration-300 ease-in-out">
            <i class="fas fa-plus-circle mr-3"></i> Add New Event
        </a>
    </div>

    {{-- SweetAlert Success Message --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK",
                    confirmButtonColor: '#4CAF50'
                });
            });
        </script>
    @endif

    {{-- Events Grid --}}
    @if($events->isEmpty())
        <div class="bg-white rounded-xl shadow-lg p-12 max-w-lg mx-auto text-center
                     transform hover:scale-105 transition duration-500 ease-in-out border border-gray-200">
            <div class="mb-6">
                <div class="w-28 h-28 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-gray-400 text-7xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-2">No Events Yet!</h3>
                <p class="text-gray-600 text-lg">It seems you haven't created any events. Let's start!</p>
            </div>
            <a href="{{ route('events.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-full
                                                                hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition duration-300 shadow-lg">
                <i class="fas fa-plus-circle mr-2"></i> Create Your First Event
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
            @foreach ($events as $event)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-6 flex flex-col h-full border border-gray-100
                              transform hover:scale-105 transition-transform duration-500 ease-in-out">
                    {{-- Event Image --}}
                    @if ($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                             class="rounded-lg mb-4 w-full h-52 object-cover shadow-sm">
                    @else
                        <div class="w-full h-52 bg-gradient-to-br from-gray-200 to-gray-300
                                          rounded-lg mb-4 flex items-center justify-center text-gray-400 text-7xl shadow-sm">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif

                    {{-- Event Details --}}
                    <h3 class="text-2xl font-bold text-gray-900 mb-3 leading-tight">{{ $event->title }}</h3>
                    <p class="text-gray-700 text-base mb-4 flex-grow">{{ Str::limit($event->description, 120) }}</p>

                    <div class="text-gray-600 text-sm space-y-3 mb-6">
                        <p class="flex items-center">
                            <i class="fas fa-calendar-alt text-purple-500 mr-3 text-lg"></i>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-clock text-blue-500 mr-3 text-lg"></i>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-map-marker-alt text-green-500 mr-3 text-lg"></i>
                            <span class="font-semibold">{{ $event->location }}</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-users text-pink-500 mr-3 text-lg"></i>
                            @if($event->rsvp_required)
                                <span class="text-green-600 font-semibold">RSVP Required</span>
                            @else
                                <span class="text-red-600 font-semibold">No RSVP Required</span>
                            @endif
                        </p>
                        @if($event->is_paid)
                        <p class="flex items-center">
                            <i class="fas fa-dollar-sign text-orange-500 mr-3 text-lg"></i>
                            <span class="font-semibold text-orange-700">Rp{{ number_format($event->price, 0, ',', '.') }}</span>
                        </p>
                        @else
                        <p class="flex items-center">
                            <i class="fas fa-tag text-teal-500 mr-3 text-lg"></i>
                            <span class="font-semibold text-teal-700">Free Event</span>
                        </p>
                        @endif
                        
                        {{-- Audience Type Display (New Feature) --}}
                        <p class="flex items-center">
                            <i class="fas fa-bullseye text-indigo-500 mr-3 text-lg"></i>
                            <span class="font-semibold text-indigo-700">
                                @if($event->audience_type === 'all')
                                    All Alumni
                                @elseif($event->audience_type === 'major_only')
                                    Specific Major(s)
                                @elseif($event->audience_type === 'year_only')
                                    Specific Year(s)
                                @elseif($event->audience_type === 'major_and_year')
                                    Specific Major(s) & Year(s)
                                @endif
                            </span>
                        </p>
                    </div>

                    {{-- Actions (Edit/Delete/Registrations) --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-auto pt-4 border-t border-gray-100">
                        <div class="mb-2 sm:mb-0 sm:mr-2">
                            <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white font-semibold rounded-md shadow-md
                                        hover:bg-yellow-600 transform hover:scale-105 transition duration-300">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                        </div>
                        <div class="mb-2 sm:mb-0 sm:mr-2">
                            <a href="{{ route('admin.events.registrations.index', $event->id) }}" class="inline-flex items-center px-4 py-2 bg-green-500 text-white font-semibold rounded-md shadow-md
                                        hover:bg-green-600 transform hover:scale-105 transition duration-300">
                                <i class="fas fa-users mr-2"></i> Registrations ({{ $event->eventRegistrations()->count() }})
                            </a>
                        </div>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirmDelete(event);">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-md shadow-md
                                                        hover:bg-red-700 transform hover:scale-105 transition duration-300">
                                <i class="fas fa-trash-alt mr-2"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div class="mt-10 flex justify-center">
            {{ $events->links('vendor.pagination.tailwind') }} {{-- Memastikan menggunakan Tailwind Pagination --}}
        </div>
    @endif
</div>

<script>
    // Fungsi konfirmasi SweetAlert untuk delete
    function confirmDelete(event) {
        event.preventDefault(); // Mencegah form langsung submit
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit(); // Jika dikonfirmasi, submit form
            }
        });
        return false; // Mencegah form submit default
    }
</script>
@endsection