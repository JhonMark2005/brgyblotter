@php
    $statusLabel = strtoupper(str_replace('_', ' ', $incident['status']));
    $hearingDate = (!empty($incident['hearing_date']) && $incident['hearing_date'] !== '0000-00-00 00:00:00')
        ? date('l, F j, Y \a\t g:i A', strtotime($incident['hearing_date']))
        : null;
    $noticeTitle = ['new_case' => 'Notice of Blotter Incident Filed', 'hearing' => 'Notice of Hearing Schedule', 'status' => 'Notice of Case Status Update'][$type] ?? 'Blotter Notice';
    $parties = [
        ['role' => 'Complainant', 'name' => $incident['complainant_name']],
        ['role' => 'Respondent',  'name' => $incident['respondent_name']],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $noticeTitle }} — #{{ str_pad($incident['id'], 4, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Times New Roman', Times, serif; }
        .page-break { page-break-after: always; break-after: page; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .notice-card { box-shadow: none !important; border: none !important; border-radius: 0 !important; }
            .page-break { margin: 0; padding: 0; }
            .print-wrapper { margin: 0 !important; padding: 0 !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="no-print bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between sticky top-0 z-10 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('incidents.view', $incident['id']) }}"
               class="text-sm text-gray-500 hover:text-green-700 flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Incident
            </a>
            <span class="text-gray-300">|</span>
            <span class="text-sm font-medium text-gray-700">
                {{ $noticeTitle }}
                @if(count($parties) > 1)
                <span class="text-gray-400 text-xs">({{ count($parties) }} copies)</span>
                @endif
            </span>
        </div>
        <button onclick="window.print()"
                class="flex items-center gap-2 bg-green-700 hover:bg-green-800 text-white text-sm font-semibold px-5 py-2 rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print / Save as PDF
        </button>
    </div>

    <div class="print-wrapper max-w-3xl mx-auto my-8 px-4 pb-12 space-y-10">
        @foreach($parties as $i => $party)
            @if($i > 0)
            <div class="no-print flex items-center gap-4 my-2">
                <div class="flex-1 border-t-2 border-dashed border-gray-300"></div>
                <span class="text-xs text-gray-400 uppercase tracking-widest">Page Break — Next Copy</span>
                <div class="flex-1 border-t-2 border-dashed border-gray-300"></div>
            </div>
            <div class="page-break"></div>
            @endif

            <div class="notice-card bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">
                <div class="text-center px-10 pt-10 pb-6 border-b-2 border-gray-800">
                    <img src="{{ asset('assets/logo.png') }}" alt="Barangay Caranas"
                         style="width:80px;height:80px;border-radius:50%;margin:0 auto 10px;display:block;background:#fff;" />
                    <p class="text-xs tracking-widest uppercase text-gray-500">Republic of the Philippines</p>
                    <p class="text-xs tracking-widest uppercase text-gray-500">Province of Samar — Municipality of Motiong</p>
                    <h1 class="text-xl font-bold text-gray-900 mt-1">BARANGAY CARANAS</h1>
                    <p class="text-sm text-gray-600">Office of the Barangay Captain</p>
                    <div class="mt-4 inline-block bg-gray-900 text-white px-6 py-1.5 text-sm font-bold tracking-widest uppercase">
                        {{ $noticeTitle }}
                    </div>
                </div>

                <div class="bg-gray-50 border-b border-gray-200 px-10 py-2 text-xs font-bold uppercase tracking-widest text-gray-500 text-center">
                    Copy for: {{ $party['role'] }}
                </div>

                <div class="px-10 py-8 text-gray-800 text-sm leading-relaxed">
                    <div class="flex justify-between mb-6 text-sm">
                        <div><span class="text-gray-500">Date:</span> <strong>{{ date('F j, Y') }}</strong></div>
                        <div><span class="text-gray-500">Case No.:</span> <strong class="font-mono">#{{ str_pad($incident['id'], 4, '0', STR_PAD_LEFT) }}</strong></div>
                    </div>

                    <p class="mb-1"><span class="text-gray-500">To:</span> <strong>{{ $party['name'] }}</strong> <span class="text-gray-400 text-xs">({{ $party['role'] }})</span></p>
                    <p class="mb-6"><span class="text-gray-500">Subject:</span> <strong>{{ $noticeTitle }}</strong></p>

                    @if($type === 'new_case')
                    <p class="mb-4">This is to inform you that a blotter incident has been officially filed and recorded under Case No. <strong>#{{ str_pad($incident['id'], 4, '0', STR_PAD_LEFT) }}</strong> at the Barangay Hall of Caranas, Motiong, Samar. The details of the incident are as follows:</p>

                    @elseif($type === 'hearing')
                    <p class="mb-4">This is to formally notify you that a <strong>hearing has been scheduled</strong> for Blotter Case No. <strong>#{{ str_pad($incident['id'], 4, '0', STR_PAD_LEFT) }}</strong>. Your presence is required on the date and time indicated below.</p>
                    @if($hearingDate)
                    <div class="border-2 border-gray-800 px-6 py-4 mb-4 text-center">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-1">Hearing Schedule</p>
                        <p class="text-lg font-bold text-gray-900">{{ $hearingDate }}</p>
                        <p class="text-xs text-gray-500 mt-1">Barangay Hall, Caranas, Motiong, Samar</p>
                    </div>
                    @if(!empty($incident['hearing_notes']))
                    <p class="mb-4"><strong>Additional Instructions:</strong> {{ $incident['hearing_notes'] }}</p>
                    @endif
                    @else
                    <p class="mb-4 text-red-600 italic">Note: No hearing date has been set yet.</p>
                    @endif

                    @elseif($type === 'status')
                    <p class="mb-4">This is to formally notify you that the status of blotter case <strong>#{{ str_pad($incident['id'], 4, '0', STR_PAD_LEFT) }}</strong> has been updated. Please see the case details below.</p>
                    <div class="border-2 border-gray-800 px-6 py-3 mb-4 text-center">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-1">Current Case Status</p>
                        <p class="text-lg font-bold text-gray-900">{{ $statusLabel }}</p>
                    </div>
                    @endif

                    <table class="w-full border-collapse mb-6 text-sm">
                        <tbody>
                            @foreach([
                                'Case Number'      => '#' . str_pad($incident['id'], 4, '0', STR_PAD_LEFT),
                                'Complainant'      => $incident['complainant_name'],
                                'Respondent'       => $incident['respondent_name'],
                                'Incident Type'    => $incident['incident_type'],
                                'Date of Incident' => date('F j, Y', strtotime($incident['date'])),
                                'Location'         => $incident['location'],
                                'Status'           => $statusLabel,
                            ] as $label => $value)
                            <tr>
                                <td class="border border-gray-400 px-3 py-1.5 bg-gray-50 font-bold text-gray-600 w-1/3 text-xs uppercase tracking-wide">{{ $label }}</td>
                                <td class="border border-gray-400 px-3 py-1.5">{{ $value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <p class="font-bold text-xs uppercase tracking-wide text-gray-600 mb-1">Narrative / Description</p>
                    <div class="border border-gray-400 px-4 py-3 mb-6 min-h-16 text-sm leading-relaxed">
                        {!! nl2br(e($incident['description'])) !!}
                    </div>

                    @if($type === 'hearing')
                    <p class="mb-4">Please bring a valid ID, any supporting documents, and evidence relevant to the case. Arrive <strong>15 minutes before</strong> the scheduled time. Failure to appear may result in an ex parte decision.</p>
                    @else
                    <p class="mb-4">For inquiries regarding this matter, please visit or contact the Barangay Hall of Caranas, Motiong, Samar during office hours.</p>
                    @endif
                    <p>Thank you for your cooperation.</p>

                    <div class="mt-12 grid grid-cols-2 gap-16 text-center text-sm">
                        <div>
                            <div class="border-b border-gray-800 mb-1 h-10"></div>
                            <p class="font-bold">{{ $incident['recorded_by_name'] ?? 'Barangay Staff' }}</p>
                            <p class="text-xs text-gray-600">Barangay Staff on Duty</p>
                        </div>
                        <div>
                            <div class="border-b border-gray-800 mb-1 h-10"></div>
                            <p class="font-bold">Barangay Captain</p>
                            <p class="text-xs text-gray-600">Brgy. Caranas, Motiong, Samar</p>
                        </div>
                    </div>

                    <div class="mt-10 pt-4 border-t border-gray-200 text-xs text-gray-400 text-center">
                        Generated by Digital Barangay Blotter System &mdash; Brgy. Caranas, Motiong, Samar &mdash; {{ date('F j, Y g:i A') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</body>
</html>
