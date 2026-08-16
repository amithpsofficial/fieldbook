<x-app-layout>
    <x-slot name="header">Edit Other Expense</x-slot>

    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6"
             x-data="{
                landId: '{{ old('land_id', $otherExpense->land_id) }}',
                landsData: {{ $lands->mapWithKeys(fn($l) => [$l->id => $l->plantation_year])->toJson() }},
                get minDate() {
                    const year = this.landsData[this.landId];
                    return year ? year + '-01-01' : '';
                }
             }">
            <h3 class="text-base font-semibold text-gray-800 mb-5">Edit Expense Details</h3>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('other-expenses.update', $otherExpense) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="expense_type" value="Expense Type *" />
                    <select id="expense_type" name="expense_type" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" required>
                        <option value="transportation" {{ $otherExpense->expense_type === 'transportation' ? 'selected' : '' }}>Transportation</option>
                        <option value="electricity" {{ $otherExpense->expense_type === 'electricity' ? 'selected' : '' }}>Electricity</option>
                        <option value="other" {{ $otherExpense->expense_type === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('expense_type')" class="mt-1" />
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
                    <x-input-label for="amount" value="Amount (₹) *" />
                    <x-text-input id="amount" name="amount" type="number" step="0.01" min="0" class="mt-1" :value="$otherExpense->amount" required />
                    <x-input-error :messages="$errors->get('amount')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="expense_date" value="Date *" />
                    <x-text-input id="expense_date" name="expense_date" type="date" class="mt-1" :value="$otherExpense->expense_date->format('Y-m-d')" max="{{ date('Y-m-d') }}" x-bind:min="minDate" required />
                    <p class="text-xs text-gray-400 mt-1" x-show="minDate">
                        Can't be before this land's plantation year (<span x-text="landsData[landId]"></span>).
                    </p>
                    <x-input-error :messages="$errors->get('expense_date')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="notes" value="Notes" />
                    <textarea id="notes" name="notes" rows="2" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">{{ $otherExpense->notes }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-3">
                    <a href="{{ route('other-expenses.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-primary-button>Update Expense</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>