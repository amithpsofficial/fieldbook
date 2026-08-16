<?php
namespace App\Http\Controllers;
use App\Models\Land;
use App\Models\OtherExpense;
use Illuminate\Http\Request;

class OtherExpenseController extends Controller
{
    public function index()
    {
        $expenses = OtherExpense::where('user_id', auth()->id())
            ->with('land')->latest('expense_date')->get();
        return view('other-expenses.index', compact('expenses'));
    }

    public function create()
    {
        $lands = Land::where('user_id', auth()->id())->get();
        return view('other-expenses.create', compact('lands'));
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
            'expense_type' => 'required|in:transportation,electricity,other',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => $this->dateRules($request),
            'notes'        => 'nullable|string',
        ]);
        OtherExpense::create([
            'user_id'      => auth()->id(),
            'land_id'      => $request->land_id ?: null,
            'expense_type' => $request->expense_type,
            'amount'       => $request->amount,
            'expense_date' => $request->expense_date,
            'notes'        => $request->notes,
        ]);
        return redirect()->route('other-expenses.index')
            ->with('success', 'Expense saved.');
    }

    public function show(OtherExpense $otherExpense)
    {
        abort_if($otherExpense->user_id !== auth()->id(), 403);
        return redirect()->route('other-expenses.index');
    }

    public function edit(OtherExpense $otherExpense)
    {
        abort_if($otherExpense->user_id !== auth()->id(), 403);
        $lands = Land::where('user_id', auth()->id())->get();
        return view('other-expenses.edit', compact('otherExpense', 'lands'));
    }

    public function update(Request $request, OtherExpense $otherExpense)
    {
        abort_if($otherExpense->user_id !== auth()->id(), 403);
        $request->validate([
            'expense_type' => 'required|in:transportation,electricity,other',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => $this->dateRules($request),
        ]);
        $otherExpense->update($request->only('land_id', 'expense_type', 'amount', 'expense_date', 'notes'));
        return redirect()->route('other-expenses.index')->with('success', 'Expense updated.');
    }

    public function destroy(OtherExpense $otherExpense)
    {
        abort_if($otherExpense->user_id !== auth()->id(), 403);
        $otherExpense->delete();
        return redirect()->route('other-expenses.index')->with('success', 'Expense deleted.');
    }
}