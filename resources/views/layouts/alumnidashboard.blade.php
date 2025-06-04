<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    {{-- Jika Anda menggunakan Font Awesome 5, gunakan link ini: --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" xintegrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    <style>
        /* Opsional: Styling scrollbar kustom untuk UX yang lebih baik */
        body::-webkit-scrollbar {
            width: 8px;
        }
        body::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        body::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        body::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-rose-600 text-white p-5 flex flex-col shadow-lg">
            <h2 class="text-2xl font-bold mb-8 text-center">Alumni Connect</h2>
            <ul class="space-y-4 flex-grow">
                <li>
                    <a href="{{ route('alumni.dashboard') }}"
                        class="block p-3 rounded-lg hover:bg-rose-500 transition-colors duration-200
                                {{ request()->routeIs('alumni.dashboard') ? 'bg-rose-700 font-semibold' : '' }}">
                        <i class="fas fa-home mr-3 text-lg"></i> Dashboard
                    </a>
                </li>
                <li>
                    {{-- Diperbarui: Menggunakan URL langsung dan request()->is untuk highlight --}}
                    <a href="/alumni/profile"
                        class="block p-3 rounded-lg hover:bg-rose-500 transition-colors duration-200
                                {{ request()->is('alumni/profile') || request()->is('alumni/profile/edit') ? 'bg-rose-700 font-semibold' : '' }}">
                        <i class="fas fa-user-circle mr-3 text-lg"></i> Profil Saya
                    </a>
                </li>
                {{-- Link untuk Direktori Alumni --}}
                <li>
                    {{-- Diperbarui: Menggunakan route('directory.index') dan request()->routeIs('directory.*') --}}
                    <a href="/alumni/directory"
                        class="block p-3 rounded-lg hover:bg-rose-500 transition-colors duration-200
                                {{ request()->is('alumni/directory') || request()->is('alumni/directory/show')? 'bg-rose-700 font-semibold' : '' }}">
                        <i class="fas fa-users mr-3 text-lg"></i> Direktori Alumni
                    </a>
                </li>
                <li>
                    <a href="{{ route('alumni.events.index') }}"
                        class="block p-3 rounded-lg hover:bg-rose-500 transition-colors duration-200
                                {{ request()->routeIs('alumni.events.index') ? 'bg-rose-700 font-semibold' : '' }}">
                        <i class="fas fa-calendar-alt mr-3 text-lg"></i> Acara
                    </a>
                </li>
                <li>
                    <a href="{{ route('alumni.blogs.index') }}"
                        class="block p-3 rounded-lg hover:bg-rose-500 transition-colors duration-200
                                {{ request()->routeIs('alumni.blogs.index') ? 'bg-rose-700 font-semibold' : '' }}">
                        <i class="fas fa-blog mr-3 text-lg"></i> Blog
                    </a>
                </li>
                <li>
                    <a href="{{ route('alumni.announcements.index') }}"
                        class="block p-3 rounded-lg hover:bg-rose-500 transition-colors duration-200
                                {{ request()->routeIs('alumni.announcements.index') ? 'bg-rose-700 font-semibold' : '' }}">
                        <i class="fas fa-bullhorn mr-3 text-lg"></i> Pengumuman
                    </a>
                </li>
            </ul>

            {{-- Tombol Logout di bagian bawah sidebar --}}
            <div class="mt-auto pt-6 border-t border-rose-700">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                   class="block p-3 rounded-lg hover:bg-rose-500 transition-colors duration-200 text-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <nav class="bg-white shadow-md p-4 text-gray-800">
                <div class="container mx-auto flex justify-between items-center">
                    <h1 class="text-xl font-semibold">Selamat Datang, {{ Auth::user()->name ?? 'Alumni' }}</h1>
                    {{-- Tombol Logout di navbar (opsional, jika ingin ada di sini juga) --}}
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();"
                       class="text-red-600 hover:text-red-800 font-medium">
                        Logout
                    </a>
                </div>
            </nav>

            <main class="p-6 flex-1">
                @yield('content')
            </main>

            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <form id="logout-form-navbar" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</body>
</html>