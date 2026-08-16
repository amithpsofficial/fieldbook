<x-app-layout>
    <x-slot name="header">Add Fertilizer / Pesticide</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6"
             x-data="{
                landId: '{{ old('land_id', $lands->count() === 1 ? $lands->first()->id : '') }}',
                landsData: {{ $lands->mapWithKeys(fn($l) => [$l->id => $l->plantation_year])->toJson() }},
                get minDate() {
                    const year = this.landsData[this.landId];
                    return year ? year + '-01-01' : '';
                }
             }">
            <h3 class="text-base font-semibold text-gray-800 mb-5">Application Details</h3>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('fertilizer-applications.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="land_id" value="Select Land" />
                    <select id="land_id" name="land_id" x-model="landId" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">All Lands (Default)</option>
                        @foreach($lands as $land)
                            <option value="{{ $land->id }}">{{ $land->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="brand_name" value="Brand Name *" />
                        <x-text-input id="brand_name" name="brand_name" type="text" class="mt-1" :value="old('brand_name')" placeholder="e.g. Factamfos" />
                        <x-input-error :messages="$errors->get('brand_name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="chemical_content" value="Chemical Content" />
                        <x-text-input id="chemical_content" name="chemical_content" type="text" class="mt-1" :value="old('chemical_content')" placeholder="e.g. Sulphur/Phosphate" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="vendor_name" value="Vendor / Supplier" />
                        <x-text-input id="vendor_name" name="vendor_name" type="text" class="mt-1" :value="old('vendor_name')" />
                    </div>
                    <div>
                        <x-input-label for="cost" value="Total Cost (₹)" />
                        <x-text-input id="cost" name="cost" type="number" step="0.01" min="0" class="mt-1" :value="old('cost')" />
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="dosage_amount" value="Dosage Amount" />
                        <x-text-input id="dosage_amount" name="dosage_amount" type="number" step="0.01" min="0" class="mt-1" :value="old('dosage_amount')" />
                    </div>
                    <div>
                        <x-input-label for="dosage_unit" value="Dosage Unit" />
                        <select id="dosage_unit" name="dosage_unit" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">-- Select --</option>
                            <option value="gms_cent">Gms / Cent</option>
                            <option value="ml_litre">Ml / Litre</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="date_applied" value="Date Applied *" />
                        <x-text-input id="date_applied" name="date_applied" type="date" class="mt-1" :value="old('date_applied', date('Y-m-d'))" max="{{ date('Y-m-d') }}" x-bind:min="minDate" required />
                        <p class="text-xs text-gray-400 mt-1" x-show="minDate">
                            Can't be before this land's plantation year (<span x-text="landsData[landId]"></span>).
                        </p>
                        <x-input-error :messages="$errors->get('date_applied')" class="mt-1" />
                    </div>
                </div>

                <div>
                    <x-input-label for="climate" value="Climate" />
                    <select id="climate" name="climate" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">-- Select --</option>
                        <option value="sunny">Sunny</option>
                        <option value="cloudy">Cloudy</option>
                        <option value="slight_rainy">Slight Rainy</option>
                        <option value="rainy">Rainy</option>
                    </select>
                </div>

                <div>
                    <x-input-label for="observation" value="Observation" />
                    <textarea id="observation" name="observation" rows="3" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="What was the result of this application?">{{ old('observation') }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-3">
                    <a href="{{ route('fertilizer-applications.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-primary-button>Save Record</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>