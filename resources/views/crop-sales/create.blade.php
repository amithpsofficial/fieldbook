<x-app-layout>
    <x-slot name="header">Sell Crop</x-slot>

    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6"
             x-data="{
                cropId: '{{ old('crop_id') }}',
                landId: '{{ old('land_id', $lands->count() === 1 ? $lands->first()->id : '') }}',
                price: 0,
                weight: 0,
                get total() { return ((parseFloat(this.price) || 0) * (parseFloat(this.weight) || 0)).toFixed(2); },
                landsData: {{ $lands->mapWithKeys(fn($l) => [$l->id => $l->plantation_year])->toJson() }},
                cropStockData: {{ $cropStockData->toJson() }},
                get cropData() {
                    return this.cropStockData[this.cropId] || null;
                },
                get byLand() {
                    return this.cropData ? this.cropData.by_land : {};
                },
                get realLandKeys() {
                    return Object.keys(this.byLand).filter(k => k !== 'unassigned');
                },
                onCropChange() {
                    if (this.realLandKeys.length === 1) {
                        this.landId = this.realLandKeys[0];
                    }
                },
                get autoAssigned() {
                    return this.realLandKeys.length === 1 && this.landId === this.realLandKeys[0];
                },
                get availableStock() {
                    if (!this.cropData) return null;
                    if (this.landId && this.byLand[this.landId]) {
                        return this.byLand[this.landId].available;
                    }
                    if (this.landId) return 0;
                    return this.cropData.total_available;
                },
                get exceedsStock() {
                    return this.availableStock !== null && (parseFloat(this.weight) || 0) > this.availableStock;
                },
                get firstStockDateForSelection() {
                    if (!this.cropData) return null;
                    if (this.landId && this.byLand[this.landId]) {
                        return this.byLand[this.landId].first_stock_date;
                    }
                    return this.cropData.first_stock_date;
                },
                get minDate() {
                    const plantationYear = this.landsData[this.landId];
                    const plantationDate = plantationYear ? plantationYear + '-01-01' : null;
                    const stockDate = this.firstStockDateForSelection;
                    const dates = [plantationDate, stockDate].filter(Boolean);
                    if (!dates.length) return '';
                    return dates.reduce((a, b) => (a > b ? a : b));
                }
             }">
            <h3 class="text-base font-semibold text-gray-800 mb-5">Sale Details</h3>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('crop-sales.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="crop_id" value="Crop *" />
                    <select id="crop_id" name="crop_id" x-model="cropId" @change="onCropChange()" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" required>
                        <option value="">-- Select Crop --</option>
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}">{{ $crop->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('crop_id')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="land_id" value="Select Land" />
                    <select id="land_id" name="land_id" x-model="landId" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">All Lands (Default)</option>
                        @foreach($lands as $land)
                            <option value="{{ $land->id }}">{{ $land->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2 mt-2" x-show="autoAssigned">
                        Auto-assigned — this is the only land with stock of this crop.
                    </p>
                    <p class="text-xs text-gray-500 mt-2" x-show="cropId && availableStock !== null">
                        Available processed stock: <strong x-text="availableStock"></strong> kg
                    </p>
                </div>

                <div>
                    <x-input-label for="buyer_name" value="Buyer Name *" />
                    <x-text-input id="buyer_name" name="buyer_name" type="text" class="mt-1" placeholder="Buyer or business name" required />
                    <x-input-error :messages="$errors->get('buyer_name')" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price_per_kg" value="Price per kg (₹) *" />
                        <x-text-input id="price_per_kg" name="price_per_kg" type="number" step="0.01" min="0" class="mt-1" x-model="price" required />
                        <x-input-error :messages="$errors->get('price_per_kg')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="weight_sold_kg" value="Weight Sold (kg) *" />
                        <x-text-input id="weight_sold_kg" name="weight_sold_kg" type="number" step="0.01" min="0" class="mt-1" x-model="weight" x-bind:class="exceedsStock ? 'border-red-400' : ''" required />
                        <p class="text-xs text-red-600 mt-1" x-show="exceedsStock">
                            Exceeds available stock (<span x-text="availableStock"></span> kg).
                        </p>
                        <x-input-error :messages="$errors->get('weight_sold_kg')" class="mt-1" />
                    </div>
                </div>

                <div class="bg-green-50 text-green-700 text-sm rounded-lg px-4 py-3">
                    Total Income: ₹ <strong x-text="total"></strong>
                </div>

                <div>
                    <x-input-label for="sale_date" value="Sale Date *" />
                    <x-text-input id="sale_date" name="sale_date" type="date" class="mt-1" :value="date('Y-m-d')" max="{{ date('Y-m-d') }}" x-bind:min="minDate" required />
                    <p class="text-xs text-gray-400 mt-1" x-show="minDate">
                        Can't be before <span x-text="minDate"></span> (land's plantation year or first recorded stock, whichever is later).
                    </p>
                    <x-input-error :messages="$errors->get('sale_date')" class="mt-1" />
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="deduct_from_stock" value="1" id="deduct_from_stock" checked class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <label for="deduct_from_stock" class="text-sm text-gray-700">Deduct from Current Stock</label>
                </div>

                <div class="flex items-center justify-between pt-3">
                    <a href="{{ route('crop-sales.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-primary-button x-bind:disabled="exceedsStock" x-bind:class="exceedsStock ? 'opacity-50 cursor-not-allowed' : ''">Record Sale</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>