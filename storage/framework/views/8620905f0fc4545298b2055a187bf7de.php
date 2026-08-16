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
     <?php $__env->slot('header', null, []); ?> Dashboard <?php $__env->endSlot(); ?>

    <div class="space-y-6">

        <!-- Greeting -->
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Good day, <?php echo e(explode(' ', Auth::user()->name)[0]); ?> 👋</h1>
            <p class="text-sm text-gray-500 mt-1">Here's how your farm is doing this season.</p>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500">Net Profit</span>
                    <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3v-6m-3 6v-3m9 3H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2v11a2 2 0 01-2 2z" /></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold <?php echo e($netProfit >= 0 ? 'text-green-600' : 'text-red-600'); ?>">₹ <?php echo e(number_format($netProfit, 2)); ?></p>
                <p class="text-xs text-gray-400 mt-1">This season</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500">Total Income</span>
                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" /></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">₹ <?php echo e(number_format($totalIncome, 2)); ?></p>
                <p class="text-xs text-gray-400 mt-1">From crop sales</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500">Total Expense</span>
                    <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" /></svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800">₹ <?php echo e(number_format($totalExpense, 2)); ?></p>
                <p class="text-xs text-gray-400 mt-1">Labour, fertilizer & other costs</p>
            </div>

        </div>

        <!-- Weather Card -->
        <?php if($weatherByLand->isNotEmpty()): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 00-9.78 1.555A4 4 0 003 15z" /></svg>
                Weather Updates
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php $__currentLoopData = $weatherByLand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-gray-100 rounded-xl p-4 hover:shadow-md transition-shadow duration-150">
                    <p class="font-medium text-gray-800"><?php echo e($item['land_name']); ?></p>
                    <?php if($item['weather']): ?>
                        <p class="text-sm text-gray-500"><?php echo e($item['weather']['location']); ?></p>
                        <div class="flex items-center mt-2">
                            <?php if($item['weather']['icon']): ?>
                                <img src="https://openweathermap.org/img/wn/<?php echo e($item['weather']['icon']); ?>@2x.png" alt="weather icon" class="w-12 h-12">
                            <?php endif; ?>
                            <div class="ml-2">
                                <p class="text-xl font-semibold text-gray-800"><?php echo e(round($item['weather']['temperature'])); ?>°C</p>
                                <p class="text-xs text-gray-500 capitalize"><?php echo e($item['weather']['description']); ?></p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                            Humidity: <?php echo e($item['weather']['humidity']); ?>% | Wind: <?php echo e($item['weather']['wind_speed']); ?> m/s
                        </p>
                    <?php else: ?>
                        <p class="text-sm text-gray-400 mt-2">Weather unavailable right now.</p>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="<?php echo e(url('labour-expenses/create')); ?>" class="flex items-center gap-3 border border-gray-200 rounded-xl p-4 hover:bg-green-50 hover:border-green-200 hover:shadow-md transition-all duration-150">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Add Labour</span>
                </a>
                <a href="<?php echo e(url('fertilizer-applications/create')); ?>" class="flex items-center gap-3 border border-gray-200 rounded-xl p-4 hover:bg-green-50 hover:border-green-200 hover:shadow-md transition-all duration-150">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3h6v4l4 8a2 2 0 01-1.8 3H6.8A2 2 0 015 15l4-8V3z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8" /></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Add Fertilizer</span>
                </a>
                <a href="<?php echo e(url('crop-sales/create')); ?>" class="flex items-center gap-3 border border-gray-200 rounded-xl p-4 hover:bg-green-50 hover:border-green-200 hover:shadow-md transition-all duration-150">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 2v8m0 0v2m0-2c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Sell Crop</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Recent Activity</h3>
            <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center gap-3 py-3 border-b border-gray-100 last:border-0">
                    <div class="w-2 h-2 rounded-full bg-green-500 shrink-0"></div>
                    <span class="text-sm text-gray-700"><?php echo e($activity); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-gray-400 text-center py-6">No recent activity yet. Start by adding a labour expense or selling a crop.</p>
            <?php endif; ?>
        </div>

    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laravel-projects\fieldbook\resources\views/dashboard.blade.php ENDPATH**/ ?>