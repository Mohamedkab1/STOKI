<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="Stoki ERP - Application de gestion de stock professionnelle">
    <title><?php echo $__env->yieldContent('title', 'Stoki'); ?> - Gestion de Stock</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- CSS & JS (Vite / Tailwind) -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap 5 (legacy) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- CSS Personnalisé (Ordre important) -->
    <link rel="stylesheet" href="<?php echo e(asset('css/tailwind-overrides.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/theme.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/components.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/layout.css')); ?>">

    <?php echo $__env->yieldPushContent('styles'); ?>
    
    <!-- Détection immédiate du thème : ajoute .dark et data-theme AVANT le rendu -->
    <script src="<?php echo e(asset('js/theme.js')); ?>"></script>
</head>
<body class="app-body" x-data>

    <!-- Overlay Mobile -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>
    
    <!-- Sidebar -->
    <?php if (isset($component)) { $__componentOriginal3623d0faebbae10085f2828f046806b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3623d0faebbae10085f2828f046806b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3623d0faebbae10085f2828f046806b2)): ?>
<?php $attributes = $__attributesOriginal3623d0faebbae10085f2828f046806b2; ?>
<?php unset($__attributesOriginal3623d0faebbae10085f2828f046806b2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3623d0faebbae10085f2828f046806b2)): ?>
<?php $component = $__componentOriginal3623d0faebbae10085f2828f046806b2; ?>
<?php unset($__componentOriginal3623d0faebbae10085f2828f046806b2); ?>
<?php endif; ?>

    <!-- Main Content Area -->
    <div class="app-main">
        
        <!-- Top Navbar -->
        <?php if (isset($component)) { $__componentOriginal7a1851460580b016997ecb03412ebcac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a1851460580b016997ecb03412ebcac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7a1851460580b016997ecb03412ebcac)): ?>
<?php $attributes = $__attributesOriginal7a1851460580b016997ecb03412ebcac; ?>
<?php unset($__attributesOriginal7a1851460580b016997ecb03412ebcac); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7a1851460580b016997ecb03412ebcac)): ?>
<?php $component = $__componentOriginal7a1851460580b016997ecb03412ebcac; ?>
<?php unset($__componentOriginal7a1851460580b016997ecb03412ebcac); ?>
<?php endif; ?>

        <!-- Page Content -->
        <main class="app-content">
            
            <div class="animate-in">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>

        <!-- Footer -->
        <footer style="background: var(--bg-page); border-top: 1px solid var(--border-color);" class="py-10 px-6 text-center mt-auto">
            <p style="color: var(--text-muted);" class="text-xs">
                &copy; <?php echo e(date('Y')); ?> <span style="color: var(--text-primary);" class="font-bold">Stoki </span>. Tous droits réservés.
            </p>
        </footer>
    </div>

    <!-- Conteneur pour les notifications toast Bootstrap 5 -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999; margin-top: 60px;">
        <?php
            $alerts = ['success' => 'check-circle', 'error' => 'times-circle', 'warning' => 'exclamation-triangle', 'info' => 'info-circle'];
            $bgClasses = ['success' => 'success text-white', 'error' => 'danger text-white', 'warning' => 'warning text-dark', 'info' => 'info text-dark'];
        ?>
        
        <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(session($type)): ?>
                <div class="toast align-items-center text-bg-<?php echo e(explode(' ', $bgClasses[$type])[0]); ?> border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="<?php echo e($type === 'error' ? '5000' : '3500'); ?>">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center gap-2 <?php echo e(str_contains($bgClasses[$type], 'text-white') ? 'text-white' : 'text-dark'); ?>">
                            <i class="fas fa-<?php echo e($icon); ?> fs-5"></i>
                            <span class="fw-medium font-inter"><?php echo e(session($type)); ?></span>
                        </div>
                        <button type="button" class="btn-close <?php echo e(str_contains($bgClasses[$type], 'text-white') ? 'btn-close-white' : ''); ?> me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Setup Toast Auto-Show -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { autohide: true })
            });
            toastList.forEach(toast => toast.show());
        });
    </script>
    
    <!-- Base Logic -->
    <script src="<?php echo e(asset('js/sidebar.js')); ?>"></script>
    <script src="<?php echo e(asset('js/notifications.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views/layouts/app.blade.php ENDPATH**/ ?>