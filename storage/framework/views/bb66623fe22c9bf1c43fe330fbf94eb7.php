<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> Reports <?php $__env->endSlot(); ?>

    <div class="space-y-6">

        <?php if($landsWeather->isNotEmpty()): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <?php $__currentLoopData = $landsWeather; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                    <img src="https://openweathermap.org/img/wn/<?php echo e($item['weather']['icon']); ?>@2x.png"
                         alt="<?php echo e($item['weather']['description']); ?>"
                         class="w-14 h-14 -my-2 -ml-1 shrink-0">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider truncate"><?php echo e($item['land_name']); ?></p>
                        <p class="text-2xl font-bold text-gray-900 tracking-tight"><?php echo e(round($item['weather']['temperature'])); ?>°C</p>
                        <p class="text-xs text-gray-500 capitalize truncate"><?php echo e($item['weather']['description']); ?> · <?php echo e($item['weather']['location']); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Profit Overview</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Season
                    <span class="font-medium text-gray-700"><?php echo e($seasonStart->format('M Y')); ?></span> –
                    <span class="font-medium text-gray-700"><?php echo e($seasonEnd->format('M Y')); ?></span>
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-50 text-green-700 text-xs font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Current Season
            </span>
        </div>

        <!-- Profit Overview: Season Yearly -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-150">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Income</span>
                    <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" /></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900 tracking-tight">₹<?php echo e(number_format($totalIncome, 0)); ?></p>
                <p class="text-xs text-gray-400 mt-1.5">This season</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-150">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Expense</span>
                    <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" /></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900 tracking-tight">₹<?php echo e(number_format($totalExpense, 0)); ?></p>
                <p class="text-xs text-gray-400 mt-1.5">This season</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-150">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Net Profit</span>
                    <div class="w-9 h-9 rounded-lg <?php echo e($netProfit >= 0 ? 'bg-blue-50' : 'bg-amber-50'); ?> flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 <?php echo e($netProfit >= 0 ? 'text-blue-600' : 'text-amber-600'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold tracking-tight <?php echo e($netProfit >= 0 ? 'text-blue-600' : 'text-amber-600'); ?>">₹<?php echo e(number_format($netProfit, 0)); ?></p>
                <p class="text-xs text-gray-400 mt-1.5">This season</p>
            </div>
        </div>

        <!-- Profit Comparison: Year on Year -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-1">
                <h3 class="text-base font-semibold text-gray-800">Profit Comparison</h3>
                <span class="text-xs text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">Last 5 seasons</span>
            </div>
            <p class="text-xs text-gray-400 mb-2">Year-on-year income, expense, and profit trend</p>
            <div class="flex items-center gap-5 mb-3">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span>
                    <span class="text-xs text-gray-500">Income</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-rose-400"></span>
                    <span class="text-xs text-gray-500">Expense</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <span class="text-xs text-gray-500">Profit (trend)</span>
                </div>
            </div>
            <div class="h-80">
                <canvas id="profitYoyChart"></canvas>
            </div>
            <?php if(collect($seasonsData)->filter(fn($s) => $s['income'] > 0 || $s['expense'] > 0)->count() <= 1): ?>
                <p class="text-xs text-gray-400 mt-3 text-center">Only one season of data recorded so far — the comparison will fill in as more seasons pass.</p>
            <?php endif; ?>
        </div>

        <!-- Expense Overview: filterable by period + expense type -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" id="expense-overview" x-data="{ period: '<?php echo e($expensePeriod); ?>' }">
            <div class="flex items-center justify-between mb-1">
                <h3 class="text-base font-semibold text-gray-800">Expense Overview</h3>
                <span class="text-xs text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full">
                    <?php echo e(\Carbon\Carbon::parse($expenseOverview['from'])->format('d M Y')); ?> – <?php echo e(\Carbon\Carbon::parse($expenseOverview['to'])->format('d M Y')); ?>

                </span>
            </div>
            <p class="text-xs text-gray-400 mb-4">Filter by period and expense type</p>

            <form method="GET" action="<?php echo e(route('reports')); ?>#expense-overview" class="flex flex-wrap items-end gap-3 mb-5">
                <div>
                    <label class="text-xs font-medium text-gray-500">Period</label>
                    <select name="expense_period" x-model="period" onchange="this.form.submit()" class="mt-1 block border-gray-300 rounded-lg shadow-sm text-sm focus:border-green-500 focus:ring-green-500">
                        <option value="weekly" <?php echo e($expensePeriod === 'weekly' ? 'selected' : ''); ?>>Weekly (last 8 weeks)</option>
                        <option value="monthly" <?php echo e($expensePeriod === 'monthly' ? 'selected' : ''); ?>>Monthly (last 6 months)</option>
                        <option value="season" <?php echo e($expensePeriod === 'season' ? 'selected' : ''); ?>>Season Yearly</option>
                        <option value="range" <?php echo e($expensePeriod === 'range' ? 'selected' : ''); ?>>Custom Date Range</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-500">Expense Type</label>
                    <select name="expense_type" onchange="this.form.submit()" class="mt-1 block border-gray-300 rounded-lg shadow-sm text-sm focus:border-green-500 focus:ring-green-500">
                        <option value="all" <?php echo e($expenseType === 'all' ? 'selected' : ''); ?>>All Types</option>
                        <option value="labour" <?php echo e($expenseType === 'labour' ? 'selected' : ''); ?>>Labour</option>
                        <option value="fertilizer" <?php echo e($expenseType === 'fertilizer' ? 'selected' : ''); ?>>Fertilizer / Pesticide</option>
                        <option value="other" <?php echo e($expenseType === 'other' ? 'selected' : ''); ?>>Other Expenses</option>
                    </select>
                </div>

                <div x-show="period === 'range'" class="flex items-end gap-2">
                    <div>
                        <label class="text-xs font-medium text-gray-500">From</label>
                        <input type="date" name="date_from" value="<?php echo e($dateFrom); ?>" class="mt-1 block border-gray-300 rounded-lg shadow-sm text-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">To</label>
                        <input type="date" name="date_to" value="<?php echo e($dateTo); ?>" class="mt-1 block border-gray-300 rounded-lg shadow-sm text-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <button type="submit" class="text-sm font-medium text-green-700 hover:text-green-800 px-3 py-2 border border-green-200 rounded-lg">Apply</button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            <th class="py-2 pr-4">Period</th>
                            <?php if($expenseType === 'all'): ?>
                                <th class="py-2 pr-4">Labour</th>
                                <th class="py-2 pr-4">Fertilizer / Pesticide</th>
                                <th class="py-2 pr-4">Other</th>
                            <?php endif; ?>
                            <th class="py-2 pr-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $expenseOverview['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-gray-50 last:border-0">
                                <td class="py-2.5 pr-4 text-gray-600"><?php echo e($row['label']); ?></td>
                                <?php if($expenseType === 'all'): ?>
                                    <td class="py-2.5 pr-4 text-gray-700">₹<?php echo e(number_format($row['breakdown']['Labour'], 2)); ?></td>
                                    <td class="py-2.5 pr-4 text-gray-700">₹<?php echo e(number_format($row['breakdown']['Fertilizer / Pesticide'], 2)); ?></td>
                                    <td class="py-2.5 pr-4 text-gray-700">₹<?php echo e(number_format($row['breakdown']['Other'], 2)); ?></td>
                                <?php endif; ?>
                                <td class="py-2.5 pr-4 text-right font-semibold text-gray-800">₹<?php echo e(number_format($row['total'], 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="<?php echo e($expenseType === 'all' ? 5 : 2); ?>" class="py-6 text-center text-gray-400">No expenses recorded in this range.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if(count($expenseOverview['rows']) > 0): ?>
                    <tfoot>
                        <tr class="border-t border-gray-200">
                            <td class="py-2.5 pr-4 font-bold text-gray-800">Grand Total</td>
                            <?php if($expenseType === 'all'): ?>
                                <td colspan="3"></td>
                            <?php endif; ?>
                            <td class="py-2.5 pr-4 text-right font-bold text-red-600">₹<?php echo e(number_format($expenseOverview['total'], 2)); ?></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Expense Breakdown + Monthly Income -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-1">Expense Breakdown</h3>
                <p class="text-xs text-gray-400 mb-4">This season</p>
                <div class="space-y-0.5">
                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-600 flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </span>
                            Labour
                        </span>
                        <span class="text-sm font-semibold text-gray-800">₹<?php echo e(number_format($labourTotal, 2)); ?></span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-600 flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3h6v4l4 8a2 2 0 01-1.8 3H6.8A2 2 0 015 15l4-8V3z" /></svg>
                            </span>
                            Fertilizer / Pesticide
                        </span>
                        <span class="text-sm font-semibold text-gray-800">₹<?php echo e(number_format($fertilizerTotal, 2)); ?></span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-600 flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3v-6m-3 6v-3m9 3H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2v11a2 2 0 01-2 2z" /></svg>
                            </span>
                            Other Expenses
                        </span>
                        <span class="text-sm font-semibold text-gray-800">₹<?php echo e(number_format($otherTotal, 2)); ?></span>
                    </div>
                    <div class="flex items-center justify-between pt-4">
                        <span class="text-sm font-bold text-gray-800">Total</span>
                        <span class="text-sm font-bold text-red-600">₹<?php echo e(number_format($totalExpense, 2)); ?></span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-1">Monthly Income</h3>
                <p class="text-xs text-gray-400 mb-4">This season</p>
                <?php $__empty_1 = true; $__currentLoopData = $monthlySales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                        <span class="text-sm text-gray-600"><?php echo e(date('F', mktime(0,0,0,$month,1))); ?></span>
                        <span class="text-sm font-semibold text-green-700">₹<?php echo e(number_format($total, 2)); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10" /></svg>
                        <p class="text-sm text-gray-400">No income recorded this season.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Keep scroll position on the Expense Overview card after filtering,
        // instead of jumping back to the top of the page.
        if (window.location.hash === '#expense-overview') {
            const target = document.getElementById('expense-overview');
            if (target) {
                window.addEventListener('load', () => {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }
        }

        const seasonsData = <?php echo json_encode($seasonsData, 15, 512) ?>;
        const rupeeFmt = (v) => '₹' + Number(v).toLocaleString('en-IN', { maximumFractionDigits: 0 });

        const ctx = document.getElementById('profitYoyChart').getContext('2d');

        const incomeGradient = ctx.createLinearGradient(0, 0, 0, 320);
        incomeGradient.addColorStop(0, 'rgba(16, 185, 129, 0.95)');
        incomeGradient.addColorStop(1, 'rgba(16, 185, 129, 0.55)');

        const incomeGradientMuted = ctx.createLinearGradient(0, 0, 0, 320);
        incomeGradientMuted.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
        incomeGradientMuted.addColorStop(1, 'rgba(16, 185, 129, 0.15)');

        const expenseGradient = ctx.createLinearGradient(0, 0, 0, 320);
        expenseGradient.addColorStop(0, 'rgba(251, 113, 133, 0.95)');
        expenseGradient.addColorStop(1, 'rgba(251, 113, 133, 0.55)');

        const expenseGradientMuted = ctx.createLinearGradient(0, 0, 0, 320);
        expenseGradientMuted.addColorStop(0, 'rgba(251, 113, 133, 0.35)');
        expenseGradientMuted.addColorStop(1, 'rgba(251, 113, 133, 0.15)');

        const profitFillGradient = ctx.createLinearGradient(0, 0, 0, 320);
        profitFillGradient.addColorStop(0, 'rgba(79, 70, 229, 0.18)');
        profitFillGradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

        const incomeColors = seasonsData.map(s => (s.income === 0 && s.expense === 0) ? incomeGradientMuted : incomeGradient);
        const expenseColors = seasonsData.map(s => (s.income === 0 && s.expense === 0) ? expenseGradientMuted : expenseGradient);

        new Chart(ctx, {
            data: {
                labels: seasonsData.map(s => s.label),
                datasets: [
                    {
                        type: 'bar',
                        label: 'Income',
                        data: seasonsData.map(s => s.income),
                        backgroundColor: incomeColors,
                        borderRadius: 8,
                        maxBarThickness: 28,
                        order: 2,
                    },
                    {
                        type: 'bar',
                        label: 'Expense',
                        data: seasonsData.map(s => s.expense),
                        backgroundColor: expenseColors,
                        borderRadius: 8,
                        maxBarThickness: 28,
                        order: 2,
                    },
                    {
                        type: 'line',
                        label: 'Profit (trend)',
                        data: seasonsData.map(s => s.profit),
                        borderColor: '#4f46e5',
                        backgroundColor: profitFillGradient,
                        borderWidth: 2.5,
                        tension: 0.3,
                        fill: true,
                        spanGaps: true,
                        segment: {
                            borderDash: (c) => {
                                const a = seasonsData[c.p0DataIndex], b = seasonsData[c.p1DataIndex];
                                return (a.income === 0 && a.expense === 0 && b.income === 0 && b.expense === 0) ? [4, 4] : undefined;
                            }
                        },
                        pointRadius: (ctx) => (seasonsData[ctx.dataIndex].is_current ? 6 : 4),
                        pointBackgroundColor: (ctx) => (seasonsData[ctx.dataIndex].is_current ? '#4f46e5' : '#ffffff'),
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        order: 1,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 12,
                        cornerRadius: 10,
                        titleFont: { size: 12, weight: '600' },
                        bodyFont: { size: 12 },
                        bodySpacing: 6,
                        callbacks: {
                            label: (item) => `  ${item.dataset.label}: ${rupeeFmt(item.parsed.y)}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6', drawTicks: false },
                        border: { display: false },
                        ticks: {
                            color: '#9ca3af',
                            font: { size: 11 },
                            padding: 8,
                            callback: (v) => rupeeFmt(v)
                        }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            color: (ctx) => (seasonsData[ctx.index] && seasonsData[ctx.index].is_current ? '#4f46e5' : '#6b7280'),
                            font: (ctx) => ({
                                size: 12,
                                weight: (seasonsData[ctx.index] && seasonsData[ctx.index].is_current) ? '700' : '500'
                            })
                        }
                    }
                }
            }
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laravel-projects\fieldbook\resources\views/reports.blade.php ENDPATH**/ ?>