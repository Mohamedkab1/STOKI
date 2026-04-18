<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Stoki')); ?> - Connexion</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <!-- Thème CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('css/theme.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('css/components.css')); ?>">
        
        <!-- Détection immédiate du thème -->
        <script src="<?php echo e(asset('js/theme.js')); ?>"></script>
    </head>
    <body class="font-sans antialiased" style="background: var(--bg-primary); color: var(--text-primary);">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg" style="background: var(--accent);">
                        <i class="fas fa-cubes text-2xl text-white"></i>
                    </div>
                    <span class="text-2xl font-black tracking-tight" style="color: var(--text-primary);">STOKI<span style="color: var(--accent);">ERP</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden sm:rounded-2xl shadow-lg" style="background: var(--bg-surface); border: 1px solid var(--border-color);">
                <?php echo e($slot); ?>

            </div>
        </div>
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </body>
</html>
<?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views/layouts/guest.blade.php ENDPATH**/ ?>