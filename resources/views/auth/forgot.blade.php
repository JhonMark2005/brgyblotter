<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password — Digital Barangay Blotter</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #e8ede9; background-image: radial-gradient(circle at 15% 85%, rgba(21,128,61,0.08) 0%, transparent 45%), radial-gradient(circle at 85% 15%, rgba(21,128,61,0.06) 0%, transparent 40%); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden">
    <div class="bg-gradient-to-r from-[#0a2e18] to-[#0f3d22] px-8 py-8 text-center">
        <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-16 h-16 rounded-full object-cover bg-white mx-auto mb-3 ring-2 ring-white/30" />
        <h1 class="text-white font-bold text-lg">Forgot Password</h1>
        <p class="text-green-200 text-xs mt-1">Digital Barangay Blotter — Brgy. Caranas</p>
    </div>

    <div class="px-8 py-8">
        @if(session('error'))
        <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <p class="text-gray-500 text-sm mb-6">Enter your username and email address. Both must match your account before a reset link is sent.</p>

        <form method="POST" action="{{ route('forgot.password') }}" novalidate>
            @csrf
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                <input type="text" id="username" name="username" required autofocus
                       class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/20 transition"
                       placeholder="Your username" />
            </div>
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                <input type="email" id="email" name="email" required
                       class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/20 transition"
                       placeholder="your@email.com" />
            </div>
            <button type="submit"
                    class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-3 rounded-xl text-sm transition-colors shadow-sm">
                Send Reset Link
            </button>
        </form>

        <p class="text-center text-sm text-gray-400 mt-6">
            <a href="{{ route('login') }}" class="text-green-700 hover:underline font-medium">Back to Login</a>
        </p>
    </div>
</div>

</body>
</html>
