<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Land;
use App\Models\CropSale;
use App\Models\CropStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CropSaleController extends Controller
{
    public function index()
    {
        $sales = CropSale::where('user_id', auth()->id())
            ->with(['crop', 'land'])->latest('sale_date')->get();
        return view('crop-sales.index', compact('sales'));
    }

    public function create()
    {
        $crops = Crop::where('user_id', auth()->id())->get();
        $lands = Land::where('user_id', auth()->id())->get();

        // Only "processed" stock can be sold (green stock isn't market-ready yet).
        $processedStock = CropStock::where('user_id', auth()->id())
            ->where('type', 'processed')
            ->get();

        $cropStockData = $processedStock
            ->groupBy('crop_id')
            ->map(function ($rows) {
                $byLand = $rows->groupBy(fn($row) => $row->land_id ?? 'unassigned')
                    ->map(function ($landRows) {
                        $available = round($landRows->sum('quantity_kg'), 2);
                        $firstDate = $landRows->where('quantity_kg', '>', 0)->min('stock_date');
                        return [
                            'available' => $available,
                            'first_stock_date' => $firstDate ? Carbon::parse($firstDate)->format('Y-m-d') : null,
                        ];
                    })
                    ->filter(fn($d) => $d['available'] > 0);

                $firstDateOverall = $rows->where('quantity_kg', '>', 0)->min('stock_date');

                return [
                    'by_land' => $byLand,
                    'total_available' => round($rows->sum('quantity_kg'), 2),
                    'first_stock_date' => $firstDateOverall ? Carbon::parse($firstDateOverall)->format('Y-m-d') : null,
                ];
            });

        return view('crop-sales.create', compact('crops', 'lands', 'cropStockData'));
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

            if ($request->crop_id && $request->land_id) {
                $firstStockDate = CropStock::where('user_id', auth()->id())
                    ->where('crop_id', $request->crop_id)
                    ->where('land_id', $request->land_id)
                    ->min('stock_date');

                if ($firstStockDate && $value < $firstStockDate) {
                    $fail("The date can't be before stock of this crop was first recorded for this land ({$firstStockDate}).");
                }
            }
        }];
    }

    protected function stockAvailabilityRule(Request $request)
    {
        return function ($attribute, $value, $fail) use ($request) {
            if (!$request->crop_id) {
                return;
            }

            $query = CropStock::where('user_id', auth()->id())
                ->where('crop_id', $request->crop_id)
                ->where('type', 'processed');

            if ($request->land_id) {
                $query->where('land_id', $request->land_id);
            }

            $available = $query->sum('quantity_kg');

            if ($value > $available) {
                $scope = $request->land_id ? 'for this land' : 'across all lands';
                $fail('Only ' . number_format($available, 2) . " kg of processed stock is available {$scope}. You entered {$value} kg.");
            }
        };
    }

    public function store(Request $request)
    {
        $request->validate([
            'crop_id'       => 'required|exists:crops,id',
            'land_id'       => 'nullable|exists:lands,id',
            'buyer_name'    => 'required|string|max:255',
            'price_per_kg'  => 'required|numeric|min:0',
            'weight_sold_kg'=> ['required', 'numeric', 'min:0', $this->stockAvailabilityRule($request)],
            'sale_date'     => $this->dateRules($request),
            'deduct_from_stock' => 'boolean',
        ]);

        $totalIncome = $request->price_per_kg * $request->weight_sold_kg;

        DB::transaction(function () use ($request, $totalIncome) {
            CropSale::create([
                'user_id'           => auth()->id(),
                'crop_id'           => $request->crop_id,
                'land_id'           => $request->land_id ?: null,
                'buyer_name'        => $request->buyer_name,
                'price_per_kg'      => $request->price_per_kg,
                'weight_sold_kg'    => $request->weight_sold_kg,
                'total_income'      => $totalIncome,
                'deduct_from_stock' => $request->boolean('deduct_from_stock'),
                'sale_date'         => $request->sale_date,
            ]);

            if ($request->boolean('deduct_from_stock')) {
                CropStock::create([
                    'user_id'     => auth()->id(),
                    'crop_id'     => $request->crop_id,
                    'land_id'     => $request->land_id ?: null,
                    'type'        => 'processed',
                    'quantity_kg' => -$request->weight_sold_kg,
                    'stock_date'  => $request->sale_date,
                    'notes'       => 'Deducted via sale to ' . $request->buyer_name,
                ]);
            }
        });

        return redirect()->route('crop-sales.index')->with('success', 'Sale recorded successfully.');
    }

    public function show(CropSale $cropSale)
    {
        abort_if($cropSale->user_id !== auth()->id(), 403);
        return redirect()->route('crop-sales.index');
    }

    public function edit(CropSale $cropSale)
    {
        abort_if($cropSale->user_id !== auth()->id(), 403);
        $crops = Crop::where('user_id', auth()->id())->get();
        $lands = Land::where('user_id', auth()->id())->get();
        return view('crop-sales.edit', compact('cropSale', 'crops', 'lands'));
    }

    public function update(Request $request, CropSale $cropSale)
    {
        abort_if($cropSale->user_id !== auth()->id(), 403);
        $request->validate([
            'land_id'       => 'nullable|exists:lands,id',
            'buyer_name'    => 'required|string|max:255',
            'price_per_kg'  => 'required|numeric|min:0',
            'weight_sold_kg'=> 'required|numeric|min:0',
            'sale_date'     => $this->dateRules($request),
        ]);
        $cropSale->update([
            'land_id'        => $request->land_id ?: null,
            'buyer_name'     => $request->buyer_name,
            'price_per_kg'   => $request->price_per_kg,
            'weight_sold_kg' => $request->weight_sold_kg,
            'total_income'   => $request->price_per_kg * $request->weight_sold_kg,
            'sale_date'      => $request->sale_date,
        ]);
        return redirect()->route('crop-sales.index')->with('success', 'Sale updated.');
    }

    public function destroy(CropSale $cropSale)
    {
        abort_if($cropSale->user_id !== auth()->id(), 403);
        $cropSale->delete();
        return redirect()->route('crop-sales.index')->with('success', 'Sale deleted.');
    }
}