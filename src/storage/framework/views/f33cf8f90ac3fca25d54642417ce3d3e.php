<?php if (isset($component)) { $__componentOriginal1dfbd81375060c202bfc9ecfa0f94aec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1dfbd81375060c202bfc9ecfa0f94aec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.user.layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('user.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Categories')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <form method="GET" action="<?php echo e(route('user.categories.index')); ?>">
                    <div class="flex">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                               placeholder="Search categories..."
                               class="rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 w-full">
                        <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <?php if($categories->isEmpty()): ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 text-center">
                        <p class="text-gray-500">No categories found.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">
                                    <a href="<?php echo e(route('user.categories.show', $category)); ?>" class="hover:text-indigo-600">
                                        <?php echo e($category->name); ?>

                                    </a>
                                </h3>
                                <?php if($category->description): ?>
                                    <p class="text-gray-600 mb-4"><?php echo e(Str::limit($category->description, 100)); ?></p>
                                <?php endif; ?>
                                <a href="<?php echo e(route('user.categories.show', $category)); ?>"
                                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-6">
                    <?php echo e($categories->appends(['search' => request('search')])->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1dfbd81375060c202bfc9ecfa0f94aec)): ?>
<?php $attributes = $__attributesOriginal1dfbd81375060c202bfc9ecfa0f94aec; ?>
<?php unset($__attributesOriginal1dfbd81375060c202bfc9ecfa0f94aec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1dfbd81375060c202bfc9ecfa0f94aec)): ?>
<?php $component = $__componentOriginal1dfbd81375060c202bfc9ecfa0f94aec; ?>
<?php unset($__componentOriginal1dfbd81375060c202bfc9ecfa0f94aec); ?>
<?php endif; ?>
<?php /**PATH /var/www/laravel-project/resources/views/user/categories/index.blade.php ENDPATH**/ ?>