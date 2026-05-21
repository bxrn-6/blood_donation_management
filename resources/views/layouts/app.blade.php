<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Blood Donation Management System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="app-shell">
            @auth
                <nav class="app-navbar">
                    <div class="max-w-7xl mx-auto px-4">
                        <div class="flex flex-col md:flex-row justify-between h-16 items-center gap-4 md:gap-0">
                            <div class="flex items-center">
                                <a href="{{ route('dashboard') }}" class="app-brand">
                                    <i class="fas fa-tint mr-2"></i>Blood 4 Life 
                                </a>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <!-- Navigation Links -->
                                <div class="hidden md:flex space-x-4">
<a href="{{ route('dashboard') }}" class="app-nav-link">
                                        <i class="fas fa-home mr-1"></i>Dashboard
                                    </a>
                                    
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('donors.index') }}" class="app-nav-link">
                                            <i class="fas fa-users mr-1"></i>Donors
                                        </a>
                                        <a href="{{ route('blood-inventory.index') }}" class="app-nav-link">
                                            <i class="fas fa-flask mr-1"></i>Inventory
                                        </a>
                                        <a href="{{ route('blood-requests.index') }}" class="app-nav-link">
                                            <i class="fas fa-hand-holding-medical mr-1"></i>Requests
                                        </a>
                                        <a href="{{ route('donations.index') }}" class="app-nav-link">
                                            <i class="fas fa-hand-holding-heart mr-1"></i>Donations
                                        </a>
                                        <a href="{{ route('donor-matches.index') }}" class="app-nav-link">
                                            <i class="fas fa-link mr-1"></i>Matches
                                        </a>
                                        <a href="{{ route('reports.index') }}" class="app-nav-link">
                                            <i class="fas fa-chart-bar mr-1"></i>Reports
                                        </a>
                                    @elseif(auth()->user()->isDonor())
                                        <a href="{{ route('donor.dashboard') }}" class="app-nav-link">
                                            <i class="fas fa-tachometer-alt mr-1"></i>My Dashboard
                                        </a>
                                        @if(auth()->user()->donor)
                                            <a href="{{ route('donors.show', auth()->user()->donor->id) }}" class="app-nav-link">
                                                <i class="fas fa-user mr-1"></i>My Profile
                                            </a>
                                            <a href="{{ route('donations.index') }}" class="app-nav-link">
                                                <i class="fas fa-hand-holding-heart mr-1"></i>My Donations
                                            </a>
                                        @else
                                            <a href="{{ route('donors.create') }}" class="app-nav-link">
                                                <i class="fas fa-user-plus mr-1"></i>Register Profile
                                            </a>
                                        @endif
                                    @elseif(auth()->user()->isHospital())
                                        <a href="{{ route('blood-requests.create') }}" class="app-nav-link">
                                            <i class="fas fa-plus-circle mr-1"></i>New Request
                                        </a>
                                        <a href="{{ route('blood-requests.index') }}" class="app-nav-link">
                                            <i class="fas fa-list mr-1"></i>My Requests
                                        </a>
                                    @endif
                                </div>
                                
                                <!-- User Menu -->
                                <div class="relative">
                                    <button onclick="toggleUserMenu()" class="flex items-center text-slate-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium transition">
                                        <i class="fas fa-user-circle mr-2"></i>{{ auth()->user()->name }}
                                        <i class="fas fa-chevron-down ml-1"></i>
                                    </button>
                                    
                                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-cog mr-2"></i>Profile Settings
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            @endauth

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>

        <script>
            function toggleUserMenu() {
                const menu = document.getElementById('userMenu');
                menu.classList.toggle('hidden');
            }

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                const menu = document.getElementById('userMenu');
                const button = event.target.closest('button');
                
                if (!menu.contains(event.target) && !button) {
                    menu.classList.add('hidden');
                }
            });
        </script>
    </body>
</html>
