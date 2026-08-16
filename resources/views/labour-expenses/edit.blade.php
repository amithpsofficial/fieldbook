<x-app-layout>
    <x-slot name="header">Edit Labour Expense</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6"
             x-data="{
                landId: '{{ $labourExpense->land_id }}',
                landsData: {{ $lands->mapWithKeys(fn($l) => [$l->id => $l->plantation_year])->toJson() }},
                get minDate() {
                    const year = this.landsData[this.landId];
                    return year ? year + '-01-01' : '';
                }
             }">
            <h3 class="text-base font-semibold text-gray-800 mb-5">Edit Details</h3>

            <form action="{{ route('labour-expenses.update', $labourExpense) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

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
                    <x-input-label for="expense_date" value="Date *" />
                    <x-text-input id="expense_date" name="expense_date" type="date" class="mt-1" :value="$labourExpense->expense_date->format('Y-m-d')" max="{{ date('Y-m-d') }}" x-bind:min="minDate" required />
                    <p class="text-xs text-gray-400 mt-1" x-show="minDate">
                        Can't be before this land's plantation year (<span x-text="landsData[landId]"></span>).
                    </p>
                    <p class="text-xs text-gray-400 mt-1" x-show="!minDate">Future dates aren't allowed.</p>
                </div>

                <div>
                    <x-input-label for="total_amount" value="Total Amount (₹) *" />
                    <x-text-input id="total_amount" name="total_amount" type="number" step="0.01" min="0" class="mt-1" :value="$labourExpense->total_amount" required />
                </div>

                <div>
                    <x-input-label for="notes" value="Notes" />
                    <textarea id="notes" name="notes" rows="2" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">{{ $labourExpense->notes }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-3">
                    <a href="{{ route('labour-expenses.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-primary-button>Update Expense</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>