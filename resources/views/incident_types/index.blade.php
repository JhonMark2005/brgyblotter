@extends('layouts.app')
@php $pageTitle = __('itype.manage'); @endphp

@section('content')
<main id="main-content" class="flex-1 overflow-auto bg-gray-50">
    <div class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4 sticky top-0 z-10">
        <div class="flex items-center gap-2">
            <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <div class="flex items-center gap-1.5 text-sm text-gray-500 mb-1">
                    <span class="text-gray-700 font-medium">{{ __('nav.admin') }}</span>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-700 font-medium">{{ __('itype.manage') }}</span>
                </div>
                <h1 class="text-xl font-semibold text-gray-800">{{ __('itype.manage') }}</h1>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6 max-w-2xl mx-auto space-y-5">
        @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">{{ __('itype.add') }}</p>
                        <p class="text-green-100 text-xs">{{ __('itype.add_sub') }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('incident-types.store') }}" novalidate>
                @csrf
                <div class="p-5 flex gap-3">
                    <input type="text" name="type_name" required maxlength="100"
                           class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                           placeholder="{{ __('itype.type_ph') }}" />
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-green-700 hover:bg-green-800 rounded-xl transition-colors shadow-sm whitespace-nowrap">
                        {{ __('itype.add_btn') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-700">{{ __('itype.current') }}</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ str_replace(':count', count($types), __('itype.current_sub')) }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                    {{ count($types) }} {{ __('itype.types') }}
                </span>
            </div>

            @if(empty($types))
            <div class="px-6 py-12 text-center text-sm text-gray-400">{{ __('itype.empty') }}</div>
            @else
            <ul class="divide-y divide-gray-50">
                @foreach($types as $type)
                <li class="flex items-center justify-between px-6 py-3.5 hover:bg-gray-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-green-400 flex-shrink-0"></div>
                        <span class="text-sm text-gray-700 font-medium">{{ $type['type_name'] }}</span>
                    </div>
                    <form method="POST" action="{{ route('incident-types.destroy', $type['id']) }}"
                          onsubmit="return confirmDelete('type &quot;{{ $type['type_name'] }}&quot;')">
                        @csrf
                        <button type="submit" class="opacity-0 group-hover:opacity-100 transition-opacity px-3 py-1.5 text-xs font-semibold text-red-500 hover:text-white bg-transparent hover:bg-red-500 border border-transparent hover:border-red-500 rounded-lg transition-colors">
                            {{ __('common.delete') }}
                        </button>
                    </form>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        <p class="text-xs text-gray-400 text-center px-4">{{ __('itype.note') }}</p>
    </div>
</main>
@endsection
