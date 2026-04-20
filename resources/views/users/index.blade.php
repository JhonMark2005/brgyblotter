@extends('layouts.app')
@php $pageTitle = 'Manage Users'; @endphp

@section('content')
<main id="main-content" class="flex-1 overflow-auto bg-gray-50">
    <div class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4 flex items-center justify-between sticky top-0 z-10">
        <div class="flex items-center gap-2">
            <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">{{ __('user.manage') }}</h1>
                <p class="text-sm text-gray-500 hidden sm:block">System user account management</p>
            </div>
        </div>
        <a href="{{ route('users.create') }}"
           class="flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white text-sm font-semibold px-3 md:px-4 py-2 md:py-2.5 rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">{{ __('user.add') }}</span>
        </a>
    </div>

    <div class="p-4 md:p-6 space-y-5">
        @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-white font-semibold text-sm">User Accounts</p>
                            <p class="text-green-100 text-xs">All registered system users</p>
                        </div>
                    </div>
                    <span class="bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full">
                        {{ count($users) }} user{{ count($users) !== 1 ? 's' : '' }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                @if(empty($users))
                <div class="px-6 py-12 text-center text-gray-400 text-sm">No users found.</div>
                @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3.5">User</th>
                            <th class="text-left px-6 py-3.5">Username</th>
                            <th class="text-left px-6 py-3.5">Role</th>
                            <th class="text-left px-6 py-3.5">Date Created</th>
                            <th class="text-center px-6 py-3.5">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $u)
                        @php $isSelf = (int) $u['id'] === (int) (session('user')['id'] ?? 0); @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0 {{ $u['role'] === 'admin' ? 'bg-purple-500' : 'bg-green-600' }}">
                                        {{ strtoupper(substr($u['full_name'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $u['full_name'] }}</p>
                                        @if($isSelf)<span class="text-xs text-green-600 font-medium">You</span>@endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-gray-600 text-sm">{{ $u['username'] }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $u['role'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $u['role'] === 'admin' ? 'Administrator' : 'Staff' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ !empty($u['created_at']) ? date('M j, Y', strtotime($u['created_at'])) : '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('users.edit', $u['id']) }}"
                                       class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600 hover:text-white bg-amber-50 hover:bg-amber-500 px-2.5 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    @if(!$isSelf)
                                    <form method="POST" action="{{ route('users.delete', $u['id']) }}"
                                          onsubmit="return confirmDelete('user {{ $u['username'] }}')"
                                          class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold text-red-500 hover:text-white bg-red-50 hover:bg-red-500 px-2.5 py-1.5 rounded-lg transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Delete
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-xs text-gray-300 px-2.5 py-1.5">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
