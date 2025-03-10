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
            <?php echo e(__('Admin Books')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Приветственное сообщение -->
                <div class="p-6 border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-4 text-gray-900">📚 Welcome, Admin!</h1>
                    <p class="text-gray-600 mb-4">Here you can manage books efficiently.</p>

                    <!-- Кнопка добавления новой книги -->
                    <a href="<?php echo e(route('admin.books.create')); ?>"
                       class="bg-indigo-600 text-black px-6 py-2 rounded-md font-semibold hover:bg-indigo-700 transition-all duration-300 shadow-md">
                        ➕ Add Book
                    </a>
                </div>

                <!-- Список книг -->
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-gray-100 p-5 rounded-lg shadow-md hover:shadow-lg transition-all">
                            <h2 class="text-xl font-bold mb-2 text-gray-800"><?php echo e($book->title); ?></h2>
                            <p class="text-gray-600 mb-3"><?php echo e(Str::limit($book->description, 150)); ?></p>

                            <!-- Кнопки управления книгами в один ряд с расстоянием между ними -->
                            <div class="flex items-center space-x-8 mt-4">
                                <!-- Edit -->
                                <a href="<?php echo e(route('admin.books.edit', $book->id)); ?>"
                                   class="text-yellow-500 font-semibold hover:text-yellow-600 transition">
                                    ✏️ Edit
                                </a>

                                <!-- Delete -->
                                <form action="<?php echo e(route('admin.books.destroy', $book->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                            class="text-red-500 font-semibold hover:text-red-600 transition">
                                        🗑️ Delete
                                    </button>
                                </form>

                                <!-- Download PDF -->
                                <a href="<?php echo e(route('books.download', $book->id)); ?>"
                                   class="text-blue-500 font-semibold hover:text-blue-600 transition">
                                    📥 Download PDF
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Пагинация -->
                <div class="mt-6 px-6">
                    <?php echo e($books->links()); ?>

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
<?php /**PATH /Users/bekzatsaparbekov/LaravelProject/LaravelProject/resources/views/admin/books/index.blade.php ENDPATH**/ ?>