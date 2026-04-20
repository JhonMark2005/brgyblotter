@extends('layouts.app')
@php $pageTitle = 'Dashboard'; @endphp

@section('content')
<main id="main-content" class="flex-1 overflow-auto">
    <div class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4 flex items-center justify-between sticky top-0 z-10">
        <div class="flex items-center gap-2">
            <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">{{ __('dash.title') }}</h1>
                <p class="text-sm text-gray-500">{{ date('l, F j, Y') }}</p>
            </div>
        </div>
        <a href="{{ route('incidents.create') }}"
           class="flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white text-sm font-medium px-3 md:px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">{{ __('dash.new_incident') }}</span>
        </a>
    </div>

    <div class="p-4 md:p-6 space-y-6">
        @if(session('success'))
        <div data-flash class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">{{ __('dash.total') }}</p>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ $totalIncidents }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ __('dash.total_sub') }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">{{ __('dash.month') }}</p>
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ $thisMonthCount }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ date('F Y') }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">{{ __('dash.pending') }}</p>
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ $pendingCount }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ __('dash.pending_sub') }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">{{ __('dash.resolved') }}</p>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ $resolvedCount }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ __('dash.resolved_sub') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
            <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-semibold text-gray-800">{{ __('dash.trend') }}</h2>
                        <p class="text-xs text-gray-400">{{ __('dash.trend_sub') }}</p>
                    </div>
                </div>
                <canvas id="monthlyChart" height="110"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="mb-4">
                    <h2 class="text-base font-semibold text-gray-800">{{ __('dash.status') }}</h2>
                    <p class="text-xs text-gray-400">{{ __('dash.status_sub') }}</p>
                </div>
                <canvas id="statusChart" height="160"></canvas>
                <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400 flex-shrink-0"></span><span class="text-gray-600">{{ __('dash.pending') }} <strong>{{ $pendingCount }}</strong></span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-teal-500 flex-shrink-0"></span><span class="text-gray-600">{{ __('dash.investigating') }} <strong>{{ $investigationCount }}</strong></span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green-500 flex-shrink-0"></span><span class="text-gray-600">{{ __('dash.resolved') }} <strong>{{ $resolvedCount }}</strong></span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-gray-400 flex-shrink-0"></span><span class="text-gray-600">{{ __('dash.dismissed') }} <strong>{{ $dismissedCount }}</strong></span></div>
                </div>
            </div>
        </div>

        @if(!empty($countByType))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="mb-4">
                <h2 class="text-base font-semibold text-gray-800">{{ __('dash.types') }}</h2>
                <p class="text-xs text-gray-400">{{ __('dash.types_sub') }}</p>
            </div>
            <div id="typeChartWrap"><canvas id="typeChart"></canvas></div>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">{{ __('dash.recent') }}</h2>
                <a href="{{ route('incidents.index') }}" class="text-sm text-green-700 hover:underline font-medium">{{ __('dash.view_all') }}</a>
            </div>
            <div class="overflow-x-auto">
                @if(empty($recentIncidents))
                <div class="px-6 py-12 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-sm">{{ __('dash.no_incidents') }}</p>
                    <a href="{{ route('incidents.create') }}" class="text-green-700 text-sm hover:underline mt-1 inline-block">{{ __('dash.add_first') }}</a>
                </div>
                @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3">{{ __('dash.case') }}</th>
                            <th class="text-left px-6 py-3">{{ __('dash.complainant') }}</th>
                            <th class="text-left px-6 py-3">{{ __('dash.type') }}</th>
                            <th class="text-left px-6 py-3">{{ __('dash.date') }}</th>
                            <th class="text-left px-6 py-3">{{ __('dash.status_col') }}</th>
                            <th class="text-left px-6 py-3">{{ __('dash.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentIncidents as $inc)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-mono text-gray-600">#{{ str_pad($inc['id'], 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $inc['complainant_name'] }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $inc['incident_type'] }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ date('M j, Y', strtotime($inc['date'])) }}</td>
                            <td class="px-6 py-3">@include('incidents._status_badge', ['inc' => $inc])</td>
                            <td class="px-6 py-3">
                                <a href="{{ route('incidents.view', $inc['id']) }}" class="text-green-700 hover:text-green-800 font-medium text-xs">{{ __('common.view') }}</a>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    Chart.defaults.font.family = 'ui-sans-serif, system-ui, sans-serif';
    Chart.defaults.color = '#6b7280';

    const monthlyLabels = {!! json_encode(array_map(fn($k) => date('M Y', strtotime($k . '-01')), array_keys($countPerMonth))) !!};
    const monthlyData   = {!! json_encode(array_values($countPerMonth)) !!};

    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Incidents',
                data: monthlyData,
                backgroundColor: (ctx) => {
                    const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, ctx.chart.height);
                    gradient.addColorStop(0, 'rgba(22,163,74,0.35)');
                    gradient.addColorStop(1, 'rgba(22,163,74,0.0)');
                    return gradient;
                },
                borderColor: '#16a34a',
                borderWidth: 2.5,
                pointBackgroundColor: '#16a34a',
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.45,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: '#f3f4f6' } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Under Investigation', 'Resolved', 'Dismissed'],
            datasets: [{
                data: [{{ $pendingCount }}, {{ $investigationCount }}, {{ $resolvedCount }}, {{ $dismissedCount }}],
                backgroundColor: ['#facc15', '#14b8a6', '#22c55e', '#9ca3af'],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } }
            }
        }
    });

    @if(!empty($countByType))
    const typeLabels = {!! json_encode(array_keys($countByType)) !!};
    const typeData   = {!! json_encode(array_values($countByType)) !!};
    const typeColors = ['#16a34a','#15803d','#166534','#14532d','#4ade80','#86efac','#bbf7d0','#dcfce7'];

    const typeWrap = document.getElementById('typeChartWrap');
    typeWrap.style.height = Math.max(60, typeData.length * 52) + 'px';

    new Chart(document.getElementById('typeChart'), {
        type: 'bar',
        data: {
            labels: typeLabels,
            datasets: [{
                label: 'Cases',
                data: typeData,
                backgroundColor: typeColors.slice(0, typeData.length),
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: '#f3f4f6' } },
                y: { grid: { display: false } }
            }
        }
    });
    @endif
})();
</script>
@endpush
@endsection
