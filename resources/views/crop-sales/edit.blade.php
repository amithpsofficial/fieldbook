<x-app-layout>
    <x-slot name="header">Edit Sale Record</x-slot>

    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6"
             x-data="{
                price: {{ $cropSale->price_per_kg }},
                weight: {{ $cropSale->weight_sold_kg }},
                get total() { return ((parseFloat(this.price) || 0) * (parseFloat(this.weight) || 0)).toFixed(2); },
                landId: '{{ old('land_id', $cropSale->land_id) }}',
                landsData: {{ $lands->mapWithKeys(fn($l) => [$l->id => $l->plantation_year])->toJson() }},
                get minDate() {
                    const year = this.landsData[this.landId];
                    return year ? year + '-01-01' : '';
                }
             }">
            <h3 class="text-base font-semibold text-gray-800 mb-5">Edit Sale Details</h3>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('crop-sales.update', $cropSale) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="crop_id" value="Crop" />
                    <select id="crop_id" name="crop_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}" {{ $cropSale->crop_id == $crop->id ? 'selected' : '' }}>
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
                    <x-input-label for="buyer_name" value="Buyer Name" />
                    <x-text-input id="buyer_name" name="buyer_name" type="text" class="mt-1" :value="$cropSale->buyer_name" required />
                    <x-input-error :messages="$errors->get('buyer_name')" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price_per_kg" value="Price per kg (₹)" />
                        <x-text-input id="price_per_kg" name="price_per_kg" type="number" step="0.01" class="mt-1" x-model="price" required />
                        <x-input-error :messages="$errors->get('price_per_kg')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="weight_sold_kg" value="Weight Sold (kg)" />
                        <x-text-input id="weight_sold_kg" name="weight_sold_kg" type="number" step="0.01" class="mt-1" x-model="weight" required />
                        <x-input-error :messages="$errors->get('weight_sold_kg')" class="mt-1" />
                    </div>
                </div>

                <div class="bg-green-50 text-green-700 text-sm rounded-lg px-4 py-3">
                    Total Income: ₹ <strong x-text="total"></strong>
                </div>

                <div>
                    <x-input-label for="sale_date" value="Sale Date" />
                    <x-text-input id="sale_date" name="sale_date" type="date" class="mt-1" :value="$cropSale->sale_date->format('Y-m-d')" max="{{ date('Y-m-d') }}" x-bind:min="minDate" required />
                    <p class="text-xs text-gray-400 mt-1" x-show="minDate">
                        Can't be before this land's plantation year (<span x-text="landsData[landId]"></span>).
                    </p>
                    <x-input-error :messages="$errors->get('sale_date')" class="mt-1" />
                </div>

                <div class="flex items-center justify-between pt-3">
                    <a href="{{ route('crop-sales.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-primary-button>Update Sale</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>