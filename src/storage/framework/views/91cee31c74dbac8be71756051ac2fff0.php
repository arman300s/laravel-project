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
            <?php echo e(__('Books')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-3xl font-semibold mb-6 text-gray-900">Available Books</h1>
                    <p class="text-gray-600 mb-6">Browse through a list of books available for borrowing. Click "Borrow" to get started.</p>
                </div>

                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition-all ease-in-out duration-300">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-3">
                                <span class="inline-block mr-2">📗</span><?php echo e($book->title); ?>

                            </h2>
                            <p class="text-gray-700 mb-4"><?php echo e(Str::limit($book->description, 150)); ?></p>
                            <div class="flex justify-between items-center">
                                <a href="<?php echo e(route('admin.books.show', $book->id)); ?>" class="text-blue-500 hover:text-blue-700 font-semibold">
                                    More details
                                </a>
                                <a href="<?php echo e(route('books.borrow', $book->id)); ?>"
                                   class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-700 transition duration-200">
                                    📚 <strong>Borrow</strong>
                                </a>
                            </div>
                            <div class="mt-4">
                                <a href="<?php echo e(route('books.download', $book->id)); ?>"
                                   class="text-black font-bold text-lg hover:underline mt-2 block">
                                    📥 <strong>Download PDF</strong>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH /var/www/laravel-project/resources/views/user/books/index.blade.php ENDPATH**/ ?>