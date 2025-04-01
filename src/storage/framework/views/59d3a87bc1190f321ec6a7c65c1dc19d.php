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
            <?php echo e(__('Borrowing Details')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Borrowing Details</h1>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success mb-6">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="space-y-4 mb-8">
                        <div class="border-b pb-4">
                            <h3 class="text-sm font-medium text-gray-500">User</h3>
                            <p class="mt-1 text-lg text-gray-900"><?php echo e($borrowing->user->name); ?></p>
                        </div>

                        <div class="border-b pb-4">
                            <h3 class="text-sm font-medium text-gray-500">Book</h3>
                            <p class="mt-1 text-lg text-gray-900"><?php echo e($borrowing->book->title); ?></p>
                        </div>

                        <div class="border-b pb-4">
                            <h3 class="text-sm font-medium text-gray-500">Borrowed Date</h3>
                            <p class="mt-1 text-lg text-gray-900">
                                <?php if($borrowing->borrowed_at): ?>
                                    <?php echo e(\Carbon\Carbon::parse($borrowing->borrowed_at)->format('d-m-Y')); ?>

                                <?php else: ?>
                                    <span class="text-red-500">Not specified</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="border-b pb-4">
                            <h3 class="text-sm font-medium text-gray-500">Due Date</h3>
                            <p class="mt-1 text-lg text-gray-900">
                                <?php if($borrowing->due_date): ?>
                                    <?php echo e(\Carbon\Carbon::parse($borrowing->due_date)->format('d-m-Y')); ?>

                                <?php else: ?>
                                    <span class="text-red-500">Not specified</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="border-b pb-4">
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1 text-lg text-gray-900"><?php echo e(ucfirst($borrowing->status)); ?></p>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <a href="<?php echo e(route('admin.borrowings.edit', $borrowing->id)); ?>"
                           class="text-blue-500 hover:text-blue-700">
                            Edit
                        </a>
                        <span>|</span>
                        <form action="<?php echo e(route('admin.borrowings.destroy', $borrowing->id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                    class="text-red-500 hover:text-red-700"
                                    onclick="return confirm('Are you sure you want to delete this borrowing?')">
                                Delete
                            </button>
                        </form>
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
<?php /**PATH /var/www/laravel-project/resources/views/admin/borrowings/show.blade.php ENDPATH**/ ?>