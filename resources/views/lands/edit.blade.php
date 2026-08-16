<x-app-layout>
    <x-slot name="header">Edit Land</x-slot>

    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-5">Edit Land Details</h3>

            <form action="{{ route('lands.update', $land) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="name" value="Land Name *" />
                    <x-text-input id="name" name="name" type="text" class="mt-1" :value="old('name', $land->name)" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div x-data="{ year: {{ old('plantation_year', $land->plantation_year ?? date('Y')) }} }">
                    <div class="flex items-center justify-between mb-1">
                        <x-input-label for="plantation_year_range" value="Plantation Year" class="mb-0" />
                        <span class="text-sm font-semibold text-green-700" x-text="year"></span>
                    </div>
                    <input id="plantation_year_range" type="range" min="1900" max="{{ date('Y') }}" step="1"
                           x-model="year"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-600">
                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>1900</span>
                        <span>{{ date('Y') }}</span>
                    </div>
                    <input type="hidden" name="plantation_year" x-bind:value="year">
                    <x-input-error :messages="$errors->get('plantation_year')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="location" value="Location (Village/Town)" />
                    <x-text-input id="location" name="location" type="text" class="mt-1" :value="old('location', $land->location)" />
                    <p class="text-xs text-gray-400 mt-1">Used to show local weather updates for this land.</p>
                    <x-input-error :messages="$errors->get('location')" class="mt-1" />
                </div>

                <div class="flex items-center justify-between pt-3">
                    <a href="{{ route('farm') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-primary-button>Update Land</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>