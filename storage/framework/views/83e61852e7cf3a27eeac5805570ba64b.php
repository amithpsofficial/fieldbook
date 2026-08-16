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
     <?php $__env->slot('header', null, []); ?> Harvest Stock <?php $__env->endSlot(); ?>

    <div class="space-y-6">

        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Track green and processed crop stock.</p>
            <a href="<?php echo e(route('crop-stocks.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 shadow-sm hover:shadow-md transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add Stock
            </a>
        </div>

        <?php if($summary->count()): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <!-- Pie chart: stock composition by crop -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-1">
                <h3 class="text-base font-semibold text-gray-800 mb-1">Stock Composition</h3>
                <p class="text-xs text-gray-400 mb-4">Share of total stock by crop</p>
                <div class="h-56">
                    <canvas id="cropStockPieChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cropName => $totals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full chart-color-dot" data-index="<?php echo e($loop->index); ?>"></span>
                                <?php echo e($cropName); ?>

                            </span>
                            <span class="font-medium text-gray-800"><?php echo e($totals['percentage']); ?>%</span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Per-crop breakdown cards -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cropName => $totals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-base font-semibold text-gray-800"><?php echo e($cropName); ?></h3>
                            <span class="text-xs font-semibold text-gray-500 bg-gray-50 px-2 py-1 rounded-full"><?php echo e($totals['percentage']); ?>%</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-green-700 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m0-18c-2 2-6 2-8 0m8 0c2 2 6 2 8 0" /></svg>
                                Green
                            </span>
                            <span class="text-sm font-semibold text-gray-800"><?php echo e(number_format($totals['green'], 2)); ?> kg</span>
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-sm text-amber-700 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                Processed
                            </span>
                            <span class="text-sm font-semibold text-gray-800"><?php echo e(number_format($totals['processed'], 2)); ?> kg</span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

        </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
                            <th class="px-6 py-3 font-medium">Date</th>
                            <th class="px-6 py-3 font-medium">Crop</th>
                            <th class="px-6 py-3 font-medium">Land</th>
                            <th class="px-6 py-3 font-medium">Type</th>
                            <th class="px-6 py-3 font-medium">Quantity (kg)</th>
                            <th class="px-6 py-3 font-medium">Notes</th>
                            <th class="px-6 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 text-gray-700"><?php echo e($stock->stock_date->format('d M Y')); ?></td>
                                <td class="px-6 py-4 text-gray-700"><?php echo e($stock->crop->name ?? '-'); ?></td>
                                <td class="px-6 py-4 text-gray-700"><?php echo e($stock->land->name ?? 'All Lands'); ?></td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?php echo e($stock->type === 'green' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700'); ?>">
                                        <?php echo e(ucfirst($stock->type)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium <?php echo e($stock->quantity_kg < 0 ? 'text-red-600' : 'text-gray-800'); ?>">
                                    <?php echo e(number_format($stock->quantity_kg, 2)); ?>

                                </td>
                                <td class="px-6 py-4 text-gray-500"><?php echo e($stock->notes ?? '-'); ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?php echo e(route('crop-stocks.edit', $stock)); ?>" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-150">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="<?php echo e(route('crop-stocks.destroy', $stock)); ?>" method="POST" onsubmit="return confirm('Delete this entry?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    No stock entries yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <?php if($summary->count()): ?>
    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const cropLabels = <?php echo json_encode($summary->keys(), 15, 512) ?>;
        const cropTotals = <?php echo json_encode($summary->map(fn($t) => $t['total'])->values(), 15, 512) ?>;
        const palette = ['#22c55e', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6', '#14b8a6', '#f43f5e', '#84cc16'];

        // Color the little dots in the legend list to match the chart slices
        document.querySelectorAll('.chart-color-dot').forEach(dot => {
            const i = parseInt(dot.dataset.index, 10);
            dot.style.backgroundColor = palette[i % palette.length];
        });

        new Chart(document.getElementById('cropStockPieChart'), {
            type: 'doughnut',
            data: {
                labels: cropLabels,
                datasets: [{
                    data: cropTotals,
                    backgroundColor: cropLabels.map((_, i) => palette[i % palette.length]),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: (item) => `${item.label}: ${Number(item.parsed).toLocaleString('en-IN')} kg`
                        }
                    }
                }
            }
        });
    </script>
    <?php $__env->stopPush(); ?>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laravel-projects\fieldbook\resources\views/crop-stocks/index.blade.php ENDPATH**/ ?>