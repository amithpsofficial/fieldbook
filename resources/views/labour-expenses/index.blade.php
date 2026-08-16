<x-app-layout>
    <x-slot name="header">Labour Expenses</x-slot>

    <div class="space-y-6">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Track wages paid to labourers.</p>
            <a href="{{ route('labour-expenses.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 shadow-sm hover:shadow-md transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add Labour Expense
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
                            <th class="px-6 py-3 font-medium w-8"></th>
                            <th class="px-6 py-3 font-medium">Date</th>
                            <th class="px-6 py-3 font-medium">Land</th>
                            <th class="px-6 py-3 font-medium">Type</th>
                            <th class="px-6 py-3 font-medium">Amount</th>
                            <th class="px-6 py-3 font-medium">Notes</th>
                            <th class="px-6 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    @forelse($expenses as $expense)
                        @php
                            $isPerLabourer = $expense->entry_type === 'per_labourer' && $expense->details->count();
                            $entryLabels = ['daily_total' => 'Daily Total', 'weekly_total' => 'Weekly Total', 'per_labourer' => 'Per Labourer'];
                            $entryColors = ['daily_total' => 'bg-blue-50 text-blue-700', 'weekly_total' => 'bg-green-50 text-green-700', 'per_labourer' => 'bg-amber-50 text-amber-700'];
                        @endphp
                        <tbody x-data="{ open: false }" class="divide-y divide-gray-100 border-b border-gray-100">
                            <tr class="hover:bg-gray-50 transition-colors duration-150" :class="open && 'bg-gray-50'">
                                <td class="px-6 py-4">
                                    @if($isPerLabourer)
                                        <button @click="open = !open" class="text-gray-400 hover:text-green-600 transition-colors duration-150">
                                            <svg class="w-4 h-4 transition-transform duration-150" :class="open && 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </button>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $expense->expense_date->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $expense->land->name ?? 'All Lands' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $entryColors[$expense->entry_type] ?? 'bg-gray-50 text-gray-700' }}">
                                        {{ $entryLabels[$expense->entry_type] ?? ucfirst(str_replace('_', ' ', $expense->entry_type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800">₹ {{ number_format($expense->total_amount, 2) }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $expense->notes ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('labour-expenses.edit', $expense) }}" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-150">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="{{ route('labour-expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Delete this expense?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @if($isPerLabourer)
                                <tr x-show="open" x-transition x-cloak>
                                    <td></td>
                                    <td colspan="6" class="px-6 pb-4">
                                        <div class="bg-gray-50 rounded-lg border border-gray-100 divide-y divide-gray-100">
                                            @foreach($expense->details as $detail)
                                                <div class="flex items-center justify-between px-4 py-2.5 text-sm">
                                                    <span class="text-gray-600 flex items-center gap-2">
                                                        <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-semibold shrink-0">
                                                            {{ substr($detail->labourer->name ?? '?', 0, 1) }}
                                                        </span>
                                                        {{ $detail->labourer->name ?? 'Unknown' }}
                                                    </span>
                                                    <span class="font-medium text-gray-800">₹ {{ number_format($detail->amount, 2) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    @empty
                        <tbody>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    No labour expenses recorded yet.
                                </td>
                            </tr>
                        </tbody>
                    @endforelse
                </table>
            </div>
        </div>

    </div>
</x-app-layout>