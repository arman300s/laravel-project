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
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-6">Reservation Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-md font-medium text-gray-900">Book Information</h3>
                    <dl class="mt-4 space-y-4">
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Title</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e($reservation->book->title); ?></dd>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Author</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e($reservation->book->author); ?></dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-md font-medium text-gray-900">Reservation Details</h3>
                    <dl class="mt-4 space-y-4">
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Reservation Date</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e($reservation->reservation_date->format('M d, Y')); ?></dd>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Expiration Date</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e($reservation->expiration_date->format('M d, Y')); ?></dd>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?php echo e($reservation->status === 'approved' ? 'bg-green-100 text-green-800' :
                                    ($reservation->status === 'rejected' ? 'bg-red-100 text-red-800' :
                                    ($reservation->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'))); ?>">
                                    <?php echo e(ucfirst($reservation->status)); ?>

                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <?php if($reservation->notes): ?>
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h3 class="text-md font-medium text-gray-900">Your Notes</h3>
                    <div class="mt-2 text-sm text-gray-500">
                        <?php echo e($reservation->notes); ?>

                    </div>
                </div>
            <?php endif; ?>

            <div class="mt-6">
                <?php if($reservation->status === 'pending'): ?>
                    <form action="<?php echo e(route('user.reservations.cancel', $reservation->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none transition ease-in-out duration-150"
                                onclick="return confirm('Are you sure you want to cancel this reservation?')">
                            Cancel Reservation
                        </button>
                    </form>
                <?php endif; ?>
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
<?php /**PATH /var/www/laravel-project/resources/views/user/reservations/show.blade.php ENDPATH**/ ?>