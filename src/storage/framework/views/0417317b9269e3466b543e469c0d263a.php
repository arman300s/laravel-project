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
        <?php echo e(__('View Book')); ?>

    </h2>
 <?php $__env->endSlot(); ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-3xl font-semibold mb-6 text-gray-800"><?php echo e($book->title); ?></h1>


                <p class="text-lg mb-4"><strong class="font-medium text-gray-800">👤Author:</strong> <?php echo e($book->author); ?></p>
                <p class="text-lg"><strong class="font-medium text-gray-800">📚Description:</strong> <?php echo e($book->description); ?></p>


                <div class="mt-6 flex space-x-4">
                    <a href="<?php echo e(route('books.download', $book->id)); ?>"
                       class="inline-flex items-center px-6 py-3  text-black text-lg font-medium rounded-lg shadow-md hover:bg-blue-700 transition">
                        📥 Download PDF
                    </a>

                    <?php if(Auth::check()): ?>
                        <a href="<?php echo e(Auth::user()->role === 'admin' ? route('admin.books.index') : route('user.books.index')); ?>"
                           class="inline-flex items-center px-6 py-3 bg-gray-300 text-gray-800 text-lg font-medium rounded-lg shadow-md ">
                            🔙 Back to list
                        </a>
                    <?php endif; ?>
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
<?php /**PATH /var/www/laravel-project/resources/views/admin/books/show.blade.php ENDPATH**/ ?>