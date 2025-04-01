<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?> | Admin</title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen">
    <?php echo $__env->make('layouts.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(isset($header)): ?>
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl font-semibold text-gray-900"><?php echo e($header); ?></h1>
            </div>
        </header>
    <?php endif; ?>

    <main class="py-6 px-4 sm:px-6 lg:px-8">
        <?php echo e($slot); ?>

    </main>
</div>
</body>
</html>
<?php /**PATH /var/www/laravel-project/resources/views/components/admin/layout.blade.php ENDPATH**/ ?>