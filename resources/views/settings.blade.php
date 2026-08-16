<x-app-layout>
    <x-slot name="header">Settings</x-slot>

    <div class="max-w-2xl mx-auto space-y-6">

        @if(session('success'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Season Configuration -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                        <svg class="w-4.5 h-4.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">Season Configuration</h3>
                        <p class="text-xs text-gray-400">Controls the date range used across Reports</p>
                    </div>
                </div>

                <div class="max-w-xs">
                    <x-input-label for="season_start_month" value="Season Start Month" />
                    <select id="season_start_month" name="season_start_month" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        @foreach(range(1,12) as $month)
                            <option value="{{ $month }}" {{ $setting->season_start_month == $month ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$month,1)) }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1.5">Reports will be calculated from this month to the same month next year.</p>
                </div>
            </div>

            <!-- Labour Rate Defaults -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                        <svg class="w-4.5 h-4.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">Labour Rate Defaults</h3>
                        <p class="text-xs text-gray-400">Pre-fills new labour expense entries</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="default_day_rate" value="Default Day Rate (₹)" />
                        <x-text-input id="default_day_rate" name="default_day_rate" type="number" step="0.01" min="0" class="mt-1" :value="$setting->default_day_rate" placeholder="e.g. 500" />
                        <p class="text-xs text-gray-400 mt-1.5">Used for daily total labour expenses.</p>
                    </div>
                    <div>
                        <x-input-label for="default_per_person_rate" value="Default Per Person Rate (₹)" />
                        <x-text-input id="default_per_person_rate" name="default_per_person_rate" type="number" step="0.01" min="0" class="mt-1" :value="$setting->default_per_person_rate" placeholder="e.g. 800" />
                        <p class="text-xs text-gray-400 mt-1.5">Auto-filled for per labourer entries.</p>
                    </div>
                </div>
            </div>

            <!-- Save bar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
                <p class="text-xs text-gray-400 pl-2">Changes apply the next time reports are calculated.</p>
                <x-primary-button>Save Settings</x-primary-button>
            </div>

        </form>
    </div>
</x-app-layout>