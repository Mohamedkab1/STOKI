

<header class="app-navbar">
    <!-- Mobile Menu Toggle -->
    <button id="sidebar-toggle" class="hamburger-btn">
        <i class="fas fa-bars"></i>
    </button>

    <div class="hidden lg:flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-text-muted opacity-60">
        <span class="capitalize"><?php echo e(now()->locale('fr')->isoFormat('dddd D MMMM YYYY')); ?></span>
    </div>

    <!-- Div pour pousser les actions complètement à droite -->
    <div style="margin-left: auto;"></div>

    <div class="flex items-center gap-4 lg:gap-6">
        <!-- Theme Toggle (Clair/Sombre) -->
        <button 
            onclick="toggleTheme()" 
            class="theme-toggle-btn" 
            id="themeToggle" 
            title="Basculer le thème"
            aria-label="Basculer le thème"
            type="button"
        >
            <i class="fas fa-moon theme-icon"></i>
        </button>

        <!-- Notification Bell -->
        <?php if (isset($component)) { $__componentOriginal6541145ad4a57bfb6e6f221ba77eb386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification-bell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification-bell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386)): ?>
<?php $attributes = $__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386; ?>
<?php unset($__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6541145ad4a57bfb6e6f221ba77eb386)): ?>
<?php $component = $__componentOriginal6541145ad4a57bfb6e6f221ba77eb386; ?>
<?php unset($__componentOriginal6541145ad4a57bfb6e6f221ba77eb386); ?>
<?php endif; ?>

        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button @click="open = !open" class="flex items-center gap-3 p-1 rounded-xl hover:bg-sidebar-active transition-all group border-0 bg-transparent cursor-pointer">
                <div class="avatar">
                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                </div>
                <div class="hidden md:block text-left pr-2">
                    <p class="text-[11px] font-black text-text-main leading-tight uppercase tracking-tight m-0" style="color: var(--text-primary)"><?php echo e(auth()->user()->name); ?></p>
                    <p class="text-[9px] text-text-muted mt-0 uppercase font-bold opacity-60 m-0">Administrateur</p>
                </div>
                <i class="fas fa-chevron-down text-[10px] text-text-muted transition-transform duration-300" :class="open ? 'rotate-180 text-brand-primary' : ''"></i>
            </button>

            <!-- Dropdown Menu -->
            <div 
                x-show="open" 
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                class="absolute right-0 mt-3 w-64 bg-card border border-border-subtle rounded-2xl shadow-premium-xl z-50 overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-border-subtle bg-sidebar-active/50">
                    <p class="text-[10px] font-black text-text-muted uppercase tracking-widest mb-1">Session active</p>
                    <p class="text-xs font-bold text-text-main truncate" style="color: var(--text-primary); margin:0;"><?php echo e(auth()->user()->email); ?></p>
                </div>
                
                <div class="p-2.5">
                    <a href="<?php echo e(route('profile')); ?>" class="flex items-center gap-3 px-4 py-3 text-[11px] font-black uppercase tracking-tight text-text-muted hover:text-brand-primary hover:bg-brand-primary/5 rounded-xl transition-all group" style="text-decoration:none;">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center group-hover:bg-brand-primary/10 transition-colors">
                            <i class="fas fa-user-circle text-sm group-hover:scale-110 transition-transform"></i>
                        </div>
                        <span>Mon Profil</span>
                    </a>
                    
                    <div class="h-px bg-border-subtle my-2 mx-2"></div>
                    
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-[11px] font-black uppercase tracking-tight text-rose-500 hover:bg-rose-50 rounded-xl transition-all group text-left border-0 bg-transparent cursor-pointer">
                            <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center transition-colors shadow-sm">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views/components/layout/navbar.blade.php ENDPATH**/ ?>