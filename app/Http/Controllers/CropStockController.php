<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Land;
use App\Models\CropStock;
use Illuminate\Http\Request;

class CropStockController extends Controller
{
    public function index()
    {
        $stocks = CropStock::where('user_id', auth()->id())
            ->with(['crop', 'land'])->latest('stock_date')->get();

        $rawSummary = CropStock::where('user_id', auth()->id())
            ->with('crop')
            ->get()
            ->groupBy('crop.name')
            ->map(function ($items) {
                return [
                    'green'     => $items->where('type', 'green')->sum('quantity_kg'),
                    'processed' => $items->where('type', 'processed')->sum('quantity_kg'),
                ];
            });

        // Total stock (green + processed) across all crops, used to calculate each crop's share
        $grandTotal = $rawSummary->sum(fn($totals) => $totals['green'] + $totals['processed']);

        $summary = $rawSummary->map(function ($totals) use ($grandTotal) {
            $cropTotal = $totals['green'] + $totals['processed'];
            $totals['total'] = $cropTotal;
            $totals['percentage'] = $grandTotal > 0 ? round(($cropTotal / $grandTotal) * 100, 1) : 0;
            return $totals;
        });

        return view('crop-stocks.index', compact('stocks', 'summary'));
    }

    public function create()
    {
        $crops = Crop::where('user_id', auth()->id())->get();
        $lands = Land::where('user_id', auth()->id())->get();
        return view('crop-stocks.create', compact('crops', 'lands'));
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
            'crop_id'     => 'nullable|exists:crops,id',
            'crop_name'   => 'required_without:crop_id|nullable|string|max:255',
            'land_id'     => 'nullable|exists:lands,id',
            'type'        => 'required|in:green,processed',
            'quantity_kg' => 'required|numeric|min:0',
            'stock_date'  => $this->dateRules($request),
            'notes'       => 'nullable|string',
        ]);

        $cropId = $request->crop_id;

        if (!$cropId && $request->crop_name) {
            $crop = Crop::firstOrCreate([
                'user_id' => auth()->id(),
                'name'    => $request->crop_name,
            ]);
            $cropId = $crop->id;
        }

        CropStock::create([
            'user_id'     => auth()->id(),
            'crop_id'     => $cropId,
            'land_id'     => $request->land_id ?: null,
            'type'        => $request->type,
            'quantity_kg' => $request->quantity_kg,
            'stock_date'  => $request->stock_date,
            'notes'       => $request->notes,
        ]);

        return redirect()->route('crop-stocks.index')->with('success', 'Stock entry saved.');
    }

    public function show(CropStock $cropStock)
    {
        abort_if($cropStock->user_id !== auth()->id(), 403);
        return redirect()->route('crop-stocks.index');
    }

    public function edit(CropStock $cropStock)
    {
        abort_if($cropStock->user_id !== auth()->id(), 403);
        $crops = Crop::where('user_id', auth()->id())->get();
        $lands = Land::where('user_id', auth()->id())->get();
        return view('crop-stocks.edit', compact('cropStock', 'crops', 'lands'));
    }

    public function update(Request $request, CropStock $cropStock)
    {
        abort_if($cropStock->user_id !== auth()->id(), 403);
        $request->validate([
            'land_id'     => 'nullable|exists:lands,id',
            'type'        => 'required|in:green,processed',
            'quantity_kg' => 'required|numeric|min:0',
            'stock_date'  => $this->dateRules($request),
        ]);
        $cropStock->update($request->only('crop_id', 'land_id', 'type', 'quantity_kg', 'stock_date', 'notes'));
        return redirect()->route('crop-stocks.index')->with('success', 'Stock updated.');
    }

    public function destroy(CropStock $cropStock)
    {
        abort_if($cropStock->user_id !== auth()->id(), 403);
        $cropStock->delete();
        return redirect()->route('crop-stocks.index')->with('success', 'Stock entry deleted.');
    }
}