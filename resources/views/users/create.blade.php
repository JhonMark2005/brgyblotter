@extends('layouts.app')
@php $pageTitle = 'Add User'; @endphp

@section('content')
<main id="main-content" class="flex-1 overflow-auto bg-gray-50">
    <div class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4 sticky top-0 z-10">
        <div class="flex items-center gap-2">
            <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <div class="flex items-center gap-1.5 text-sm text-gray-500 mb-1">
                    <a href="{{ route('users.index') }}" class="hover:text-green-700 transition-colors">{{ __('user.manage') }}</a>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-700 font-medium">{{ __('user.add') }}</span>
                </div>
                <h1 class="text-xl font-semibold text-gray-800">{{ __('user.add_new') }}</h1>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6 max-w-lg mx-auto">
        @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-base">{{ __('user.new_account') }}</p>
                        <p class="text-green-100 text-xs">{{ __('user.new_sub') }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('users.store') }}" novalidate>
                @csrf
                <div class="p-6 space-y-5">
                    <div>
                        <label for="full_name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('user.full_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="full_name" name="full_name" required
                               value="{{ $old['full_name'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="{{ __('user.full_name_ph') }}" />
                    </div>

                    <div>
                        <label for="username" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('user.username') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="username" name="username" required
                               value="{{ $old['username'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="{{ __('user.username_ph') }}" autocomplete="off" />
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                            Email Address <span class="text-gray-300 font-normal normal-case">(for notifications &amp; password reset)</span>
                        </label>
                        <input type="email" id="email" name="email" maxlength="150"
                               value="{{ $old['email'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="staff@example.com" />
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('user.password') }} <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" required minlength="6"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="{{ __('user.password_ph') }}" autocomplete="new-password" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">{{ __('user.role') }} <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="role" value="staff" class="peer sr-only" @checked(($old['role'] ?? 'staff') === 'staff')>
                                <div class="border-2 border-gray-200 rounded-xl p-3 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-50">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <p class="text-sm font-semibold text-gray-700 peer-checked:text-green-700">{{ __('user.staff') }}</p>
                                    <p class="text-xs text-gray-400">{{ __('user.staff_sub') }}</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="role" value="admin" class="peer sr-only" @checked(($old['role'] ?? '') === 'admin')>
                                <div class="border-2 border-gray-200 rounded-xl p-3 text-center transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <p class="text-sm font-semibold text-gray-700 peer-checked:text-purple-700">{{ __('user.admin') }}</p>
                                    <p class="text-xs text-gray-400">{{ __('user.admin_sub') }}</p>
                                </div>
                            </label>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">{{ __('user.role_note') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('users.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 rounded-xl transition-colors">{{ __('common.cancel') }}</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-green-700 hover:bg-green-800 rounded-xl transition-colors shadow-sm">{{ __('user.create') }}</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
