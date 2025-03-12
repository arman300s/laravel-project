<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            <?php echo e(__('Update Avatar')); ?>

        </h2>
    </header>

    <!-- Display Current Avatar -->
    <div class="mt-4">
        <img src="<?php echo e(auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('default-avatar.png')); ?>"
             alt="User Avatar" class="rounded-full border shadow-md" width="150">
    </div>

    <?php if(session('success')): ?>
        <p class="mt-2 text-green-600"><?php echo e(session('success')); ?></p>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <p class="mt-2 text-red-600"><?php echo e(implode(', ', $errors->all())); ?></p>
    <?php endif; ?>

    <!-- Change Avatar Form -->
    <form action="<?php echo e(route('profile.avatar.update')); ?>" method="POST" enctype="multipart/form-data" class="mt-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="mb-4">
            <label for="avatar" class="block font-medium text-gray-700">Choose a new avatar:</label>
            <input type="file" class="mt-2 border p-2 w-full" name="avatar" id="avatar" accept="image/*" required>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Upload Avatar
        </button>
    </form>

    <!-- Delete Avatar Form -->
    <?php if(auth()->user()->avatar): ?>
        <form action="<?php echo e(route('profile.avatar.delete')); ?>" method="POST" class="mt-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="px-4 py-2 bg-red-600 text- rounded hover:bg-red-700">
                Delete Avatar
            </button>
        </form>
    <?php endif; ?>
</section>
<?php /**PATH /Users/bekzatsaparbekov/LaravelProject/LaravelProject/resources/views/profile/partials/avatar.blade.php ENDPATH**/ ?>