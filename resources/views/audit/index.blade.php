@extends('layouts.app')
@php $pageTitle = 'Audit Log'; @endphp

@section('content')
<main id="main-content" class="flex-1 overflow-auto bg-gray-50">
    <div class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4 sticky top-0 z-10">
        <div class="flex items-center gap-2">
            <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Audit Log</h1>
                <p class="text-sm text-gray-500 hidden sm:block">Track all system actions — who did what and when</p>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6 space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">Filter Logs</p>
                        <p class="text-gray-200 text-xs">Narrow down by action, type, or user</p>
                    </div>
                </div>
            </div>
            <form method="GET" action="{{ route('audit.index') }}">
                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Action</label>
                        <select name="action" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent transition">
                            <option value="">All Actions</option>
                            <option value="created" @selected(($filters['action'] ?? '') === 'created')>Created</option>
                            <option value="updated" @selected(($filters['action'] ?? '') === 'updated')>Updated</option>
                            <option value="deleted" @selected(($filters['action'] ?? '') === 'deleted')>Deleted</option>
                            <option value="generated" @selected(($filters['action'] ?? '') === 'generated')>Generated</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Type</label>
                        <select name="entity_type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent transition">
                            <option value="">All Types</option>
                            <option value="incident" @selected(($filters['entity_type'] ?? '') === 'incident')>Incident</option>
                            <option value="user" @selected(($filters['entity_type'] ?? '') === 'user')>User</option>
                            <option value="incident_type" @selected(($filters['entity_type'] ?? '') === 'incident_type')>Incident Type</option>
                            <option value="report" @selected(($filters['entity_type'] ?? '') === 'report')>Report</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">User</label>
                        <select name="user_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent transition">
                            <option value="">All Users</option>
                            @foreach($users as $u)
                            <option value="{{ $u['user_id'] }}" @selected((int)($filters['user_id'] ?? 0) === (int)$u['user_id'])>{{ $u['user_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-5 py-4 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('audit.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 rounded-xl transition-colors">Clear</a>
                    <button type="submit" class="flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-green-700 hover:bg-green-800 rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">Activity Log</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                    {{ $total }} entr{{ $total !== 1 ? 'ies' : 'y' }}
                </span>
            </div>

            @if(empty($logs))
            <div class="px-6 py-16 text-center">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-gray-500 font-medium">No log entries found</p>
                <p class="text-sm text-gray-400 mt-1">Actions will appear here once users interact with the system</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3.5">Date & Time</th>
                            <th class="text-left px-6 py-3.5">User</th>
                            <th class="text-left px-6 py-3.5">Action</th>
                            <th class="text-left px-6 py-3.5">Type</th>
                            <th class="text-left px-6 py-3.5">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($logs as $log)
                        @php
                            $actionColors = ['created' => 'bg-green-100 text-green-700', 'updated' => 'bg-blue-100 text-blue-700', 'deleted' => 'bg-red-100 text-red-700'];
                            $typeColors   = ['incident' => 'bg-purple-100 text-purple-700', 'user' => 'bg-amber-100 text-amber-700', 'incident_type' => 'bg-teal-100 text-teal-700'];
                            $actionClass  = $actionColors[$log['action']] ?? 'bg-gray-100 text-gray-600';
                            $typeClass    = $typeColors[$log['entity_type']] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-6 py-3.5 text-gray-500 text-xs whitespace-nowrap">
                                {{ date('M j, Y', strtotime($log['created_at'])) }}
                                <span class="block text-gray-400">{{ date('g:i A', strtotime($log['created_at'])) }}</span>
                            </td>
                            <td class="px-6 py-3.5 font-medium text-gray-800">{{ $log['user_name'] }}</td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold capitalize {{ $actionClass }}">{{ $log['action'] }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $typeClass }}">
                                    {{ str_replace('_', ' ', $log['entity_type']) }}
                                    @if($log['entity_id'])<span class="ml-1 font-mono">#{{ str_pad($log['entity_id'], 4, '0', STR_PAD_LEFT) }}</span>@endif
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-gray-600 text-xs max-w-xs truncate">{{ $log['description'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($pages > 1)
            @php
                $qp      = array_filter($filters);
                $btnBase = 'w-8 h-8 flex items-center justify-center rounded-lg text-xs font-semibold transition-colors';
                $btnActive   = 'bg-green-700 text-white shadow-sm';
                $btnNormal   = 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50';
                $btnDisabled = 'bg-white border border-gray-100 text-gray-300 cursor-not-allowed';
            @endphp
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/50">
                <p class="text-xs text-gray-500">Page <span class="font-semibold text-gray-700">{{ $page }}</span> of <span class="font-semibold text-gray-700">{{ $pages }}</span></p>
                <div class="flex gap-1 items-center">
                    @if($page > 1)
                        @php $qp['page'] = $page - 1; @endphp
                        <a href="{{ route('audit.index', $qp) }}" class="{{ $btnBase }} {{ $btnNormal }}">&lsaquo;</a>
                    @else
                        <span class="{{ $btnBase }} {{ $btnDisabled }}">&lsaquo;</span>
                    @endif

                    @php $range = 2; $shown = []; $prev = null;
                    for ($i = 1; $i <= $pages; $i++) {
                        if ($i === 1 || $i === $pages || ($i >= $page - $range && $i <= $page + $range)) $shown[] = $i;
                    } @endphp
                    @foreach($shown as $i)
                        @if($prev !== null && $i - $prev > 1)<span class="px-1 text-gray-400 text-xs">…</span>@endif
                        @php $qp['page'] = $i; $cls = $i === $page ? $btnActive : $btnNormal; @endphp
                        <a href="{{ route('audit.index', $qp) }}" class="{{ $btnBase }} {{ $cls }}">{{ $i }}</a>
                        @php $prev = $i; @endphp
                    @endforeach

                    @if($page < $pages)
                        @php $qp['page'] = $page + 1; @endphp
                        <a href="{{ route('audit.index', $qp) }}" class="{{ $btnBase }} {{ $btnNormal }}">&rsaquo;</a>
                    @else
                        <span class="{{ $btnBase }} {{ $btnDisabled }}">&rsaquo;</span>
                    @endif
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</main>
@endsection
