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
            <?php echo e(__('Create New Borrowing')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Add New Borrowing</h1>

                    <form action="<?php echo e(route('admin.borrowings.store')); ?>" method="POST" class="space-y-6">
                        <?php echo csrf_field(); ?>

                        <!-- User Selection -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User</label>
                            <select id="user_id" name="user_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Book Selection -->
                        <div>
                            <label for="book_id" class="block text-sm font-medium text-gray-700 mb-2">Book</label>
                            <select id="book_id" name="book_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                <?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($book->id); ?>"><?php echo e($book->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Borrowed Date -->
                        <div>
                            <label for="borrowed_at" class="block text-sm font-medium text-gray-700 mb-2">Borrowed Date</label>
                            <input type="date" id="borrowed_at" name="borrowed_at"
                                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                            <input type="date" id="due_date" name="due_date"
                                   value="<?php echo e(old('due_date')); ?>"
                                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                                <option value="pending" <?php echo e(old('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                <option value="overdue" <?php echo e(old('status') == 'overdue' ? 'selected' : ''); ?>>Overdue</option>
                                <option value="returned" <?php echo e(old('status') == 'returned' ? 'selected' : ''); ?>>Returned</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary font-semibold">
                                Create Borrowing
                            </button>
                        </div>
                    </form>
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
<?php /**PATH /var/www/laravel-project/resources/views/admin/borrowings/create.blade.php ENDPATH**/ ?>