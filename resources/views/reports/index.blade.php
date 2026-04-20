@extends('layouts.app')
@php $pageTitle = __('rep.title'); @endphp

@section('content')
<main id="main-content" class="flex-1 overflow-auto bg-gray-50">
    <div class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4 sticky top-0 z-10 print:hidden">
        <div class="flex items-center gap-2">
            <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">{{ __('rep.title') }}</h1>
                <p class="text-sm text-gray-500 hidden sm:block">{{ __('rep.subtitle') }}</p>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6 space-y-5 print:p-0">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden print:hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                        </div>
                        <div>
                            <p class="text-white font-semibold text-sm">{{ __('rep.filter_title') }}</p>
                            <p class="text-green-100 text-xs">{{ $isStaff ? __('rep.filter_staff') : __('rep.filter_sub') }}</p>
                        </div>
                    </div>
                    @if($isStaff)
                    <span class="flex items-center gap-1.5 bg-white/20 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ __('rep.my_incidents') }}
                    </span>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('reports.generate') }}">
                @csrf
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('inc.type') }}</label>
                        <select name="incident_type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">{{ __('inc.all_types') }}</option>
                            @foreach($types as $type)
                            <option value="{{ $type }}" @selected(($filters['incident_type'] ?? '') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('inc.status') }}</label>
                        <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">{{ __('inc.all_status') }}</option>
                            @foreach(\App\Models\Incident::STATUSES as $val => $label)
                            <option value="{{ $val }}" @selected(($filters['status'] ?? '') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('inc.date_from') }}</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('inc.date_to') }}</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" />
                    </div>
                </div>
                <input type="hidden" name="page" value="1" id="report-page-input">
                <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('reports.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 rounded-xl transition-colors">{{ __('common.reset') }}</a>
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-green-700 hover:bg-green-800 rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ __('rep.generate') }}
                    </button>
                </div>
            </form>
        </div>

        @if($generated)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden print:shadow-none print:border-none print:rounded-none">

            <div class="px-6 py-5 border-b border-gray-100 print:hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-gray-800">{{ __('rep.results') }}</h2>
                        <p class="text-sm text-gray-500 mt-0.5">
                            @php
                                $statuses = \App\Models\Incident::STATUSES;
                                $parts = [];
                                if (!empty($filters['incident_type'])) $parts[] = 'Type: ' . $filters['incident_type'];
                                if (!empty($filters['status']))        $parts[] = 'Status: ' . ($statuses[$filters['status']] ?? $filters['status']);
                                if (!empty($filters['date_from']))     $parts[] = 'From: ' . date('M j, Y', strtotime($filters['date_from']));
                                if (!empty($filters['date_to']))       $parts[] = 'To: ' . date('M j, Y', strtotime($filters['date_to']));
                            @endphp
                            {{ empty($parts) ? __('rep.all') : implode(' • ', $parts) }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            {{ count($incidents) }} record{{ count($incidents) !== 1 ? 's' : '' }}
                        </span>
                        <button onclick="window.print()" class="flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            {{ __('rep.print') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="hidden print:block text-center px-8 pt-8 pb-6 border-b-2 border-gray-800">
                <img src="{{ asset('assets/logo.png') }}" alt="Barangay Caranas"
                     style="width:70px;height:70px;border-radius:50%;margin:0 auto 8px;display:block;background:#fff;border:2px solid #333;" />
                <p style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#555;margin:0;">Republic of the Philippines</p>
                <p style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#555;margin:0;">Province of Samar — Municipality of Motiong</p>
                <h1 style="font-size:18px;font-weight:900;color:#111;margin:4px 0 2px;">BARANGAY CARANAS</h1>
                <p style="font-size:11px;color:#555;margin:0;">Office of the Barangay Captain</p>
                <div style="margin-top:10px;display:inline-block;background:#111;color:#fff;padding:5px 20px;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">
                    INCIDENT REPORT{{ $isStaff ? ' — MY RECORDS' : '' }}
                </div>
                <p style="font-size:10px;color:#888;margin-top:8px;">
                    Generated: {{ date('F j, Y g:i A') }} &nbsp;&bull;&nbsp; Total Records: {{ count($incidents) }}
                    @if(!empty($parts)) &nbsp;&bull;&nbsp; {{ implode(' • ', $parts) }} @endif
                </p>
            </div>

            <div class="overflow-x-auto">
                @if(empty($incidents))
                <div class="px-6 py-16 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-sm text-gray-400">{{ __('rep.no_results') }}</p>
                </div>
                @else

                <table class="w-full text-sm print:hidden">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-4 py-3">{{ __('dash.case') }}</th>
                            <th class="text-left px-4 py-3">{{ __('dash.date') }}</th>
                            <th class="text-left px-4 py-3">{{ __('inc.complainant') }}</th>
                            <th class="text-left px-4 py-3">{{ __('inc.respondent') }}</th>
                            <th class="text-left px-4 py-3">{{ __('dash.type') }}</th>
                            <th class="text-left px-4 py-3">{{ __('view.location') }}</th>
                            <th class="text-left px-4 py-3">{{ __('dash.status_col') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($incidents as $inc)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-gray-600 text-xs">#{{ str_pad($inc['id'], 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap text-xs">{{ date('M j, Y', strtotime($inc['date'])) }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $inc['complainant_name'] }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $inc['respondent_name'] }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $inc['incident_type'] }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $inc['location'] }}</td>
                            <td class="px-4 py-3">@include('incidents._status_badge', ['inc' => $inc])</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="hidden print:block px-6 py-4">
                    @foreach($incidents as $idx => $inc)
                    @php
                        $incStatus  = strtoupper(str_replace('_', ' ', $inc['status']));
                        $incCaseNo  = '#' . str_pad($inc['id'], 4, '0', STR_PAD_LEFT);
                        $incHearing = (!empty($inc['hearing_date']) && $inc['hearing_date'] !== '0000-00-00 00:00:00')
                            ? date('F j, Y \a\t g:i A', strtotime($inc['hearing_date'])) : null;
                    @endphp
                    <div style="margin-bottom:18px;border:1px solid #bbb;border-radius:4px;overflow:hidden;page-break-inside:avoid;">
                        <div style="background:#1a1a1a;color:#fff;padding:5px 12px;display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:11px;font-weight:700;font-family:monospace;">{{ $incCaseNo }}</span>
                            <span style="font-size:10px;font-weight:600;letter-spacing:1px;text-transform:uppercase;">{{ $incStatus }}</span>
                        </div>
                        <table style="width:100%;border-collapse:collapse;font-size:11px;">
                            <tr>
                                <td style="padding:5px 10px;border:1px solid #ddd;background:#f5f5f5;font-weight:700;color:#555;text-transform:uppercase;font-size:9px;letter-spacing:1px;width:18%;">Date</td>
                                <td style="padding:5px 10px;border:1px solid #ddd;width:32%;">{{ date('F j, Y', strtotime($inc['date'])) }}</td>
                                <td style="padding:5px 10px;border:1px solid #ddd;background:#f5f5f5;font-weight:700;color:#555;text-transform:uppercase;font-size:9px;letter-spacing:1px;width:18%;">Incident Type</td>
                                <td style="padding:5px 10px;border:1px solid #ddd;width:32%;">{{ $inc['incident_type'] }}</td>
                            </tr>
                            <tr>
                                <td style="padding:5px 10px;border:1px solid #ddd;background:#f5f5f5;font-weight:700;color:#555;text-transform:uppercase;font-size:9px;letter-spacing:1px;">Complainant</td>
                                <td style="padding:5px 10px;border:1px solid #ddd;">{{ $inc['complainant_name'] }}{{ !empty($inc['complainant_email']) ? ' (' . $inc['complainant_email'] . ')' : '' }}</td>
                                <td style="padding:5px 10px;border:1px solid #ddd;background:#f5f5f5;font-weight:700;color:#555;text-transform:uppercase;font-size:9px;letter-spacing:1px;">Respondent</td>
                                <td style="padding:5px 10px;border:1px solid #ddd;">{{ $inc['respondent_name'] }}{{ !empty($inc['respondent_email']) ? ' (' . $inc['respondent_email'] . ')' : '' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:5px 10px;border:1px solid #ddd;background:#f5f5f5;font-weight:700;color:#555;text-transform:uppercase;font-size:9px;letter-spacing:1px;">Location</td>
                                <td style="padding:5px 10px;border:1px solid #ddd;" colspan="3">{{ $inc['location'] }}</td>
                            </tr>
                            @if($incHearing)
                            <tr>
                                <td style="padding:5px 10px;border:1px solid #ddd;background:#f5f5f5;font-weight:700;color:#555;text-transform:uppercase;font-size:9px;letter-spacing:1px;">Hearing Date</td>
                                <td style="padding:5px 10px;border:1px solid #ddd;" colspan="3">{{ $incHearing }}{{ !empty($inc['hearing_notes']) ? ' — ' . $inc['hearing_notes'] : '' }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="padding:5px 10px;border:1px solid #ddd;background:#f5f5f5;font-weight:700;color:#555;text-transform:uppercase;font-size:9px;letter-spacing:1px;vertical-align:top;">Description</td>
                                <td style="padding:5px 10px;border:1px solid #ddd;line-height:1.5;" colspan="3">{!! nl2br(e($inc['description'])) !!}</td>
                            </tr>
                            <tr>
                                <td style="padding:5px 10px;border:1px solid #ddd;background:#f5f5f5;font-weight:700;color:#555;text-transform:uppercase;font-size:9px;letter-spacing:1px;">Recorded By</td>
                                <td style="padding:5px 10px;border:1px solid #ddd;" colspan="3">{{ $inc['recorded_by_name'] ?? 'Unknown' }}</td>
                            </tr>
                        </table>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            @if($pages > 1)
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/50 print:hidden">
                <p class="text-xs text-gray-500">Page <span class="font-semibold text-gray-700">{{ $page }}</span> of <span class="font-semibold text-gray-700">{{ $pages }}</span> &mdash; {{ $total }} total</p>
                <div class="flex gap-1 items-center">
                    @php
                        $btnBase     = 'w-8 h-8 flex items-center justify-center rounded-lg text-xs font-semibold transition-colors';
                        $btnNormal   = 'bg-white border border-gray-200 text-gray-600 hover:bg-green-50 cursor-pointer';
                        $btnActive   = 'bg-green-700 text-white shadow-sm';
                        $btnDisabled = 'bg-white border border-gray-100 text-gray-300 cursor-not-allowed';
                    @endphp
                    @if($page > 1)
                        <button type="button" onclick="goReportPage({{ $page - 1 }})" class="{{ $btnBase }} {{ $btnNormal }}">&lsaquo;</button>
                    @else
                        <span class="{{ $btnBase }} {{ $btnDisabled }}">&lsaquo;</span>
                    @endif

                    @php $range = 2; $shown = []; $prev = null;
                    for ($i = 1; $i <= $pages; $i++) {
                        if ($i === 1 || $i === $pages || ($i >= $page - $range && $i <= $page + $range)) $shown[] = $i;
                    } @endphp
                    @foreach($shown as $i)
                        @if($prev !== null && $i - $prev > 1)<span class="px-1 text-gray-400 text-xs">…</span>@endif
                        <button type="button" onclick="goReportPage({{ $i }})" class="{{ $btnBase }} {{ $i === $page ? $btnActive : $btnNormal }}">{{ $i }}</button>
                        @php $prev = $i; @endphp
                    @endforeach

                    @if($page < $pages)
                        <button type="button" onclick="goReportPage({{ $page + 1 }})" class="{{ $btnBase }} {{ $btnNormal }}">&rsaquo;</button>
                    @else
                        <span class="{{ $btnBase }} {{ $btnDisabled }}">&rsaquo;</span>
                    @endif
                </div>
            </div>
            @endif

            <div class="hidden print:block px-8 py-8 mt-2">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;text-align:center;font-size:12px;margin-top:24px;">
                    <div>
                        <div style="border-bottom:1px solid #333;margin-bottom:4px;height:40px;"></div>
                        <p style="font-weight:700;margin:0;">{{ session('user')['full_name'] ?? 'Barangay Staff' }}</p>
                        <p style="font-size:10px;color:#666;margin:2px 0 0;">Barangay Staff on Duty</p>
                    </div>
                    <div>
                        <div style="border-bottom:1px solid #333;margin-bottom:4px;height:40px;"></div>
                        <p style="font-weight:700;margin:0;">Barangay Captain</p>
                        <p style="font-size:10px;color:#666;margin:2px 0 0;">Brgy. Caranas, Motiong, Samar</p>
                    </div>
                </div>
                <p style="font-size:9px;color:#aaa;text-align:center;margin-top:24px;border-top:1px solid #eee;padding-top:10px;">
                    Generated by Digital Barangay Blotter System &mdash; Brgy. Caranas, Motiong, Samar &mdash; {{ date('F j, Y g:i A') }}
                </p>
            </div>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-16 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <p class="text-gray-700 font-semibold">{{ __('rep.empty_title') }}</p>
            <p class="text-sm text-gray-400 mt-1">{{ __('rep.empty_sub') }}</p>
        </div>
        @endif
    </div>
</main>

@push('scripts')
<script>
function goReportPage(p) {
    document.getElementById('report-page-input').value = p;
    document.getElementById('report-page-input').closest('form').submit();
}
</script>
@endpush
@endsection
