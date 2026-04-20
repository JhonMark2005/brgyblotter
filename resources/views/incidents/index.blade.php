@extends('layouts.app')
@php $pageTitle = 'Incident Records'; @endphp

@section('content')
<main id="main-content" class="flex-1 overflow-auto bg-gray-50">
    <div class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4 flex items-center justify-between sticky top-0 z-10">
        <div class="flex items-center gap-2">
            <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">{{ __('inc.title') }}</h1>
                <p class="text-sm text-gray-500 hidden sm:block">{{ __('inc.subtitle') }}</p>
            </div>
        </div>
        <a href="{{ route('incidents.create') }}"
           class="flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white text-sm font-semibold px-3 md:px-4 py-2 md:py-2.5 rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">{{ __('inc.add') }}</span>
        </a>
    </div>

    <div class="p-4 md:p-6 space-y-5">
        @if(session('success'))
        <div data-flash class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div data-flash class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">{{ __('inc.filter_title') }}</p>
                        <p class="text-green-100 text-xs">{{ __('inc.filter_sub') }}</p>
                    </div>
                </div>
            </div>
            <form method="GET" action="{{ route('incidents.index') }}">
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div class="xl:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('common.search') }}</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ $filters['search'] }}"
                                   placeholder="{{ __('inc.search_ph') }}"
                                   class="w-full border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('inc.status') }}</label>
                        <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">{{ __('inc.all_status') }}</option>
                            @foreach(\App\Models\Incident::STATUSES as $val => $label)
                            <option value="{{ $val }}" @selected($filters['status'] === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('inc.type') }}</label>
                        <select name="incident_type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">{{ __('inc.all_types') }}</option>
                            @foreach($types as $type)
                            <option value="{{ $type }}" @selected($filters['incident_type'] === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('inc.date_from') }}</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('inc.date_to') }}</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-5 py-4 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('incidents.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 rounded-xl transition-colors">{{ __('common.clear') }}</a>
                    <button type="submit" class="flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-green-700 hover:bg-green-800 rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        {{ __('common.search') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">{{ __('inc.results') }}</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                    {{ $total }} record{{ $total !== 1 ? 's' : '' }}
                </span>
            </div>

            <div class="overflow-x-auto">
                @if(empty($incidents))
                <div class="px-6 py-16 text-center">
                    <svg class="w-14 h-14 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <p class="text-gray-500 font-medium">{{ __('inc.no_records') }}</p>
                    <p class="text-sm text-gray-400 mt-1">{{ __('inc.no_records_sub') }}</p>
                </div>
                @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3.5">{{ __('dash.case') }}</th>
                            <th class="text-left px-6 py-3.5">{{ __('inc.complainant') }}</th>
                            <th class="text-left px-6 py-3.5">{{ __('inc.respondent') }}</th>
                            <th class="text-left px-6 py-3.5">{{ __('dash.type') }}</th>
                            <th class="text-left px-6 py-3.5">{{ __('dash.date') }}</th>
                            <th class="text-left px-6 py-3.5">{{ __('dash.status_col') }}</th>
                            <th class="text-left px-6 py-3.5">{{ __('inc.recorded_by') }}</th>
                            <th class="text-center px-6 py-3.5">{{ __('inc.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($incidents as $inc)
                        <tr class="hover:bg-green-50/40 transition-colors group">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-green-700">#{{ str_pad($inc['id'], 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $inc['complainant_name'] }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $inc['respondent_name'] }}</td>
                            <td class="px-6 py-4 text-gray-600 text-xs">{{ $inc['incident_type'] }}</td>
                            <td class="px-6 py-4 text-gray-500 text-xs whitespace-nowrap">{{ date('M j, Y', strtotime($inc['date'])) }}</td>
                            <td class="px-6 py-4">@include('incidents._status_badge', ['inc' => $inc])</td>
                            <td class="px-6 py-4 text-gray-400 text-xs">{{ $inc['recorded_by_name'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('incidents.view', $inc['id']) }}"
                                       class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 hover:text-white bg-green-50 hover:bg-green-700 px-2.5 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        {{ __('common.view') }}
                                    </a>
                                    <a href="{{ route('incidents.edit', $inc['id']) }}"
                                       class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600 hover:text-white bg-amber-50 hover:bg-amber-500 px-2.5 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        {{ __('common.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('incidents.delete', $inc['id']) }}"
                                          onsubmit="return confirmDelete('incident record #{{ str_pad($inc['id'], 4, '0', STR_PAD_LEFT) }}')"
                                          class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 text-xs font-semibold text-red-500 hover:text-white bg-red-50 hover:bg-red-500 px-2.5 py-1.5 rounded-lg transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            {{ __('common.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

            @if($pages > 1)
            @php
                $qp = array_filter($filters);
                $btnBase = 'w-8 h-8 flex items-center justify-center rounded-lg text-xs font-semibold transition-colors';
                $btnActive = 'bg-green-700 text-white shadow-sm';
                $btnNormal = 'bg-white border border-gray-200 text-gray-600 hover:bg-green-50';
                $btnDisabled = 'bg-white border border-gray-100 text-gray-300 cursor-not-allowed';
            @endphp
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/50">
                <p class="text-xs text-gray-500">Page <span class="font-semibold text-gray-700">{{ $page }}</span> of <span class="font-semibold text-gray-700">{{ $pages }}</span> &mdash; {{ $total }} total</p>
                <div class="flex gap-1 items-center">
                    @if($page > 1)
                        @php $qp['page'] = $page - 1; @endphp
                        <a href="{{ route('incidents.index', $qp) }}" class="{{ $btnBase }} {{ $btnNormal }}">&lsaquo;</a>
                    @else
                        <span class="{{ $btnBase }} {{ $btnDisabled }}">&lsaquo;</span>
                    @endif

                    @php
                        $range = 2; $shown = []; $prev = null;
                        for ($i = 1; $i <= $pages; $i++) {
                            if ($i === 1 || $i === $pages || ($i >= $page - $range && $i <= $page + $range)) $shown[] = $i;
                        }
                    @endphp
                    @foreach($shown as $i)
                        @if($prev !== null && $i - $prev > 1)
                            <span class="px-1 text-gray-400 text-xs">…</span>
                        @endif
                        @php $qp['page'] = $i; @endphp
                        <a href="{{ route('incidents.index', $qp) }}" class="{{ $btnBase }} {{ $i === $page ? $btnActive : $btnNormal }}">{{ $i }}</a>
                        @php $prev = $i; @endphp
                    @endforeach

                    @if($page < $pages)
                        @php $qp['page'] = $page + 1; @endphp
                        <a href="{{ route('incidents.index', $qp) }}" class="{{ $btnBase }} {{ $btnNormal }}">&rsaquo;</a>
                    @else
                        <span class="{{ $btnBase }} {{ $btnDisabled }}">&rsaquo;</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</main>
@endsection
