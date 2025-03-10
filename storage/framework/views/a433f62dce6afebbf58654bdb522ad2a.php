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
            <?php echo e(__('Admin Dashboard')); ?>  <!-- Заголовок страницы панели администратора -->
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Приветственное сообщение -->
                <div class="p-6">
                    <h1 class="text-4xl font-semibold mb-4 text-gray-800">Welcome, Admin!</h1>  <!-- Приветствие администратору -->
                    <p class="text-lg text-gray-600">This is the dashboard where you can manage all aspects of the application.</p>  <!-- Описание -->

                    <!-- Дополнительная информация или действия для администратора -->
                    <div class="mt-6">
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="inline-block bg-blue-600 text-blue-700 px-6 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-all duration-300">
                            Manage Users
                        </a>
                        <a href="<?php echo e(route('admin.books.index')); ?>" class="inline-block bg-green-600 text-blue px-6 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-all duration-300 ml-4">
                            Manage Books
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
<?php /**PATH /Users/bekzatsaparbekov/LaravelProject/LaravelProject/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>