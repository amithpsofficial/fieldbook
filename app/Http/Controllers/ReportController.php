<?php

namespace App\Http\Controllers;

use App\Models\LabourExpense;
use App\Models\FertilizerApplication;
use App\Models\OtherExpense;
use App\Models\CropSale;
use App\Models\Setting;
use App\Models\Land;
use App\Services\WeatherService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $setting = Setting::where('user_id', $userId)->first();
        $startMonth = $setting->season_start_month ?? 1;

        $now = now();
        if ($now->month >= $startMonth) {
            $seasonStart = $now->copy()->month($startMonth)->startOfMonth();
        } else {
            $seasonStart = $now->copy()->subYear()->month($startMonth)->startOfMonth();
        }
        $seasonEnd = $seasonStart->copy()->addYear()->subDay();

        $totalIncome = CropSale::where('user_id', $userId)
            ->whereBetween('sale_date', [$seasonStart, $seasonEnd])
            ->sum('total_income');

        $labourTotal = LabourExpense::where('user_id', $userId)
            ->whereBetween('expense_date', [$seasonStart, $seasonEnd])
            ->sum('total_amount');

        $fertilizerTotal = FertilizerApplication::where('user_id', $userId)
            ->whereBetween('date_applied', [$seasonStart, $seasonEnd])
            ->sum('cost');

        $otherTotal = OtherExpense::where('user_id', $userId)
            ->whereBetween('expense_date', [$seasonStart, $seasonEnd])
            ->sum('amount');

        $totalExpense = $labourTotal + $fertilizerTotal + $otherTotal;
        $netProfit = $totalIncome - $totalExpense;

        $monthExpr = DB::connection()->getDriverName() === 'pgsql'
            ? 'EXTRACT(MONTH FROM sale_date) as month'
            : 'MONTH(sale_date) as month';

        $monthlySales = CropSale::where('user_id', $userId)
            ->whereBetween('sale_date', [$seasonStart, $seasonEnd])
            ->selectRaw("{$monthExpr}, SUM(total_income) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Year-on-year profit comparison: last 5 seasons including the current one
        $seasonsData = collect();
        for ($i = 4; $i >= 0; $i--) {
            $start = $seasonStart->copy()->subYears($i);
            $end = $start->copy()->addYear()->subDay();

            $label = $start->year === $end->year
                ? (string) $start->year
                : $start->format('Y') . '-' . $end->format('y');

            $income = CropSale::where('user_id', $userId)
                ->whereBetween('sale_date', [$start, $end])->sum('total_income');

            $labour = LabourExpense::where('user_id', $userId)
                ->whereBetween('expense_date', [$start, $end])->sum('total_amount');

            $fert = FertilizerApplication::where('user_id', $userId)
                ->whereBetween('date_applied', [$start, $end])->sum('cost');

            $other = OtherExpense::where('user_id', $userId)
                ->whereBetween('expense_date', [$start, $end])->sum('amount');

            $expense = $labour + $fert + $other;

            $seasonsData->push([
                'label'      => $label,
                'income'     => (float) $income,
                'expense'    => (float) $expense,
                'profit'     => (float) ($income - $expense),
                'is_current' => $i === 0,
            ]);
        }

        // Weather: one card per land that has a location set
        $weatherService = new WeatherService();

        $landsWeather = Land::where('user_id', $userId)
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->get()
            ->map(function ($land) use ($weatherService) {
                return [
                    'land_name' => $land->name,
                    'weather'   => $weatherService->getWeatherForLocation($land->location),
                ];
            })
            ->filter(fn ($item) => $item['weather'] !== null)
            ->values();

        // ---- Filterable Expense Overview (weekly / monthly / season yearly / date range) ----
        $expensePeriod = $request->input('expense_period', 'season');
        if (!in_array($expensePeriod, ['weekly', 'monthly', 'season', 'range'])) {
            $expensePeriod = 'season';
        }

        $expenseType = $request->input('expense_type', 'all');
        if (!in_array($expenseType, ['all', 'labour', 'fertilizer', 'other'])) {
            $expenseType = 'all';
        }

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $expenseOverview = $this->buildExpenseOverview(
            $userId, $expensePeriod, $expenseType, $dateFrom, $dateTo, $seasonStart, $seasonEnd
        );

        return view('reports', compact(
            'totalIncome', 'totalExpense', 'netProfit',
            'labourTotal', 'fertilizerTotal', 'otherTotal',
            'seasonStart', 'seasonEnd', 'monthlySales', 'seasonsData',
            'landsWeather',
            'expensePeriod', 'expenseType', 'dateFrom', 'dateTo', 'expenseOverview'
        ));
    }

    /**
     * Build the filterable expense overview table: rows bucketed by the
     * selected period, optionally restricted to a single expense type.
     */
    private function buildExpenseOverview($userId, $period, $type, $dateFrom, $dateTo, $seasonStart, $seasonEnd)
    {
        switch ($period) {
            case 'weekly':
                $to = now()->endOfDay();
                $from = now()->copy()->subWeeks(7)->startOfWeek();
                break;
            case 'monthly':
                $to = now()->endOfMonth();
                $from = now()->copy()->subMonths(5)->startOfMonth();
                break;
            case 'range':
                $from = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : now()->copy()->subMonth()->startOfDay();
                $to = $dateTo ? Carbon::parse($dateTo)->endOfDay() : now()->endOfDay();
                break;
            case 'season':
            default:
                $from = $seasonStart;
                $to = $seasonEnd;
                break;
        }

        $records = collect();

        if (in_array($type, ['all', 'labour'])) {
            $records = $records->merge(
                LabourExpense::where('user_id', $userId)
                    ->whereBetween('expense_date', [$from, $to])
                    ->get(['expense_date', 'total_amount'])
                    ->map(fn ($r) => [
                        'date'   => Carbon::parse($r->expense_date),
                        'amount' => (float) $r->total_amount,
                        'type'   => 'Labour',
                    ])
            );
        }

        if (in_array($type, ['all', 'fertilizer'])) {
            $records = $records->merge(
                FertilizerApplication::where('user_id', $userId)
                    ->whereBetween('date_applied', [$from, $to])
                    ->get(['date_applied', 'cost'])
                    ->map(fn ($r) => [
                        'date'   => Carbon::parse($r->date_applied),
                        'amount' => (float) $r->cost,
                        'type'   => 'Fertilizer / Pesticide',
                    ])
            );
        }

        if (in_array($type, ['all', 'other'])) {
            $records = $records->merge(
                OtherExpense::where('user_id', $userId)
                    ->whereBetween('expense_date', [$from, $to])
                    ->get(['expense_date', 'amount'])
                    ->map(fn ($r) => [
                        'date'   => Carbon::parse($r->expense_date),
                        'amount' => (float) $r->amount,
                        'type'   => 'Other',
                    ])
            );
        }

        $groupKey = function ($record) use ($period) {
            return match ($period) {
                'weekly' => $record['date']->copy()->startOfWeek()->format('Y-m-d'),
                'monthly', 'range' => $record['date']->format('Y-m'),
                'season' => 'season',
                default => $record['date']->format('Y-m'),
            };
        };

        $grouped = $records->groupBy($groupKey)->map(function ($group, $key) use ($period) {
            $label = match ($period) {
                'weekly' => Carbon::parse($key)->format('M j') . ' - ' . Carbon::parse($key)->copy()->endOfWeek()->format('M j'),
                'monthly', 'range' => Carbon::parse($key . '-01')->format('F Y'),
                'season' => 'Current Season',
                default => $key,
            };

            return [
                'label'     => $label,
                'total'     => $group->sum('amount'),
                'breakdown' => [
                    'Labour'                 => $group->where('type', 'Labour')->sum('amount'),
                    'Fertilizer / Pesticide' => $group->where('type', 'Fertilizer / Pesticide')->sum('amount'),
                    'Other'                  => $group->where('type', 'Other')->sum('amount'),
                ],
            ];
        })->sortKeys();

        // For weekly/monthly, fill in empty periods so the table doesn't skip gaps
        if (in_array($period, ['weekly', 'monthly'])) {
            $grouped = $this->fillMissingPeriods($grouped, $period, $from, $to);
        }

        return [
            'rows'  => $grouped->values(),
            'total' => $records->sum('amount'),
            'from'  => $from,
            'to'    => $to,
        ];
    }

    /**
     * Ensure weekly/monthly tables show every period in the window,
     * even ones with zero expenses, instead of silently skipping them.
     */
    private function fillMissingPeriods($grouped, $period, $from, $to)
    {
        $filled = collect();
        $cursor = $from->copy();

        while ($cursor->lte($to)) {
            $key = $period === 'weekly'
                ? $cursor->copy()->startOfWeek()->format('Y-m-d')
                : $cursor->format('Y-m');

            if (!$filled->has($key)) {
                $filled->put($key, $grouped->get($key, [
                    'label' => $period === 'weekly'
                        ? $cursor->copy()->startOfWeek()->format('M j') . ' - ' . $cursor->copy()->endOfWeek()->format('M j')
                        : $cursor->format('F Y'),
                    'total' => 0,
                    'breakdown' => ['Labour' => 0, 'Fertilizer / Pesticide' => 0, 'Other' => 0],
                ]));
            }

            $cursor = $period === 'weekly' ? $cursor->addWeek() : $cursor->addMonth();
        }

        return $filled;
    }
}