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
            <h2 class="text-lg font-medium text-gray-900 mb-6">New Book Reservation</h2>

            <form method="POST" action="<?php echo e(route('user.reservations.store')); ?>">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Book Selection -->
                    <div>
                        <label for="book_id" class="block text-sm font-medium text-gray-700">Book</label>
                        <select id="book_id" name="book_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select Book</option>
                            <?php $__currentLoopData = $availableBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($book->id); ?>" <?php echo e(old('book_id') == $book->id ? 'selected' : ''); ?>>
                                    <?php echo e($book->title); ?> (<?php echo e($book->author); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['book_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Reservation Date -->
                    <div>
                        <label for="reservation_date" class="block text-sm font-medium text-gray-700">Reservation Date</label>
                        <input type="date" id="reservation_date" name="reservation_date"
                               value="<?php echo e(old('reservation_date', now()->format('Y-m-d'))); ?>"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <?php $__errorArgs = ['reservation_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Special Requests (Optional)</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo e(old('notes')); ?></textarea>
                        <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="<?php echo e(route('user.reservations.index')); ?>" class="mr-4 text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-700 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150">
                        Submit Reservation
                    </button>
                </div>
            </form>
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
<?php /**PATH /var/www/laravel-project/resources/views/user/reservations/create.blade.php ENDPATH**/ ?>