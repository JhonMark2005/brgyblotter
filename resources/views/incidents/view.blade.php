@extends('layouts.app')
@php $pageTitle = 'Incident #' . str_pad($incident['id'], 4, '0', STR_PAD_LEFT); @endphp

@push('styles')
<style>
@media print {
    #sidebar, #main-content > div:first-child, .no-print { display: none !important; }
    body, main, #main-content { background: white !important; display: block !important; }
    #main-content { padding: 0 !important; }
    @page { size: A4 portrait; margin: 1.5cm 2cm; }
    #print-doc { display: block !important; width: 100%; font-size: 11pt; color: #000; font-family: 'Times New Roman', Times, serif; }
    #print-doc .print-header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10pt; margin-bottom: 14pt; }
    #print-doc .print-header img { width: 60pt; height: 60pt; border-radius: 50%; margin-bottom: 4pt; }
    #print-doc .print-header h1 { font-size: 14pt; font-weight: bold; margin: 0; }
    #print-doc .print-header p  { font-size: 10pt; margin: 2pt 0; }
    #print-doc .print-header h2 { font-size: 12pt; font-weight: bold; margin: 6pt 0 0; letter-spacing: 1pt; text-transform: uppercase; }
    #print-doc .case-row { display: flex; justify-content: space-between; align-items: center; border: 1px solid #999; padding: 6pt 10pt; margin-bottom: 10pt; background: #f5f5f5; }
    #print-doc .case-row .case-num { font-size: 14pt; font-weight: bold; font-family: monospace; }
    #print-doc .case-row .case-status { font-size: 10pt; font-weight: bold; text-transform: uppercase; border: 1px solid #333; padding: 2pt 8pt; }
    #print-doc table.fields { width: 100%; border-collapse: collapse; margin-bottom: 10pt; font-size: 10.5pt; }
    #print-doc table.fields td { border: 1px solid #ccc; padding: 5pt 8pt; vertical-align: top; }
    #print-doc table.fields td.label { font-weight: bold; width: 30%; background: #f5f5f5; color: #333; }
    #print-doc .description-box { border: 1px solid #ccc; padding: 8pt; margin-bottom: 10pt; min-height: 50pt; font-size: 10.5pt; line-height: 1.5; }
    #print-doc .description-label { font-weight: bold; font-size: 10pt; text-transform: uppercase; margin-bottom: 4pt; letter-spacing: 0.5pt; }
    #print-doc .meta-row { display: flex; gap: 0; border: 1px solid #ccc; margin-bottom: 10pt; font-size: 10pt; }
    #print-doc .meta-row .meta-cell { flex: 1; padding: 5pt 8pt; border-right: 1px solid #ccc; }
    #print-doc .meta-row .meta-cell:last-child { border-right: none; }
    #print-doc .meta-row .meta-cell .meta-label { font-weight: bold; font-size: 9pt; text-transform: uppercase; color: #555; }
    #print-doc .sig-row { display: flex; gap: 40pt; margin-top: 28pt; font-size: 10pt; }
    #print-doc .sig-row .sig-cell { flex: 1; text-align: center; }
    #print-doc .sig-row .sig-cell .sig-line { border-bottom: 1px solid #000; margin-bottom: 4pt; height: 28pt; }
    #print-doc .sig-row .sig-cell .sig-name { font-weight: bold; font-size: 10pt; }
    #print-doc .sig-row .sig-cell .sig-title { font-size: 9pt; color: #555; }
    .screen-only { display: none !important; }
}
</style>
@endpush

@section('content')

