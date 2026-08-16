<x-app-layout>
    <x-slot name="header">New Labour Entry</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6"
             x-data="{
                entryType: '{{ old('entry_type', 'daily_total') }}',
                expenseDate: '{{ old('expense_date', date('Y-m-d')) }}',
                rows: [{ id: '', name: '', amount: '' }],
                addRow() { this.rows.push({ id: '', name: '', amount: '' }); },
                removeRow(i) { this.rows.splice(i, 1); },
                get total() { return this.rows.reduce((sum, r) => sum + (parseFloat(r.amount) || 0), 0).toFixed(2); },
                landId: '{{ old('land_id', $lands->count() === 1 ? $lands->first()->id : '') }}',
                landsData: {{ $lands->mapWithKeys(fn($l) => [$l->id => $l->plantation_year])->toJson() }},
                get minDate() {
                    const year = this.landsData[this.landId];
                    return year ? year + '-01-01' : '';
                },
                get weekEndPreview() {
                    if (!this.expenseDate) return '';
                    const d = new Date(this.expenseDate + 'T00:00:00');
                    d.setDate(d.getDate() + 6);
                    return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
                }
             }">
            <h3 class="text-base font-semibold text-gray-800 mb-5">Labour Expense Details</h3>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('labour-expenses.store') }}" method="POST" class="space-y-4">
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

                <div>
                    <label for="expense_date" class="block text-sm font-medium text-gray-700">
                        <span x-show="entryType !== 'weekly_total'">Date *</span>
                        <span x-show="entryType === 'weekly_total'">Week Start Date *</span>
                    </label>
                    <x-text-input id="expense_date" name="expense_date" type="date" class="mt-1 w-full" x-model="expenseDate" max="{{ date('Y-m-d') }}" x-bind:min="minDate" required />

                    <p class="text-xs text-gray-400 mt-1" x-show="minDate && entryType !== 'weekly_total'">
                        Can't be before this land's plantation year (<span x-text="landsData[landId]"></span>).
                    </p>
                    <p class="text-xs text-gray-400 mt-1" x-show="!minDate && entryType !== 'weekly_total'">Future dates aren't allowed.</p>

                    <p class="text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2 mt-2" x-show="entryType === 'weekly_total' && expenseDate">
                        Covers the week from <span x-text="expenseDate"></span> to <strong x-text="weekEndPreview"></strong>.
                    </p>

                    <x-input-error :messages="$errors->get('expense_date')" class="mt-1" />
                </div>

                <div>
                    <x-input-label value="Entry Type" />
                    <div class="flex flex-wrap gap-4 mt-2">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="radio" name="entry_type" value="daily_total" x-model="entryType" class="text-green-600 focus:ring-green-500">
                            Daily Total
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="radio" name="entry_type" value="weekly_total" x-model="entryType" class="text-green-600 focus:ring-green-500">
                            Weekly Total
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="radio" name="entry_type" value="per_labourer" x-model="entryType" class="text-green-600 focus:ring-green-500">
                            Per Labourer
                        </label>
                    </div>
                </div>

                <!-- Daily Total / Weekly Total Section (shared fields) -->
                <div x-show="entryType === 'daily_total' || entryType === 'weekly_total'" x-transition class="space-y-4">
                    <div>
                        <label for="total_amount" class="block text-sm font-medium text-gray-700">
                            <span x-show="entryType !== 'weekly_total'">Total Amount Paid (₹) *</span>
                            <span x-show="entryType === 'weekly_total'">Total Amount Paid for the Week (₹) *</span>
                        </label>
                        <x-text-input id="total_amount" name="total_amount" type="number" step="0.01" min="0" class="mt-1" :value="old('total_amount')" placeholder="0.00" x-bind:disabled="entryType !== 'daily_total' && entryType !== 'weekly_total'" />
                        <x-input-error :messages="$errors->get('total_amount')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="2" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Optional notes" x-bind:disabled="entryType !== 'daily_total' && entryType !== 'weekly_total'">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Per Labourer Section -->
                <div x-show="entryType === 'per_labourer'" x-transition class="space-y-3">
                    <x-input-label value="Labourer List" />
                    <template x-for="(row, index) in rows" :key="index">
                        <div class="flex gap-2 items-center">
                            <select :name="'labourers[' + index + '][id]'" x-model="row.id" x-bind:disabled="entryType !== 'per_labourer'" class="w-1/3 border-gray-300 rounded-lg shadow-sm text-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">-- New Labourer --</option>
                                @foreach($labourers as $labourer)
                                    <option value="{{ $labourer->id }}">{{ $labourer->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" :name="'labourers[' + index + '][name]'" x-model="row.name" x-bind:disabled="entryType !== 'per_labourer'" placeholder="Or type new name" class="w-1/3 border-gray-300 rounded-lg shadow-sm text-sm focus:border-green-500 focus:ring-green-500">
                            <input type="number" step="0.01" min="0" :name="'labourers[' + index + '][amount]'" x-model="row.amount" x-bind:disabled="entryType !== 'per_labourer'" placeholder="₹ Amount" class="w-1/4 border-gray-300 rounded-lg shadow-sm text-sm focus:border-green-500 focus:ring-green-500">
                            <button type="button" @click="removeRow(index)" class="text-red-500 hover:text-red-700 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="addRow()" class="text-sm font-medium text-green-700 hover:text-green-800 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add Another Labourer
                    </button>
                    <div class="bg-blue-50 text-blue-700 text-sm rounded-lg px-4 py-3">
                        Total Cost: ₹ <strong x-text="total"></strong>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3">
                    <a href="{{ route('labour-expenses.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-primary-button>Save Expense</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>