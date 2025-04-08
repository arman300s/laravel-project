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
            <?php echo e($category->name); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900"><?php echo e($category->name); ?></h3>
                        <p class="mt-1 text-sm text-gray-600">Slug: <?php echo e($category->slug); ?></p>
                    </div>

                    <?php if($category->description): ?>
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900">Description</h4>
                            <p class="mt-1 text-gray-600"><?php echo e($category->description); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="flex items-center">
                        <a href="<?php echo e(route('user.categories.index')); ?>"
                           class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Categories
                        </a>
                    </div>
                </div>
            </div>
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
<?php /**PATH /var/www/laravel-project/resources/views/user/categories/show.blade.php ENDPATH**/ ?>