<main id="main-content" class="flex-1 overflow-auto bg-gray-50">
    <div class="no-print bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4 sticky top-0 z-10 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <div class="flex items-center gap-1.5 text-sm text-gray-500 mb-1">
                    <a href="{{ route('incidents.index') }}" class="hover:text-green-700 transition-colors">Incident Records</a>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-700 font-medium">Case #{{ str_pad($incident['id'], 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <h1 class="text-xl font-semibold text-gray-800">{{ __('view.title') }}</h1>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium px-3 md:px-4 py-2 rounded-xl transition-colors">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span class="hidden sm:inline">{{ __('common.print') }}</span>
            </button>

            <div class="relative" id="notice-menu-wrap">
                <button onclick="document.getElementById('notice-menu').classList.toggle('hidden')"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-3 md:px-4 py-2 rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span class="hidden sm:inline">Download Notice</span>
                </button>
                <div id="notice-menu" class="hidden absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-lg z-20 overflow-hidden">
                    <a href="{{ route('incidents.notice', [$incident['id'], 'new_case']) }}" target="_blank"
                       class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Incident Filed Notice
                    </a>
                    <a href="{{ route('incidents.notice', [$incident['id'], 'status']) }}" target="_blank"
                       class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors border-t border-gray-100">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Status Update Notice
                    </a>
                    @php $hasHearing = !empty($incident['hearing_date']) && $incident['hearing_date'] !== '0000-00-00 00:00:00'; @endphp
                    <a href="{{ route('incidents.notice', [$incident['id'], 'hearing']) }}" target="_blank"
                       class="flex items-center gap-2 px-4 py-3 text-sm {{ $hasHearing ? 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' : 'text-gray-300 cursor-not-allowed' }} transition-colors border-t border-gray-100">
                        <svg class="w-4 h-4 {{ $hasHearing ? 'text-amber-500' : 'text-gray-300' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Hearing Schedule Notice
                        @if(!$hasHearing)<span class="ml-auto text-xs text-gray-400">No date set</span>@endif
                    </a>
                </div>
            </div>

            <a href="{{ route('incidents.edit', $incident['id']) }}"
               class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-3 md:px-4 py-2 rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span class="hidden sm:inline">{{ __('view.edit_record') }}</span>
            </a>
        </div>
    </div>

    <div class="p-4 md:p-6 max-w-3xl mx-auto screen-only">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                            <span class="text-white font-bold text-lg font-mono">#{{ str_pad($incident['id'], 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div>
                            <p class="text-white font-semibold">{{ $incident['incident_type'] }}</p>
                            <p class="text-green-100 text-xs">{{ date('F j, Y', strtotime($incident['date'])) }} &bull; {{ $incident['location'] }}</p>
                        </div>
                    </div>
                    @include('incidents._status_badge', ['inc' => $incident])
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('inc.complainant') }}</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $incident['complainant_name'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('inc.respondent') }}</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $incident['respondent_name'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('view.inc_type') }}</p>
                        <p class="text-sm text-gray-800 font-medium">{{ $incident['incident_type'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('view.inc_date') }}</p>
                        <p class="text-sm text-gray-800 font-medium">{{ date('F j, Y', strtotime($incident['date'])) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('view.location') }}</p>
                        <p class="text-sm text-gray-800 font-medium">{{ $incident['location'] }}</p>
                    </div>
                </div>

                <div class="mb-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Description</p>
                    <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-700 leading-relaxed">
                        {!! nl2br(e($incident['description'])) !!}
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="bg-gray-50 rounded-xl px-4 py-3">
                        <p class="text-xs text-gray-400 mb-0.5">{{ __('view.recorded_by') }}</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $incident['recorded_by_name'] ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-4 py-3">
                        <p class="text-xs text-gray-400 mb-0.5">{{ __('view.date_filed') }}</p>
                        <p class="text-sm font-semibold text-gray-700">{{ date('M j, Y g:i A', strtotime($incident['created_at'])) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-4 py-3">
                        <p class="text-xs text-gray-400 mb-0.5">{{ __('view.last_updated') }}</p>
                        <p class="text-sm font-semibold text-gray-700">{{ date('M j, Y g:i A', strtotime($incident['updated_at'])) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('incidents.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-green-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('common.back') }}
            </a>
        </div>
    </div>

    <div id="print-doc" style="display:none;">
        <div class="print-header">
            <img src="{{ asset('assets/logo.png') }}" alt="Barangay Caranas" style="background:#fff;" />
            <h1>BARANGAY CARANAS</h1>
            <p>Municipality of Motiong, Samar</p>
            <h2>Blotter Incident Record</h2>
        </div>

        <div class="case-row">
            <div>
                <span style="font-size:9pt;font-weight:bold;text-transform:uppercase;color:#555;letter-spacing:0.5pt;">Case Number</span><br>
                <span class="case-num">#{{ str_pad($incident['id'], 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div style="text-align:right;">
                <span class="case-status">{{ strtoupper(str_replace('_', ' ', $incident['status'])) }}</span><br>
                <span style="font-size:9pt;color:#555;">Filed: {{ date('F j, Y', strtotime($incident['created_at'])) }}</span>
            </div>
        </div>

        <table class="fields">
            <tr>
                <td class="label">Complainant</td>
                <td>{{ $incident['complainant_name'] }}</td>
                <td class="label">Respondent</td>
                <td>{{ $incident['respondent_name'] }}</td>
            </tr>
            <tr>
                <td class="label">Incident Type</td>
                <td>{{ $incident['incident_type'] }}</td>
                <td class="label">Date of Incident</td>
                <td>{{ date('F j, Y', strtotime($incident['date'])) }}</td>
            </tr>
            <tr>
                <td class="label">Location</td>
                <td colspan="3">{{ $incident['location'] }}</td>
            </tr>
        </table>

        <div class="description-label">Narrative / Description</div>
        <div class="description-box">{!! nl2br(e($incident['description'])) !!}</div>

        <div class="meta-row">
            <div class="meta-cell">
                <div class="meta-label">Recorded By</div>
                {{ $incident['recorded_by_name'] ?? 'N/A' }}
            </div>
            <div class="meta-cell">
                <div class="meta-label">Date Filed</div>
                {{ date('F j, Y g:i A', strtotime($incident['created_at'])) }}
            </div>
            <div class="meta-cell">
                <div class="meta-label">Last Updated</div>
                {{ date('F j, Y g:i A', strtotime($incident['updated_at'])) }}
            </div>
        </div>

        <div class="sig-row">
            <div class="sig-cell">
                <div class="sig-line"></div>
                <div class="sig-name">Complainant's Signature</div>
                <div class="sig-title">over printed name</div>
            </div>
            <div class="sig-cell">
                <div class="sig-line"></div>
                <div class="sig-name">{{ $incident['recorded_by_name'] ?? 'Barangay Staff' }}</div>
                <div class="sig-title">Barangay Official / Staff on Duty</div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('notice-menu-wrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('notice-menu')?.classList.add('hidden');
    }
});
</script>
@endpush
@endsection
