<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-red-600 text-white p-5 min-h-screen flex flex-col shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-center">Admin Dashboard</h2>
            <ul class="space-y-4 flex-grow">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="block p-3 rounded-lg hover:bg-red-500 transition-colors duration-200
                                {{ request()->routeIs('admin.dashboard') ? 'bg-red-700 font-semibold' : '' }}">
                        <i class="fas fa-home mr-3 text-lg"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}"
                        class="block p-3 rounded-lg hover:bg-red-500 transition-colors duration-200
                                {{ request()->routeIs('users.index') ? 'bg-red-700 font-semibold' : '' }}">
                        <i class="fas fa-users mr-3 text-lg"></i> Users
                    </a>
                </li>
               <li>
                    <a href="{{ route('majors.index') }}"
                        class="block p-3 rounded-lg hover:bg-red-500 transition-colors duration-200
                            {{ request()->routeIs('majors.index') ? 'bg-red-700 font-semibold' : '' }}">
                        <i class="fas fa-graduation-cap mr-3 text-lg"></i> Jurusan
                    </a>
                </li>
                <li>
                    <a href="{{ route('about.index') }}"
                        class="block p-3 rounded-lg hover:bg-red-500 transition-colors duration-200
                                {{ request()->routeIs('about.index') ? 'bg-red-700 font-semibold' : '' }}">
                        <i class="fas fa-info-circle mr-3 text-lg"></i> Manage About Us
                    </a>
                </li>
                <li>
                    <a href="/admin/blogs" {{-- Menggunakan URL langsung karena route()->routeIs() tidak fleksibel untuk route group tanpa name --}}
                        class="block p-3 rounded-lg hover:bg-red-500 transition-colors duration-200
                        {{ request()->is('admin/blogs*') ? 'bg-red-700 font-semibold' : '' }}"> {{-- Gunakan request()->is() untuk sub-route --}}
                        <i class="fas fa-blog mr-3 text-lg"></i> Blogs
                    </a>
                </li>
                <li>
                    <a href="{{ route('events.index') }}"
                        class="block p-3 rounded-lg hover:bg-red-500 transition-colors duration-200
                                {{ request()->routeIs('events.index') ? 'bg-red-700 font-semibold' : '' }}">
                        <i class="fas fa-calendar mr-3 text-lg"></i> Events
                    </a>
                </li>
                <li>
                    <a href="/admin/announcements" {{-- Menggunakan URL langsung karena route()->routeIs() tidak fleksibel untuk route group tanpa name --}}
                        class="block p-3 rounded-lg hover:bg-red-500 transition-colors duration-200
                        {{ request()->is('admin/announcements*') ? 'bg-red-700 font-semibold' : '' }}"> {{-- Gunakan request()->is() untuk sub-route --}}
                        <i class="fas fa-bullhorn mr-3 text-lg"></i> Announcements
                    </a>
                </li>
            </ul>

            {{-- Logout Button at the bottom of the sidebar --}}
            <div class="mt-auto pt-6 border-t border-red-700">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                   class="block p-3 rounded-lg hover:bg-red-500 transition-colors duration-200 text-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <nav class="bg-white shadow-md p-4 text-gray-800">
                <div class="container mx-auto flex justify-between items-center">
                    <h1 class="text-xl font-semibold">Welcome, Admin</h1>
                    {{-- Logout Button in navbar (optional, if you want it here too) --}}
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