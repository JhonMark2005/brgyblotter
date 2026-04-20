<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login — Digital Barangay Blotter</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/logo.png') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .hero-pattern {
            background-image:
                radial-gradient(circle at 20% 20%, rgba(255,255,255,0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.05) 0%, transparent 50%),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .input-field { transition: all 0.2s ease; }
        .input-field:focus { box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15); }
        .btn-signin { background: linear-gradient(135deg, #15803d 0%, #166534 100%); transition: all 0.2s ease; }
        .btn-signin:hover { background: linear-gradient(135deg, #166534 0%, #14532d 100%); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(22, 163, 74, 0.4); }
        .btn-signin:active { transform: translateY(0); }
        .logo-ring { background: conic-gradient(from 0deg, #15803d, #4ade80, #15803d); animation: spin 8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .stat-card { background: rgba(255,255,255,0.08); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.12); }
        body { background-color: #e8ede9; background-image: radial-gradient(circle at 15% 85%, rgba(21,128,61,0.08) 0%, transparent 45%), radial-gradient(circle at 85% 15%, rgba(21,128,61,0.06) 0%, transparent 40%); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden flex min-h-[600px]">

    <div class="left-panel hidden md:flex w-2/5 bg-gradient-to-br from-[#0a2e18] via-[#0f3d22] to-[#0d3320] hero-pattern p-10 flex-col justify-between relative overflow-hidden">
        <div class="absolute -top-16 -left-16 w-64 h-64 bg-white opacity-5 rounded-full"></div>
        <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-white opacity-5 rounded-full"></div>

        <div class="relative z-10">
            <div class="flex flex-col items-center gap-3 mb-10 text-center">
                <div class="relative w-36 h-36 flex-shrink-0">
                    <div class="logo-ring absolute inset-0 rounded-full p-0.5">
                        <div class="w-full h-full bg-white rounded-full"></div>
                    </div>
                    <img src="{{ asset('assets/logo.png') }}" alt="Brgy. Caranas Logo"
                         class="absolute inset-1 w-[calc(100%-8px)] h-[calc(100%-8px)] rounded-full object-cover bg-white" />
                </div>
                <div>
                    <p class="text-white text-xs font-semibold uppercase tracking-widest opacity-75">Official Portal</p>
                    <h1 class="text-white text-xl font-bold leading-tight drop-shadow">Barangay Caranas</h1>
                </div>
            </div>
            <h2 class="text-white text-3xl font-bold leading-tight mb-3">Digital<br/>Barangay<br/>Blotter</h2>
            <p class="text-green-200 text-sm leading-relaxed">A secure, centralized system for managing community incident records in Brgy. Caranas, Motiong, Samar.</p>
        </div>

        <div class="relative z-10 grid grid-cols-2 gap-3">
            <div class="stat-card rounded-xl p-4">
                <p class="text-green-300 text-xs font-medium uppercase tracking-wide mb-1">System</p>
                <p class="text-white text-sm font-semibold">Blotter Records</p>
            </div>
            <div class="stat-card rounded-xl p-4">
                <p class="text-green-300 text-xs font-medium uppercase tracking-wide mb-1">Secure</p>
                <p class="text-white text-sm font-semibold">Access Control</p>
            </div>
            <div class="stat-card rounded-xl p-4 col-span-2">
                <p class="text-green-200 text-xs">Motiong, Samar &mdash; &copy; {{ date('Y') }} All rights reserved.</p>
            </div>
        </div>
    </div>

    <div class="flex-1 flex flex-col justify-center px-10 py-12" style="background-color:#f8faf8;">
        <div class="max-w-sm mx-auto w-full">
            <div class="flex items-center gap-3 mb-8 md:hidden">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-16 h-16 rounded-full object-cover bg-white ring-2 ring-green-400 shadow-md" />
                <div>
                    <p class="font-bold text-gray-800 text-sm">Digital Barangay Blotter</p>
                    <p class="text-gray-400 text-xs">Brgy. Caranas, Motiong, Samar</p>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900">{{ __('auth.welcome') }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ __('auth.subtitle') }}</p>
            </div>

            @if(session('error'))
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if(session('success'))
            <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6 text-sm">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('auth.username') }}</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input type="text" id="username" name="username" required autofocus
                               value="{{ old('username') }}"
                               class="input-field w-full border border-gray-200 bg-gray-50 rounded-xl pl-10 pr-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:bg-white"
                               placeholder="{{ __('auth.username_ph') }}" />
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('auth.password') }}</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input type="password" id="password" name="password" required
                               class="input-field w-full border border-gray-200 bg-gray-50 rounded-xl pl-10 pr-11 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:bg-white"
                               placeholder="{{ __('auth.password_ph') }}" />
                        <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <div class="text-right -mt-2">
                    <a href="{{ route('forgot.password') }}" class="text-xs text-green-700 hover:underline">Forgot password?</a>
                </div>

                <button type="submit"
                        class="btn-signin w-full text-white font-semibold py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 flex items-center justify-center gap-2 mt-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    {{ __('auth.signin') }}
                </button>
            </form>

            <p class="text-center text-gray-400 text-xs mt-8">{{ __('auth.authorized') }} &mdash; Brgy. Caranas, Motiong, Samar</p>

            <div class="flex items-center justify-center gap-1 mt-4 bg-gray-100 rounded-xl p-1 w-36 mx-auto">
                <a href="{{ route('lang.set', 'en') }}"
                   class="flex-1 text-center text-xs font-semibold py-1.5 rounded-lg transition-colors {{ session('lang', 'en') === 'en' ? 'bg-white text-green-800 shadow-sm' : 'text-gray-400 hover:text-gray-700' }}">
                    ENGLISH
                </a>
                <a href="{{ route('lang.set', 'war') }}"
                   class="flex-1 text-center text-xs font-semibold py-1.5 rounded-lg transition-colors {{ session('lang') === 'war' ? 'bg-white text-green-800 shadow-sm' : 'text-gray-400 hover:text-gray-700' }}">
                    WARAY
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    eyeOpen.classList.toggle('hidden', isHidden);
    eyeClosed.classList.toggle('hidden', !isHidden);
}
</script>
</body>
</html>
