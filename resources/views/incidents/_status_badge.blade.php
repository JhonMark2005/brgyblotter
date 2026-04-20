@php
$statusMap = [
    'pending'             => ['label' => 'Pending',             'class' => 'bg-yellow-100 text-yellow-800'],
    'under_investigation' => ['label' => 'Under Investigation', 'class' => 'bg-teal-100 text-teal-800'],
    'resolved'            => ['label' => 'Resolved',            'class' => 'bg-green-100 text-green-800'],
    'dismissed'           => ['label' => 'Dismissed',           'class' => 'bg-gray-100 text-gray-600'],
];
$s = $statusMap[$inc['status']] ?? ['label' => ucfirst($inc['status']), 'class' => 'bg-gray-100 text-gray-600'];
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $s['class'] }}">
    {{ $s['label'] }}
</span>
