<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="ESEF Centralized EMIS Dashboard — Real-time enrollment, attendance, and facilitator analytics across FCS, ESS, and NSI schemes.">

        <title>{{ $pageTitle ?? 'Dashboard' }} — ESEF EMIS</title>

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
            <aside class="fixed inset-y-0 left-0 z-50 bg-sidebar-bg shadow-sidebar transition-all duration-300 ease-in-out lg:static lg:z-auto flex flex-col border-r border-surface-800"
                   :class="{
                       'translate-x-0 w-64': mobileSidebarOpen,
                       '-translate-x-full lg:translate-x-0': !mobileSidebarOpen,
                       'lg:w-64': sidebarOpen,
                       'lg:w-20': !sidebarOpen
                   }"
                   id="sidebar">

                {{-- ESEF Brand --}}
                <div class="flex items-center gap-3 px-5 py-5 border-b border-surface-800 relative"
                     :class="sidebarOpen ? '' : 'lg:justify-center lg:px-3'">
                    <img src="{{ asset('logo/logo.png') }}" class="w-14 h-14 object-contain flex-shrink-0 bg-white p-0.5 rounded-full" alt="ESEF Logo">
                    <div class="min-w-0" x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <h1 class="text-base font-heading font-bold text-white leading-tight">ESEF</h1>
                        <p class="text-xs text-surface-400 leading-tight">Centralized EMIS Dashboard</p>
                    </div>

                    {{-- Collapse Toggle Button inside Sidebar --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="hidden lg:flex items-center justify-center w-7 h-7 rounded-full bg-sidebar-bg text-surface-400 hover:text-white hover:bg-surface-700 transition-all absolute -right-3.5 top-1/2 -translate-y-1/2 border border-surface-700 z-50 shadow-md"
                            title="Toggle Sidebar">
                        <svg x-show="sidebarOpen" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"/>
                        </svg>
                        <svg x-show="!sidebarOpen" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </button>
                </div>

                {{-- Navigation --}}
                <nav class="px-3 py-6 space-y-1 flex-1 overflow-y-auto overflow-x-hidden">
                    {{-- Back to Portal Link --}}
                    <a href="{{ route('portal') }}" 
                       class="sidebar-nav-item mb-4 bg-primary-600/10 border border-primary-600/20 hover:bg-primary-600/20 relative group" 
                       :class="sidebarOpen ? 'gap-3 px-4' : 'lg:justify-center lg:px-0 lg:gap-0'"
                       x-bind:title="!sidebarOpen ? 'System Portal' : ''">
                        <svg class="w-5 h-5 flex-shrink-0 text-primary-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="7" x="3" y="3" rx="1"/>
                            <rect width="7" height="7" x="14" y="3" rx="1"/>
                            <rect width="7" height="7" x="14" y="14" rx="1"/>
                            <rect width="7" height="7" x="3" y="14" rx="1"/>
                        </svg>
                        <span x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-semibold text-primary-600">System Portal</span>
                        
                        {{-- Tooltip for collapsed state --}}
                        <span x-show="!sidebarOpen" 
                              class="hidden lg:block absolute left-full ml-2 px-2 py-1 bg-surface-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50"
                              style="display: none;">
                            System Portal
                        </span>
                    </a>

                    <p x-show="sidebarOpen" class="px-4 text-xs font-semibold text-surface-500 uppercase tracking-wider mb-3">Analytics</p>

                    <a href="{{ route('dashboard') }}" 
                       class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }} relative group" 
                       :class="sidebarOpen ? 'gap-3 px-4' : 'lg:justify-center lg:px-0 lg:gap-0'">
                        <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        <span x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Dashboard</span>
                        <span x-show="!sidebarOpen" class="hidden lg:block absolute left-full ml-2 px-2 py-1 bg-surface-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50" style="display: none;">Dashboard</span>
                    </a>

                    <a href="{{ route('districts') }}" 
                       class="sidebar-nav-item {{ request()->routeIs('districts') ? 'active' : '' }} relative group" 
                       :class="sidebarOpen ? 'gap-3 px-4' : 'lg:justify-center lg:px-0 lg:gap-0'">
                        {{-- Map Pin --}}
                        <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Districts</span>
                        <span x-show="!sidebarOpen" class="hidden lg:block absolute left-full ml-2 px-2 py-1 bg-surface-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50" style="display: none;">Districts</span>
                    </a>

                    <a href="{{ route('attendance') }}" 
                       class="sidebar-nav-item {{ request()->routeIs('attendance') ? 'active' : '' }} relative group" 
                       :class="sidebarOpen ? 'gap-3 px-4' : 'lg:justify-center lg:px-0 lg:gap-0'">
                        {{-- Clipboard Check --}}
                        <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                        <span x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Attendance</span>
                        <span x-show="!sidebarOpen" class="hidden lg:block absolute left-full ml-2 px-2 py-1 bg-surface-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50" style="display: none;">Attendance</span>
                    </a>

                    <a href="{{ route('budget') }}" 
                       class="sidebar-nav-item {{ request()->routeIs('budget') ? 'active' : '' }} relative group" 
                       :class="sidebarOpen ? 'gap-3 px-4' : 'lg:justify-center lg:px-0 lg:gap-0'">
                        {{-- Landmark --}}
                        <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>
                        <span x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Budget &amp; Grants</span>
                        <span x-show="!sidebarOpen" class="hidden lg:block absolute left-full ml-2 px-2 py-1 bg-surface-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50" style="display: none;">Budget & Grants</span>
                    </a>

                    @if(auth()->user()?->isAdmin())
                    <a href="{{ route('facilitators') }}" 
                       class="sidebar-nav-item {{ request()->routeIs('facilitators*') ? 'active' : '' }} relative group" 
                       :class="sidebarOpen ? 'gap-3 px-4' : 'lg:justify-center lg:px-0 lg:gap-0'">
                        {{-- Users --}}
                        <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Facilitators</span>
                        <span x-show="!sidebarOpen" class="hidden lg:block absolute left-full ml-2 px-2 py-1 bg-surface-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50" style="display: none;">Facilitators</span>
                    </a>

                    <div class="pt-4 mt-4 border-t border-surface-800">
                        <p x-show="sidebarOpen" class="px-4 text-xs font-semibold text-surface-500 uppercase tracking-wider mb-3">Admin Tools</p>

                        <a href="{{ route('admin.systems.index') }}" 
                           class="sidebar-nav-item {{ request()->routeIs('admin.systems.*') ? 'active' : '' }} relative group" 
                           :class="sidebarOpen ? 'gap-3 px-4' : 'lg:justify-center lg:px-0 lg:gap-0'">
                            {{-- Grid/Systems --}}
                            <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                            <span x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Manage Systems</span>
                            <span x-show="!sidebarOpen" class="hidden lg:block absolute left-full ml-2 px-2 py-1 bg-surface-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50" style="display: none;">Manage Systems</span>
                        </a>

                        <a href="{{ route('admin.roles.index') }}" 
                           class="sidebar-nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }} relative group" 
                           :class="sidebarOpen ? 'gap-3 px-4' : 'lg:justify-center lg:px-0 lg:gap-0'">
                            {{-- Shield --}}
                            <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Manage Roles</span>
                            <span x-show="!sidebarOpen" class="hidden lg:block absolute left-full ml-2 px-2 py-1 bg-surface-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50" style="display: none;">Manage Roles</span>
                        </a>
                    </div>
                    @endif

                    <div class="pt-4 mt-4 border-t border-surface-800">
                        <p x-show="sidebarOpen" class="px-4 text-xs font-semibold text-surface-500 uppercase tracking-wider mb-3">Account</p>

                        <a href="{{ route('profile.edit') }}" 
                           class="sidebar-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }} relative group" 
                           :class="sidebarOpen ? 'gap-3 px-4' : 'lg:justify-center lg:px-0 lg:gap-0'">
                            <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Settings</span>
                            <span x-show="!sidebarOpen" class="hidden lg:block absolute left-full ml-2 px-2 py-1 bg-surface-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50" style="display: none;">Settings</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" 
                                    class="sidebar-nav-item w-full text-left hover:text-danger relative group" 
                                    :class="sidebarOpen ? 'gap-3 px-4' : 'lg:justify-center lg:px-0 lg:gap-0'">
                                <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                <span x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Log Out</span>
                                <span x-show="!sidebarOpen" class="hidden lg:block absolute left-full ml-2 px-2 py-1 bg-surface-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50" style="display: none;">Log Out</span>
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
                        {{-- Mobile Toggle --}}
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="lg:hidden text-surface-500 hover:text-surface-700 transition-colors">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                        </button>

                        <div>
                            <h2 class="text-lg font-heading font-semibold text-surface-900">{{ $pageTitle ?? 'Dashboard' }}</h2>
                            @isset($breadcrumb)
                                <p class="text-xs text-surface-400 mt-0.5">{{ $breadcrumb }}</p>
                            @endisset
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
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
                    <p class="text-xs text-surface-400 text-center">© {{ date('Y') }} Elementary & Secondary Education Foundation (ESEF), KP. Centralized EMIS Dashboard.</p>
                </footer>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
