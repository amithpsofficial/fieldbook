<x-app-layout>
    <x-slot name="header">Edit Stock Entry</x-slot>

    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6"
             x-data="{
                landId: '{{ old('land_id', $cropStock->land_id) }}',
                landsData: {{ $lands->mapWithKeys(fn($l) => [$l->id => $l->plantation_year])->toJson() }},
                get minDate() {
                    const year = this.landsData[this.landId];
                    return year ? year + '-01-01' : '';
                }
             }">
            <h3 class="text-base font-semibold text-gray-800 mb-5">Edit Details</h3>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('crop-stocks.update', $cropStock) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="crop_id" value="Crop" />
                    <select id="crop_id" name="crop_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}" {{ $cropStock->crop_id == $crop->id ? 'selected' : '' }}>
                                {{ $crop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="land_id" value="Select Land" />
                    <select id="land_id" name="land_id" x-model="landId" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">All Lands (Default)</option>
                        @foreach($lands as $land)
                            <option value="{{ $land->id }}">{{ $land->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="type" value="Stock Type" />
                    <select id="type" name="type" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="green" {{ $cropStock->type === 'green' ? 'selected' : '' }}>Green</option>
                        <option value="processed" {{ $cropStock->type === 'processed' ? 'selected' : '' }}>Processed</option>
                    </select>
                </div>

                <div>
                    <x-input-label for="quantity_kg" value="Quantity (kg)" />
                    <x-text-input id="quantity_kg" name="quantity_kg" type="number" step="0.01" class="mt-1" :value="$cropStock->quantity_kg" required />
                    <x-input-error :messages="$errors->get('quantity_kg')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="stock_date" value="Date" />
                    <x-text-input id="stock_date" name="stock_date" type="date" class="mt-1" :value="$cropStock->stock_date->format('Y-m-d')" max="{{ date('Y-m-d') }}" x-bind:min="minDate" required />
                    <p class="text-xs text-gray-400 mt-1" x-show="minDate">
                        Can't be before this land's plantation year (<span x-text="landsData[landId]"></span>).
                    </p>
                    <x-input-error :messages="$errors->get('stock_date')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="notes" value="Notes" />
                    <textarea id="notes" name="notes" rows="2" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">{{ $cropStock->notes }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-3">
                    <a href="{{ route('crop-stocks.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-primary-button>Update Stock</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>