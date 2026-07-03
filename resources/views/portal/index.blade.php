<x-portal.app :user="$user" :roleLabel="$roleLabel" pageTitle="System Launcher">

    <!-- Hero Section -->
    <div class="text-center mb-16">
        <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-4 tracking-tight">
            Welcome, {{ $user->name }}
        </h2>
        <div class="flex items-center justify-center gap-2 mb-5">
            <span class="inline-flex items-center px-5 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/30">
                {{ $roleLabel }}
            </span>
        </div>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto font-light">
            Access your authorized ESEF systems below. Select a system to launch and continue your work.
        </p>
    </div>

    <!-- Demo Role Switcher (Admin Only) -->
    @if($canSwitchRole)
        <div class="max-w-4xl mx-auto mb-12 bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-200 rounded-2xl p-6 shadow-xl" x-data="{ switching: false }">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-amber-900 mb-2">Demo Mode Active</h3>
                    <p class="text-sm text-amber-800 mb-4 leading-relaxed">View the portal as different roles to preview system access. Changes are visual only and do not affect permissions.</p>
                    
                    <div class="flex flex-wrap gap-3">
                        @foreach($demoRoleChoices as $choice)
                            <form method="POST" action="{{ route('portal.switch-role') }}" class="inline-block">
                                @csrf
                                <input type="hidden" name="role" value="{{ $choice['value'] }}">
                                <button 
                                    type="submit"
                                    @if($choice['active']) disabled @endif
                                    class="px-5 py-2.5 text-sm font-semibold rounded-xl transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 {{ $choice['active'] ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white cursor-default scale-105' : 'bg-white text-amber-900 border-2 border-amber-300 hover:bg-amber-50 hover:border-amber-400' }}"
                                    x-bind:disabled="switching"
                                    @click="switching = true"
                                >
                                    {{ $choice['label'] }}
                                    @if($choice['active'])
                                        <span class="ml-1.5 font-bold">✓</span>
                                    @endif
                                </button>
                            </form>
                        @endforeach

                        @if($isDemoActive)
                            <form method="POST" action="{{ route('portal.reset-role') }}" class="inline-block">
                                @csrf
                                <button 
                                    type="submit"
                                    class="px-5 py-2.5 text-sm font-semibold rounded-xl bg-slate-800 text-white hover:bg-slate-900 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                    x-bind:disabled="switching"
                                    @click="switching = true"
                                >
                                    Reset to My Role
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Status Messages -->
    @if(session('status'))
        <div class="max-w-3xl mx-auto mb-8 bg-blue-50 border border-blue-200 rounded-lg p-4" x-data="{ show: true }" x-show="show" x-transition>
            <div class="flex items-center justify-between">
                <p class="text-sm text-blue-800">{{ session('status') }}</p>
                <button @click="show = false" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Systems Grid -->
    @if($systems->isEmpty())
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="7" height="7" x="3" y="3" rx="1"/>
                <rect width="7" height="7" x="14" y="3" rx="1"/>
                <rect width="7" height="7" x="14" y="14" rx="1"/>
                <rect width="7" height="7" x="3" y="14" rx="1"/>
            </svg>
            <h3 class="text-lg font-semibold text-slate-900 mb-2">No Systems Available</h3>
            <p class="text-sm text-slate-600">You don't have access to any systems yet. Contact your administrator for access.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" 
             x-data="{ activeRole: '{{ $effectiveRole }}' }" 
             x-init="$watch('activeRole', value => { 
                 document.querySelectorAll('.system-card').forEach(card => {
                     card.style.animation = 'fadeIn 0.4s ease-out';
                 });
             })">
            
            {{-- Admin Panel Card (Admin Only) --}}
            @if(auth()->user()?->isAdmin())
                <div class="system-card group bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-md hover:shadow-2xl border-2 border-slate-700 hover:border-slate-600 transition-all duration-300 overflow-hidden transform hover:-translate-y-2"
                     style="animation: fadeIn 0.5s ease-out backwards;">
                    
                    <!-- Accent Bar -->
                    <div class="h-2 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-500"></div>
                    
                    <div class="p-8">
                        <!-- Icon with Glow Effect -->
                        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-6 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-lg bg-gradient-to-br from-amber-500/20 to-orange-500/20 border-2 border-amber-500/30">
                            <svg class="w-10 h-10 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M12 8v4"/>
                                <circle cx="12" cy="16" r="0.5" fill="currentColor"/>
                            </svg>
                        </div>

                        <!-- System Name -->
                        <h3 class="text-xl font-bold text-white mb-3 group-hover:text-amber-400 transition-colors leading-tight">
                            Admin Panel
                        </h3>

                        <!-- Description -->
                        <p class="text-sm text-slate-300 mb-6 leading-relaxed min-h-[2.5rem]">
                            Manage systems, roles, permissions, and platform settings
                        </p>

                        <!-- Action Button -->
                        <a href="{{ route('admin.systems.index') }}" 
                           class="block w-full px-5 py-3.5 rounded-xl text-sm font-bold text-center transition-all shadow-md hover:shadow-xl transform hover:scale-105 bg-gradient-to-r from-amber-500 to-orange-500 text-white border-2 border-amber-400/50 hover:border-amber-300">
                            Open Admin Panel
                            <span class="inline-block transition-transform group-hover:translate-x-1">→</span>
                        </a>
                    </div>
                </div>
            @endif

            @foreach($systems as $system)
                <div class="system-card group bg-white rounded-2xl shadow-md hover:shadow-2xl border-2 border-slate-100 hover:border-slate-200 transition-all duration-300 overflow-hidden transform hover:-translate-y-2"
                     style="animation: fadeIn 0.5s ease-out {{ $loop->index * 0.08 }}s backwards;">
                    
                    <!-- Accent Bar -->
                    <div class="h-2" style="background: linear-gradient(90deg, {{ $system->accent_color }}, {{ $system->accent_color }}dd);"></div>
                    
                    <div class="p-8">
                        <!-- Icon with Glow Effect -->
                        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-6 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-lg"
                             style="background: linear-gradient(135deg, {{ $system->accent_color }}25, {{ $system->accent_color }}15); box-shadow: 0 8px 16px {{ $system->accent_color }}20;">
                            @include('portal.partials.system-icon', ['icon' => $system->icon, 'color' => $system->accent_color])
                        </div>

                        <!-- System Name -->
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-primary-600 transition-colors leading-tight">
                            {{ $system->name }}
                        </h3>

                        <!-- Description -->
                        <p class="text-sm text-slate-600 mb-6 line-clamp-2 leading-relaxed min-h-[2.5rem]">
                            {{ $system->description }}
                        </p>

                        <!-- Action Button -->
                        @if($system->coming_soon)
                            <button disabled class="w-full px-5 py-3.5 bg-slate-100 text-slate-400 rounded-xl text-sm font-bold cursor-not-allowed flex items-center justify-center gap-2 border-2 border-slate-200">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                Coming Soon
                            </button>
                        @else
                            <a href="{{ route('portal.launch', $system->slug) }}" 
                               class="block w-full px-5 py-3.5 rounded-xl text-sm font-bold text-center transition-all shadow-md hover:shadow-xl transform hover:scale-105 border-2"
                               style="background: linear-gradient(135deg, {{ $system->accent_color }}, {{ $system->accent_color }}dd); color: white; border-color: {{ $system->accent_color }};">
                                Launch System 
                                <span class="inline-block transition-transform group-hover:translate-x-1">→</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @push('scripts')
        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    @endpush

</x-portal.app>
