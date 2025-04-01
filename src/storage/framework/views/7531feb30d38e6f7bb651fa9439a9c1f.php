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
            <?php echo e(__('Borrowings Management')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold mb-6">Borrowings List</h1>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success mb-4">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="mb-6">
                        <a href="<?php echo e(route('admin.borrowings.create')); ?>" class="btn btn-primary font-semibold">+ Add New Borrowing</a>
                    </div>

                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">User</th>
                            <th class="px-4 py-2 border">Book</th>
                            <th class="px-4 py-2 border">Borrowed At</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $borrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition-all duration-300">
                                <td class="px-4 py-2 border"><?php echo e($borrowing->id); ?></td>
                                <td class="px-4 py-2 border"><?php echo e($borrowing->user->name); ?></td>
                                <td class="px-4 py-2 border"><?php echo e($borrowing->book->title); ?></td>
                                <td class="px-4 py-2 border">
                                    <?php echo e($borrowing->borrowed_at ? \Carbon\Carbon::parse($borrowing->borrowed_at)->format('d-m-Y') : 'Not specified'); ?>

                                </td>
                                <td class="px-4 py-2 border">
                                    <a href="<?php echo e(route('admin.borrowings.show', $borrowing->id)); ?>" class="text-blue-500">View</a> |
                                    <a href="<?php echo e(route('admin.borrowings.edit', $borrowing->id)); ?>" class="text-yellow-500">Edit</a> |
                                    <form action="<?php echo e(route('admin.borrowings.destroy', $borrowing->id)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-500" onclick="return confirm('Delete this borrowing?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <?php echo e($borrowings->links()); ?>

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
<?php /**PATH /var/www/laravel-project/resources/views/admin/borrowings/index.blade.php ENDPATH**/ ?>