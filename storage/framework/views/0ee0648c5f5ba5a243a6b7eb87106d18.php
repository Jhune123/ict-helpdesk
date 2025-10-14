<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center" 
      style="background-image: url('<?php echo e(asset('images/bg-login.jpg')); ?>');">

    <div class="w-full max-w-md px-6 py-8 bg-white/90 backdrop-blur-md shadow-lg rounded-2xl">
        <?php echo e($slot); ?>

    </div>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\ict-helpdesk\resources\views/layouts/guest.blade.php ENDPATH**/ ?>