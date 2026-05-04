<?php $__env->startSection('title', 'Facture ' . $invoice->invoice_number); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-in">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-text-main">
                <span class="text-brand-primary">Facture</span> <?php echo e($invoice->invoice_number); ?>

            </h1>
            <p class="text-text-muted mt-1 font-medium">Générée le <?php echo e($invoice->created_at->format('d/m/Y H:i')); ?></p>
        </div>
        <div class="flex items-center gap-2">
            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['href' => ''.e(route('invoices.pdf', $invoice)).'','tag' => 'a','variant' => 'outline','icon' => 'fas fa-file-pdf']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('invoices.pdf', $invoice)).'','tag' => 'a','variant' => 'outline','icon' => 'fas fa-file-pdf']); ?>
                Télécharger PDF
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['href' => ''.e(route('invoices.index')).'','tag' => 'a','variant' => 'ghost','icon' => 'fas fa-arrow-left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('invoices.index')).'','tag' => 'a','variant' => 'ghost','icon' => 'fas fa-arrow-left']); ?>
                Retour
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
        </div>
    </div>

    <!-- Main Receipt Card -->
    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['padding' => false,'class' => 'overflow-hidden border-none shadow-premium-xl max-w-4xl mx-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'overflow-hidden border-none shadow-premium-xl max-w-4xl mx-auto']); ?>
        <div class="bg-slate-900 p-8 md:p-12 text-white relative">
            <div class="flex flex-col md:flex-row justify-between gap-8 relative z-10">
                <div>
                    <h2 class="text-3xl font-black tracking-tight mb-2">STOKI<span class="text-brand-primary">ERP</span></h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Système de gestion professionnel</p>
                    <div class="mt-8 text-sm text-slate-300 space-y-1">
                        <p>123 Rue du Commerce</p>
                        <p>75001 Paris, France</p>
                        <p class="pt-2 font-bold text-white">support@stoki.com</p>
                    </div>
                </div>
                <div class="md:text-right">
                    <div class="inline-block bg-brand-primary/20 text-brand-primary font-black py-2 px-4 rounded-xl text-xl mb-4">
                        FACTURE
                    </div>
                    <h3 class="text-5xl font-black mb-2"><?php echo e($invoice->invoice_number); ?></h3>
                    <div class="text-sm font-bold text-slate-400 space-y-1">
                        <p>Date: <?php echo e($invoice->invoice_date->format('d/m/Y')); ?></p>
                        <p>Heure: <?php echo e($invoice->invoice_date->format('H:i')); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Decorative circle -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-primary/10 rounded-full blur-3xl"></div>
        </div>

        <div class="p-8 md:p-12 bg-card transition-colors duration-300">
            <!-- Client & Payment Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="p-6 rounded-2xl bg-slate-50 border border-border-subtle">
                    <p class="text-[10px] font-bold text-text-muted uppercase tracking-widest mb-4">
                        <?php if($invoice->type == 'purchase'): ?>
                            <i class="fas fa-truck mr-2"></i> Fournisseur
                        <?php else: ?>
                            <i class="fas fa-user mr-2"></i> Destinataire (Client)
                        <?php endif; ?>
                    </p>
                    <h4 class="text-lg font-black text-text-main"><?php echo e($invoice->customer_supplier ?: 'Vente au comptoir'); ?></h4>
                    <p class="text-xs text-text-muted mt-1">Identifiant client: #<?php echo e($invoice->id + 1000); ?></p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 border border-border-subtle">
                    <p class="text-[10px] font-bold text-text-muted uppercase tracking-widest mb-4">
                        <i class="fas fa-credit-card mr-2 text-brand-primary"></i> Détails de paiement
                    </p>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-text-muted uppercase">Mode</span>
                        <span class="text-sm font-black text-text-main"><?php echo e($invoice->payment_method ?: 'Espèces'); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-muted uppercase">Statut</span>
                        <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['variant' => $invoice->payment_status == 'paid' ? 'success' : 'warning','size' => 'sm','class' => 'px-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($invoice->payment_status == 'paid' ? 'success' : 'warning'),'size' => 'sm','class' => 'px-3']); ?>
                            <?php echo e($invoice->payment_status == 'paid' ? 'PAYÉ' : 'EN ATTENTE'); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto mb-12">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-bold text-text-muted uppercase tracking-widest border-b border-border-subtle">
                            <th class="pb-4">Article & Désignation</th>
                            <th class="pb-4 text-center">Qté</th>
                            <th class="pb-4 text-right">Prix Unitaire</th>
                            <th class="pb-4 text-right">Total HT</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle">
                        <tr class="group">
                            <td class="py-6">
                                <div class="text-sm font-black text-text-main leading-tight"><?php echo e($invoice->product->name); ?></div>
                                <div class="text-[10px] text-text-muted mt-1 font-mono">SKU: <?php echo e($invoice->product->sku); ?></div>
                            </td>
                            <td class="py-6 text-center text-sm font-black text-text-main">
                                <?php echo e($invoice->quantity); ?>

                            </td>
                            <td class="py-6 text-right text-sm font-bold text-text-main">
                                <?php echo e(number_format($invoice->unit_price, 2)); ?> <span class="text-[10px]">MAD</span>
                            </td>
                            <td class="py-6 text-right text-sm font-black text-brand-primary">
                                <?php echo e(number_format($invoice->total_amount, 2)); ?> <span class="text-[10px]">MAD</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Totals Section -->
            <div class="flex flex-col md:flex-row justify-between gap-12 pt-8 border-t-4 border-slate-900/5">
                <div class="flex-1">
                    <?php if($invoice->reason): ?>
                        <div class="p-4 rounded-xl bg-sidebar-active/50 border border-border-subtle italic text-xs text-text-muted leading-relaxed transition-colors duration-300">
                            <span class="font-bold text-text-main block not-italic mb-1 uppercase tracking-widest text-[9px]">Notes:</span>
                            <?php echo e($invoice->reason); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <div class="w-full md:w-80 space-y-4">
                    <div class="flex items-center justify-between text-xs font-bold text-text-muted uppercase tracking-widest">
                        <span>Sous-total HT</span>
                        <span><?php echo e(number_format($invoice->total_amount, 2)); ?> MAD</span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-bold text-text-muted uppercase tracking-widest">
                        <span>Taxe (0%)</span>
                        <span>0,00 MAD</span>
                    </div>
                    <div class="pt-4 border-t border-border-subtle flex items-center justify-between">
                        <span class="text-sm font-black text-text-main uppercase">Total TTC</span>
                        <div class="text-right">
                            <div class="text-3xl font-black text-brand-primary"><?php echo e(number_format($invoice->total_amount, 2)); ?></div>
                            <div class="text-[10px] font-bold text-text-muted uppercase tracking-widest">Dirhams (MAD)</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-20 pt-8 border-t border-border-subtle text-center text-[10px] font-black text-text-muted uppercase tracking-[0.3em] opacity-40">
                <i class="fas fa-print mr-2"></i> Documents Généré par STOKI ERP • 2026
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\invoices\show.blade.php ENDPATH**/ ?>