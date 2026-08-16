<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\LabourExpense;
use App\Models\FertilizerApplication;
use App\Models\OtherExpense;
use App\Models\CropSale;
use App\Models\Land;
use App\Services\WeatherService;

class DashboardController extends Controller
{
    public function index(WeatherService $weatherService)
    {
        $user = auth()->user();

        $totalIncome = CropSale::where('user_id', $user->id)->sum('total_income');
        $labourTotal = LabourExpense::where('user_id', $user->id)->sum('total_amount');
        $fertilizerTotal = FertilizerApplication::where('user_id', $user->id)->sum('cost');
        $otherTotal = OtherExpense::where('user_id', $user->id)->sum('amount');
        $totalExpense = $labourTotal + $fertilizerTotal + $otherTotal;
        $netProfit = $totalIncome - $totalExpense;

        $recentActivity = collect();

        LabourExpense::where('user_id', $user->id)->latest()->take(5)->get()->each(function ($item) use ($recentActivity) {
            $recentActivity->push([
                'text' => 'Logged ₹' . number_format($item->total_amount, 2) . ' labour expense',
                'date' => $item->expense_date,
            ]);
        });

        FertilizerApplication::where('user_id', $user->id)->latest('date_applied')->take(5)->get()->each(function ($item) use ($recentActivity) {
            $recentActivity->push([
                'text' => 'Applied ' . ($item->brand_name ?? 'fertilizer') . ' costing ₹' . number_format($item->cost ?? 0, 2),
                'date' => $item->date_applied,
            ]);
        });

        OtherExpense::where('user_id', $user->id)->latest()->take(5)->get()->each(function ($item) use ($recentActivity) {
            $recentActivity->push([
                'text' => 'Recorded other expense of ₹' . number_format($item->amount, 2),
                'date' => $item->expense_date ?? $item->created_at,
            ]);
        });

        CropSale::where('user_id', $user->id)->latest('sale_date')->take(5)->get()->each(function ($item) use ($recentActivity) {
            $recentActivity->push([
                'text' => 'Sold crop for ₹' . number_format($item->total_income, 2) . ' to ' . $item->buyer_name,
                'date' => $item->sale_date,
            ]);
        });

        $recentActivity = $recentActivity
            ->sortByDesc('date')
            ->take(6)
            ->pluck('text');

        $lands = Land::where('user_id', $user->id)
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->get();

        $weatherByLand = $lands->map(function ($land) use ($weatherService) {
            return [
                'land_name' => $land->name,
                'weather' => $weatherService->getWeatherForLocation($land->location),
            ];
        });

        return view('dashboard', compact(
            'totalIncome', 'totalExpense', 'netProfit', 'recentActivity', 'weatherByLand'
        ));
    }
}