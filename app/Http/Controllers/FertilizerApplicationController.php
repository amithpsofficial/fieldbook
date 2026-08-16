<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\FertilizerApplication;
use Illuminate\Http\Request;

class FertilizerApplicationController extends Controller
{
    public function index()
    {
        $applications = FertilizerApplication::where('user_id', auth()->id())
            ->with('land')
            ->latest('date_applied')
            ->get();
        return view('fertilizer-applications.index', compact('applications'));
    }

    public function create()
    {
        $lands = Land::where('user_id', auth()->id())->get();
        return view('fertilizer-applications.create', compact('lands'));
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
            'brand_name'      => 'required|string|max:255',
            'date_applied'    => $this->dateRules($request),
            'chemical_content'=> 'nullable|string|max:255',
            'vendor_name'     => 'nullable|string|max:255',
            'cost'            => 'nullable|numeric|min:0',
            'dosage_amount'   => 'nullable|numeric|min:0',
            'dosage_unit'     => 'nullable|in:gms_cent,ml_litre',
            'climate'         => 'nullable|in:sunny,cloudy,slight_rainy,rainy',
            'observation'     => 'nullable|string',
        ]);

        FertilizerApplication::create([
            'user_id'          => auth()->id(),
            'land_id'          => $request->land_id ?: null,
            'brand_name'       => $request->brand_name,
            'chemical_content' => $request->chemical_content,
            'vendor_name'      => $request->vendor_name,
            'cost'             => $request->cost,
            'dosage_amount'    => $request->dosage_amount,
            'dosage_unit'      => $request->dosage_unit,
            'date_applied'     => $request->date_applied,
            'climate'          => $request->climate,
            'observation'      => $request->observation,
        ]);

        return redirect()->route('fertilizer-applications.index')
            ->with('success', 'Fertilizer record saved.');
    }

    public function show(FertilizerApplication $fertilizerApplication)
    {
        abort_if($fertilizerApplication->user_id !== auth()->id(), 403);
        return redirect()->route('fertilizer-applications.index');
    }

    public function edit(FertilizerApplication $fertilizerApplication)
    {
        abort_if($fertilizerApplication->user_id !== auth()->id(), 403);
        $lands = Land::where('user_id', auth()->id())->get();
        return view('fertilizer-applications.edit', compact('fertilizerApplication', 'lands'));
    }

    public function update(Request $request, FertilizerApplication $fertilizerApplication)
    {
        abort_if($fertilizerApplication->user_id !== auth()->id(), 403);

        $request->validate([
            'brand_name'   => 'required|string|max:255',
            'date_applied' => $this->dateRules($request),
            'cost'         => 'nullable|numeric|min:0',
            'dosage_amount'=> 'nullable|numeric|min:0',
            'dosage_unit'  => 'nullable|in:gms_cent,ml_litre',
            'climate'      => 'nullable|in:sunny,cloudy,slight_rainy,rainy',
        ]);

        $fertilizerApplication->update($request->except(['_token', '_method']));

        return redirect()->route('fertilizer-applications.index')
            ->with('success', 'Fertilizer record updated.');
    }

    public function destroy(FertilizerApplication $fertilizerApplication)
    {
        abort_if($fertilizerApplication->user_id !== auth()->id(), 403);
        $fertilizerApplication->delete();
        return redirect()->route('fertilizer-applications.index')
            ->with('success', 'Record deleted.');
    }
}