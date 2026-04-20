<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@isset($pageTitle){{ $pageTitle }} — @endisset Digital Barangay Blotter</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/logo.png') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#f0fdf4',
                            100: '#dcfce7',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex">

{{-- Sidebar --}}
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0a2e18] h-full flex flex-col overflow-y-auto -translate-x-full md:translate-x-0 md:sticky md:top-0 md:h-screen md:flex-shrink-0 transition-transform duration-300 ease-in-out">

    <button onclick="toggleSidebar()" class="md:hidden absolute top-3 right-3 p-2 rounded-lg text-green-300 hover:text-white hover:bg-green-800 transition-colors z-20">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-16 -left-16 w-64 h-64 bg-white opacity-5 rounded-full"></div>
        <div class="absolute top-1/2 -right-20 w-56 h-56 bg-white opacity-5 rounded-full"></div>
        <div class="absolute -bottom-20 -left-10 w-72 h-72 bg-white opacity-5 rounded-full"></div>
    </div>

    <div class="px-5 py-6 border-b border-green-700 relative z-10">
        <div class="flex flex-col items-center gap-3 text-center">
            <div class="w-28 h-28 rounded-full flex-shrink-0 overflow-hidden bg-white p-0 ring-4 ring-white shadow-xl">
                <img src="{{ asset('assets/logo.png') }}" alt="Barangay Caranas Logo" class="w-full h-full rounded-full object-cover bg-white" />
            </div>
            <div>
                <p class="text-white font-extrabold text-base leading-tight tracking-wide" style="text-shadow: 0 1px 4px rgba(0,0,0,0.5);">Brgy. Caranas</p>
                <p class="text-white text-xs font-semibold tracking-wide" style="text-shadow: 0 1px 3px rgba(0,0,0,0.4);">Motiong, Samar</p>
            </div>
        </div>
    </div>

    @php
        $currentPath = request()->path();
        function isActive(string $segment): string {
            return str_starts_with(request()->path(), ltrim($segment, '/'))
                ? 'bg-white text-green-900 font-semibold'
                : 'text-green-100 hover:bg-green-800 hover:text-white';
        }
        function isDashboardActive(): string {
            return request()->is('/') || request()->path() === ''
                ? 'bg-white text-green-900 font-semibold'
                : 'text-green-100 hover:bg-green-800 hover:text-white';
        }
        $authUser = session('user', []);
    @endphp

    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto relative z-10">
        <p class="text-xs font-semibold text-green-400 uppercase tracking-wider px-2 mb-2">{{ __('nav.main_menu') }}</p>

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-white text-green-900 font-semibold' : 'text-green-100 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            {{ __('nav.dashboard') }}
        </a>

        <a href="{{ route('incidents.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('incidents.*') && !request()->routeIs('incidents.create') ? 'bg-white text-green-900 font-semibold' : 'text-green-100 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            {{ __('nav.incidents') }}
        </a>

        <a href="{{ route('incidents.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('incidents.create') ? 'bg-white text-green-900 font-semibold' : 'text-green-100 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('nav.add_incident') }}
        </a>

        <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('reports.*') ? 'bg-white text-green-900 font-semibold' : 'text-green-100 hover:bg-green-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            {{ __('nav.reports') }}
        </a>

        @if(session('user.role') === 'admin')
        <div class="pt-3 mt-2 border-t border-green-700">
            <p class="text-xs font-semibold text-green-400 uppercase tracking-wider px-2 mb-2">{{ __('nav.admin') }}</p>
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('users.*') ? 'bg-white text-green-900 font-semibold' : 'text-green-100 hover:bg-green-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                {{ __('nav.users') }}
            </a>
            <a href="{{ route('incident-types.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('incident-types.*') ? 'bg-white text-green-900 font-semibold' : 'text-green-100 hover:bg-green-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                {{ __('nav.incident_types') }}
            </a>
            <a href="{{ route('audit.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('audit.*') ? 'bg-white text-green-900 font-semibold' : 'text-green-100 hover:bg-green-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Audit Log
            </a>
        </div>
        @endif
    </nav>

    <div class="px-4 py-4 border-t border-green-700 relative z-10">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                {{ strtoupper(substr($authUser['full_name'] ?? 'U', 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-white text-sm font-medium truncate">{{ $authUser['full_name'] ?? '' }}</p>
                <p class="text-green-300 text-xs capitalize">{{ $authUser['role'] ?? '' }}</p>
            </div>
        </div>

        <a href="{{ route('logout') }}"
           class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-green-800 hover:bg-red-700 text-green-100 hover:text-white rounded-lg text-sm transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            {{ __('nav.logout') }}
        </a>

        <div class="flex items-center gap-1 bg-green-800/60 rounded-xl p-1">
            <a href="{{ route('lang.set', 'en') }}"
               class="flex-1 text-center text-xs font-semibold py-1.5 rounded-lg transition-colors {{ session('lang', 'en') === 'en' ? 'bg-white text-green-800 shadow-sm' : 'text-green-300 hover:text-white' }}">
                ENGLISH
            </a>
            <a href="{{ route('lang.set', 'war') }}"
               class="flex-1 text-center text-xs font-semibold py-1.5 rounded-lg transition-colors {{ session('lang') === 'war' ? 'bg-white text-green-800 shadow-sm' : 'text-green-300 hover:text-white' }}">
                WARAY
            </a>
        </div>
    </div>
</aside>

<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

@yield('content')

<script src="{{ asset('js/main.js') }}"></script>
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const isHidden = sidebar.classList.contains('-translate-x-full');
    if (isHidden) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
}
window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) {
        const overlay = document.getElementById('sidebar-overlay');
        if (overlay) overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
});
</script>
@stack('scripts')
</body>
</html>
