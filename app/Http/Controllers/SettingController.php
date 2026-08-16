<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'season_start_month'      => 1,
                'default_day_rate'        => null,
                'default_per_person_rate' => null,
            ]
        );
        return view('settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'season_start_month'      => 'required|integer|min:1|max:12',
            'default_day_rate'        => 'nullable|numeric|min:0',
            'default_per_person_rate' => 'nullable|numeric|min:0',
        ]);

        Setting::updateOrCreate(
            ['user_id' => auth()->id()],
            $request->only('season_start_month', 'default_day_rate', 'default_per_person_rate')
        );

        return redirect()->route('settings')->with('success', 'Settings saved successfully.');
    }
}