@extends('layouts.app')
@php $pageTitle = 'Add Incident'; @endphp

@section('content')
<main id="main-content" class="flex-1 overflow-auto bg-gray-50">
    <div class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4 sticky top-0 z-10">
        <div class="flex items-center gap-2">
            <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('incidents.index') }}" class="hover:text-green-700 transition-colors">Incident Records</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-700 font-medium">Add New Incident</span>
                </div>
                <h1 class="text-xl font-semibold text-gray-800">Add New Incident</h1>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6 max-w-3xl mx-auto">
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
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-base">{{ __('form.new_record') }}</p>
                        <p class="text-green-100 text-xs">{{ __('form.new_record_sub') }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('incidents.store') }}" novalidate>
                @csrf
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="complainant_name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('form.complainant_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="complainant_name" name="complainant_name" required maxlength="150"
                               value="{{ $old['complainant_name'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="{{ __('form.complainant_name_ph') }}" />
                    </div>

                    <div>
                        <label for="respondent_name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('form.respondent_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="respondent_name" name="respondent_name" required maxlength="150"
                               value="{{ $old['respondent_name'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="{{ __('form.respondent_name_ph') }}" />
                    </div>

                    <div>
                        <label for="complainant_email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('form.complainant_email') }} <span class="text-gray-300 font-normal normal-case">({{ __('form.email_note') }})</span></label>
                        <input type="email" id="complainant_email" name="complainant_email" maxlength="150"
                               value="{{ $old['complainant_email'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="complainant@example.com" />
                    </div>

                    <div>
                        <label for="respondent_email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('form.respondent_email') }} <span class="text-gray-300 font-normal normal-case">({{ __('form.email_note') }})</span></label>
                        <input type="email" id="respondent_email" name="respondent_email" maxlength="150"
                               value="{{ $old['respondent_email'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="respondent@example.com" />
                    </div>

                    <div>
                        <label for="incident_type" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('form.incident_type') }} <span class="text-red-500">*</span></label>
                        <select id="incident_type" name="incident_type" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">{{ __('form.select_type') }}</option>
                            @foreach($types as $type)
                            <option value="{{ $type['type_name'] }}" @selected(($old['incident_type'] ?? '') === $type['type_name'])>{{ $type['type_name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('form.incident_date') }} <span class="text-red-500">*</span></label>
                        <input type="date" id="date" name="date" required
                               value="{{ $old['date'] ?? date('Y-m-d') }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" />
                    </div>

                    <div class="md:col-span-2">
                        <label for="location" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('form.location') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="location" name="location" required maxlength="255"
                               value="{{ $old['location'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="{{ __('form.location_ph') }}" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Status <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach(\App\Models\Incident::STATUSES as $val => $label)
                            @php
                                $selected = ($old['status'] ?? 'pending') === $val;
                                $colors = ['pending' => 'peer-checked:border-yellow-400 peer-checked:bg-yellow-50 peer-checked:text-yellow-700', 'under_investigation' => 'peer-checked:border-teal-400 peer-checked:bg-teal-50 peer-checked:text-teal-700', 'resolved' => 'peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700', 'dismissed' => 'peer-checked:border-gray-400 peer-checked:bg-gray-100 peer-checked:text-gray-700'][$val];
                            @endphp
                            <label class="relative cursor-pointer">
                                <input type="radio" name="status" value="{{ $val }}" class="peer sr-only" @checked($selected)>
                                <div class="border-2 border-gray-200 rounded-xl px-3 py-2.5 text-center text-sm font-medium text-gray-500 transition-all {{ $colors }}">{{ $label }}</div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('form.description') }} <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="5" required maxlength="3000"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none transition"
                                  placeholder="{{ __('form.description_ph') }}">{{ $old['description'] ?? '' }}</textarea>
                    </div>

                    <div>
                        <label for="hearing_date" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('form.hearing_date') }} <span class="text-gray-300 font-normal normal-case">({{ __('common.optional') }})</span></label>
                        <input type="datetime-local" id="hearing_date" name="hearing_date"
                               value="{{ $old['hearing_date'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" />
                    </div>

                    <div>
                        <label for="hearing_notes" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('form.hearing_notes') }} <span class="text-gray-300 font-normal normal-case">({{ __('common.optional') }})</span></label>
                        <input type="text" id="hearing_notes" name="hearing_notes" maxlength="500"
                               value="{{ $old['hearing_notes'] ?? '' }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="{{ __('form.hearing_notes_ph') }}" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('incidents.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 rounded-xl transition-colors">{{ __('common.cancel') }}</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-green-700 hover:bg-green-800 rounded-xl transition-colors shadow-sm">{{ __('form.save_incident') }}</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
