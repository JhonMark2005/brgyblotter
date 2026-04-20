<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password — Digital Barangay Blotter</title>
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
        <h1 class="text-white font-bold text-lg">Reset Password</h1>
        <p class="text-green-200 text-xs mt-1">Digital Barangay Blotter — Brgy. Caranas</p>
    </div>

    <div class="px-8 py-8">
        @if(!$valid)
        <div class="text-center py-4">
            <svg class="w-14 h-14 mx-auto mb-3 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <p class="text-gray-700 font-semibold">Invalid or Expired Link</p>
            <p class="text-sm text-gray-400 mt-1">This reset link has already been used or has expired.</p>
            <a href="{{ route('forgot.password') }}" class="inline-block mt-5 bg-green-700 hover:bg-green-800 text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition-colors">
                Request a New Link
            </a>
        </div>
        @else

        @if(session('error'))
        <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif

        <p class="text-gray-500 text-sm mb-6">Enter your new password below. Must be at least 6 characters.</p>

        <form method="POST" action="{{ route('reset.password') }}" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}" />

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                <input type="password" id="password" name="password" required autofocus minlength="6"
                       class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/20 transition"
                       placeholder="At least 6 characters" />
            </div>
            <div class="mb-6">
                <label for="confirm" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                <input type="password" id="confirm" name="confirm" required minlength="6"
                       class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/20 transition"
                       placeholder="Repeat new password" />
            </div>
            <button type="submit"
                    class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-3 rounded-xl text-sm transition-colors shadow-sm">
                Reset Password
            </button>
        </form>
        @endif

        <p class="text-center text-sm text-gray-400 mt-6">
            <a href="{{ route('login') }}" class="text-green-700 hover:underline font-medium">Back to Login</a>
        </p>
    </div>
</div>

</body>
</html>
