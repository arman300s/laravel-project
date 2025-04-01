<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Admin Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-4 text-gray-800">Welcome, Admin!</h1>
                    <p class="text-gray-600 mb-6">This is the dashboard where you can manage all aspects of the application.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <a href="<?php echo e(route('admin.users.index')); ?>"
                           class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition duration-300">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                    👤
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-800">Manage Users</h3>
                                    <p class="text-gray-500 text-sm">View and manage all system users</p>
                                </div>
                            </div>
                        </a>

                        <a href="<?php echo e(route('admin.books.index')); ?>"
                           class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition duration-300">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                    📚
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-800">Manage Books</h3>
                                    <p class="text-gray-500 text-sm">View and manage all books</p>
                                </div>
                            </div>
                        </a>

                        <a href="<?php echo e(route('admin.book.views')); ?>"
                           class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition duration-300">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                    📊
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-800">Book Views</h3>
                                    <p class="text-gray-500 text-sm">View book statistics and analytics</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
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
<?php endif; ?>
<?php /**PATH /var/www/laravel-project/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>