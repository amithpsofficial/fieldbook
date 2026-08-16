<?php

namespace App\Http\Controllers;

use App\Models\Land;
use Illuminate\Http\Request;

class LandController extends Controller
{
    public function index()
    {
        $lands = Land::where('user_id', auth()->id())->latest()->get();
        return view('lands.index', compact('lands'));
    }

    public function create()
    {
        return view('lands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'plantation_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'location' => 'nullable|string|max:255',
        ]);

        Land::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'plantation_year' => $request->plantation_year,
            'location' => $request->location,
        ]);

        return redirect()->route('farm')->with('success', 'Land added successfully.');
    }

    public function edit(Land $land)
    {
        abort_if($land->user_id !== auth()->id(), 403);
        return view('lands.edit', compact('land'));
    }

    public function update(Request $request, Land $land)
    {
        abort_if($land->user_id !== auth()->id(), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'plantation_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'location' => 'nullable|string|max:255',
        ]);

        $land->update($request->only('name', 'plantation_year', 'location'));

        return redirect()->route('farm')->with('success', 'Land updated successfully.');
    }

    public function destroy(Land $land)
    {
        abort_if($land->user_id !== auth()->id(), 403);
        $land->delete();
        return redirect()->route('farm')->with('success', 'Land deleted successfully.');
    }

    public function show(Land $land)
    {
        abort_if($land->user_id !== auth()->id(), 403);
        return redirect()->route('farm');
    }
}