<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="ESEF Centralized School Dashboard — Real-time attendance, finance, and academic analytics for school administrators.">

        <title>{{ $title ?? 'Dashboard' }} — {{ config('app.name', 'ESEF Dashboard') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-surface-100 flex" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">

            {{-- Mobile sidebar overlay --}}
            <div class="fixed inset-0 bg-black/50 z-40 lg:hidden"
                 x-show="mobileSidebarOpen"
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileSidebarOpen = false"
                 style="display: none;">
            </div>

            {{-- Sidebar --}}
            <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-sidebar-bg shadow-sidebar transform transition-transform duration-300 ease-out lg:translate-x-0 lg:static lg:z-auto"
                   :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   id="sidebar">

                {{-- Logo / Brand --}}
                <div class="flex items-center gap-3 px-6 py-6 border-b border-surface-800">
                    <div class="w-10 h-10 bg-primary-600 rounded-btn flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-base font-heading font-bold text-white">ESEF</h1>
                        <p class="text-xs text-surface-400">School Dashboard</p>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="px-3 py-6 space-y-1">
                    <p class="px-4 text-xs font-semibold text-surface-500 uppercase tracking-wider mb-3">Main Menu</p>

                    <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        {{-- Lucide: LayoutDashboard --}}
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('attendance') }}" class="sidebar-nav-item {{ request()->routeIs('attendance') ? 'active' : '' }}">
                        {{-- Lucide: ClipboardCheck --}}
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                        <span>Attendance</span>
                    </a>

                    <a href="{{ route('academics') }}" class="sidebar-nav-item {{ request()->routeIs('academics') ? 'active' : '' }}">
                        {{-- Lucide: GraduationCap --}}
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>
                        <span>Academics</span>
                    </a>

                    <a href="{{ route('finance') }}" class="sidebar-nav-item {{ request()->routeIs('finance') ? 'active' : '' }}">
                        {{-- Lucide: Wallet --}}
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                        <span>Finance</span>
                    </a>

                    @if(auth()->user()?->isAdmin())
                    <a href="{{ route('staff') }}" class="sidebar-nav-item {{ request()->routeIs('staff') ? 'active' : '' }}">
                        {{-- Lucide: Users --}}
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Staff</span>
                    </a>
                    @endif

                    <div class="pt-4 mt-4 border-t border-surface-800">
                        <p class="px-4 text-xs font-semibold text-surface-500 uppercase tracking-wider mb-3">Account</p>

                        <a href="{{ route('profile.edit') }}" class="sidebar-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            {{-- Lucide: Settings --}}
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span>Settings</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="sidebar-nav-item w-full text-left hover:text-danger">
                                {{-- Lucide: LogOut --}}
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            {{-- Main Content Area --}}
            <div class="flex-1 flex flex-col min-h-screen lg:ml-0">

                {{-- Top Bar --}}
                <header class="bg-white border-b border-surface-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
                    <div class="flex items-center gap-4">
                        {{-- Mobile menu toggle --}}
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="lg:hidden text-surface-500 hover:text-surface-700 transition-colors">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                        </button>

                        {{-- Page title / breadcrumb --}}
                        <div>
                            <h2 class="text-lg font-heading font-semibold text-surface-900">{{ $pageTitle ?? 'Dashboard' }}</h2>
                            @isset($breadcrumb)
                                <p class="text-xs text-surface-400 mt-0.5">{{ $breadcrumb }}</p>
                            @endisset
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        {{-- Search --}}
                        <div class="hidden md:flex items-center bg-surface-50 rounded-btn px-3 py-2 border border-surface-200 focus-within:ring-2 focus-within:ring-primary-500/30 focus-within:border-primary-400 transition-all">
                            <svg class="w-4 h-4 text-surface-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <input type="text" placeholder="Search..." class="bg-transparent border-none text-sm text-surface-700 placeholder-surface-400 focus:ring-0 focus:outline-none w-44">
                        </div>

                        {{-- Notification bell --}}
                        <button class="relative text-surface-400 hover:text-surface-600 transition-colors p-2 rounded-btn hover:bg-surface-50">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        </button>

                        {{-- User avatar/menu --}}
                        <div class="flex items-center gap-3 pl-3 border-l border-surface-200" x-data="{ userMenuOpen: false }">
                            <div class="w-9 h-9 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-semibold cursor-pointer"
                                 @click="userMenuOpen = !userMenuOpen">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="hidden sm:block">
                                <p class="text-sm font-medium text-surface-800 leading-tight">{{ auth()->user()->name ?? 'User' }}</p>
                                <p class="text-xs text-surface-400 capitalize">{{ auth()->user()->role ?? 'admin' }}</p>
                            </div>

                            {{-- Dropdown --}}
                            <div x-show="userMenuOpen"
                                 @click.outside="userMenuOpen = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-6 top-16 w-48 bg-white rounded-btn shadow-lg border border-surface-200 py-1 z-50"
                                 style="display: none;">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-surface-700 hover:bg-surface-50 transition-colors">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-surface-700 hover:bg-surface-50 transition-colors">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Page Content --}}
                <main class="flex-1 p-6 lg:p-8">
                    {{ $slot }}
                </main>

                {{-- Footer --}}
                <footer class="px-6 py-4 border-t border-surface-200 bg-white">
                    <p class="text-xs text-surface-400 text-center">© {{ date('Y') }} ESEF Centralized Dashboard. All rights reserved.</p>
                </footer>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
