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
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-medium text-gray-900">My Reservations</h2>
                <a href="<?php echo e(route('user.reservations.create')); ?>"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-700 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150">
                    New Reservation
                </a>
            </div>

            <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    <p><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>

            <?php if($reservations->isEmpty()): ?>
                <p class="text-gray-500">You have no reservations yet.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($reservation->book->title); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($reservation->book->author); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($reservation->reservation_date->format('M d, Y')); ?><br>
                                    to <?php echo e($reservation->expiration_date->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            <?php echo e($reservation->status === 'approved' ? 'bg-green-100 text-green-800' :
                                            ($reservation->status === 'rejected' ? 'bg-red-100 text-red-800' :
                                            ($reservation->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'))); ?>">
                                            <?php echo e(ucfirst($reservation->status)); ?>

                                        </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?php echo e(route('user.reservations.show', $reservation->id)); ?>"
                                           class="text-blue-600 hover:text-blue-900">Details</a>
                                        <?php if($reservation->status === 'pending'): ?>
                                            <form action="<?php echo e(route('user.reservations.cancel', $reservation->id)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit"
                                                        class="text-yellow-600 hover:text-yellow-900"
                                                        onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                                    Cancel
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <?php echo e($reservations->links()); ?>

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
<?php /**PATH /var/www/laravel-project/resources/views/user/reservations/index.blade.php ENDPATH**/ ?>