<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\Labourer;
use App\Models\LabourExpense;
use App\Models\LabourExpenseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabourExpenseController extends Controller
{
    public function index()
    {
        $expenses = LabourExpense::where('user_id', auth()->id())
            ->with(['land', 'details.labourer'])
            ->latest()
            ->get();
        return view('labour-expenses.index', compact('expenses'));
    }

    public function create()
    {
        $lands = Land::where('user_id', auth()->id())->get();
        $labourers = Labourer::where('user_id', auth()->id())->get();
        return view('labour-expenses.create', compact('lands', 'labourers'));
    }

    protected function dateRules(Request $request)
    {
        return ['required', 'date', 'before_or_equal:today', function ($attribute, $value, $fail) use ($request) {
            if ($request->land_id) {
                $land = Land::find($request->land_id);
                if ($land && $land->plantation_year && $value < $land->plantation_year . '-01-01') {
                    $fail("The date can't be before this land's plantation year ({$land->plantation_year}).");
                }
            }
        }];
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => $this->dateRules($request),
            'entry_type' => 'required|in:daily_total,weekly_total,per_labourer',
            'total_amount' => 'required_if:entry_type,daily_total,weekly_total|nullable|numeric|min:0',
            'labourers' => 'required_if:entry_type,per_labourer|nullable|array',
            'labourers.*.id' => 'nullable|exists:labourers,id',
            'labourers.*.name' => 'required_without:labourers.*.id|nullable|string',
            'labourers.*.amount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = 0;

            if ($request->entry_type === 'daily_total' || $request->entry_type === 'weekly_total') {
                $totalAmount = $request->total_amount;
            } else {
                foreach ($request->labourers as $l) {
                    $totalAmount += $l['amount'];
                }
            }

            $expense = LabourExpense::create([
                'user_id' => auth()->id(),
                'land_id' => $request->land_id ?: null,
                'expense_date' => $request->expense_date,
                'entry_type' => $request->entry_type,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            if ($request->entry_type === 'per_labourer') {
                foreach ($request->labourers as $l) {
                    $labourerId = $l['id'] ?? null;

                    if (!$labourerId && !empty($l['name'])) {
                        $labourer = Labourer::firstOrCreate(
                            ['user_id' => auth()->id(), 'name' => $l['name']],
                            ['default_rate' => $l['amount']]
                        );
                        $labourerId = $labourer->id;
                    }

                    if ($labourerId) {
                        LabourExpenseDetail::create([
                            'labour_expense_id' => $expense->id,
                            'labourer_id' => $labourerId,
                            'amount' => $l['amount'],
                        ]);
                    }
                }
            }
        });

        return redirect()->route('labour-expenses.index')->with('success', 'Labour expense saved.');
    }

    public function show(LabourExpense $labourExpense)
    {
        abort_if($labourExpense->user_id !== auth()->id(), 403);
        return redirect()->route('labour-expenses.index');
    }

    public function edit(LabourExpense $labourExpense)
    {
        abort_if($labourExpense->user_id !== auth()->id(), 403);
        $lands = Land::where('user_id', auth()->id())->get();
        $labourers = Labourer::where('user_id', auth()->id())->get();
        return view('labour-expenses.edit', compact('labourExpense', 'lands', 'labourers'));
    }

    public function update(Request $request, LabourExpense $labourExpense)
    {
        abort_if($labourExpense->user_id !== auth()->id(), 403);

        $request->validate([
            'expense_date' => $this->dateRules($request),
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $labourExpense->update($request->only('land_id', 'expense_date', 'total_amount', 'notes'));

        return redirect()->route('labour-expenses.index')->with('success', 'Labour expense updated.');
    }

    public function destroy(LabourExpense $labourExpense)
    {
        abort_if($labourExpense->user_id !== auth()->id(), 403);
        $labourExpense->delete();
        return redirect()->route('labour-expenses.index')->with('success', 'Labour expense deleted.');
    }
}