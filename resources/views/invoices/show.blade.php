@extends('layouts.app')

@section('title', 'Facture ' . $invoice->invoice_number)

@section('content')
<div class="space-y-8 animate-in">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-text-main">
                <span class="text-brand-primary">Facture</span> {{ $invoice->invoice_number }}
            </h1>
            <p class="text-text-muted mt-1 font-medium">Générée le {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <x-ui.button href="{{ route('invoices.pdf', $invoice) }}" tag="a" variant="outline" icon="fas fa-file-pdf">
                Télécharger PDF
            </x-ui.button>
            <x-ui.button href="{{ route('invoices.index') }}" tag="a" variant="ghost" icon="fas fa-arrow-left">
                Retour
            </x-ui.button>
        </div>
    </div>

    <!-- Main Receipt Card -->
    <x-ui.card :padding="false" class="overflow-hidden border-none shadow-premium-xl max-w-4xl mx-auto">
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
                    <h3 class="text-5xl font-black mb-2">{{ $invoice->invoice_number }}</h3>
                    <div class="text-sm font-bold text-slate-400 space-y-1">
                        <p>Date: {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                        <p>Heure: {{ $invoice->invoice_date->format('H:i') }}</p>
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
                        @if($invoice->type == 'purchase')
                            <i class="fas fa-truck mr-2"></i> Fournisseur
                        @else
                            <i class="fas fa-user mr-2"></i> Destinataire (Client)
                        @endif
                    </p>
                    <h4 class="text-lg font-black text-text-main">{{ $invoice->customer_supplier ?: 'Vente au comptoir' }}</h4>
                    <p class="text-xs text-text-muted mt-1">Identifiant client: #{{ $invoice->id + 1000 }}</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 border border-border-subtle">
                    <p class="text-[10px] font-bold text-text-muted uppercase tracking-widest mb-4">
                        <i class="fas fa-credit-card mr-2 text-brand-primary"></i> Détails de paiement
                    </p>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-text-muted uppercase">Mode</span>
                        <span class="text-sm font-black text-text-main">{{ $invoice->payment_method ?: 'Espèces' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-muted uppercase">Statut</span>
                        <x-ui.badge :variant="$invoice->payment_status == 'paid' ? 'success' : 'warning'" size="sm" class="px-3">
                            {{ $invoice->payment_status == 'paid' ? 'PAYÉ' : 'EN ATTENTE' }}
                        </x-ui.badge>
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
                                <div class="text-sm font-black text-text-main leading-tight">{{ $invoice->product->name }}</div>
                                <div class="text-[10px] text-text-muted mt-1 font-mono">SKU: {{ $invoice->product->sku }}</div>
                            </td>
                            <td class="py-6 text-center text-sm font-black text-text-main">
                                {{ $invoice->quantity }}
                            </td>
                            <td class="py-6 text-right text-sm font-bold text-text-main">
                                {{ number_format($invoice->unit_price, 2) }} <span class="text-[10px]">MAD</span>
                            </td>
                            <td class="py-6 text-right text-sm font-black text-brand-primary">
                                {{ number_format($invoice->total_amount, 2) }} <span class="text-[10px]">MAD</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Totals Section -->
            <div class="flex flex-col md:flex-row justify-between gap-12 pt-8 border-t-4 border-slate-900/5">
                <div class="flex-1">
                    @if($invoice->reason)
                        <div class="p-4 rounded-xl bg-sidebar-active/50 border border-border-subtle italic text-xs text-text-muted leading-relaxed transition-colors duration-300">
                            <span class="font-bold text-text-main block not-italic mb-1 uppercase tracking-widest text-[9px]">Notes:</span>
                            {{ $invoice->reason }}
                        </div>
                    @endif
                </div>
                <div class="w-full md:w-80 space-y-4">
                    <div class="flex items-center justify-between text-xs font-bold text-text-muted uppercase tracking-widest">
                        <span>Sous-total HT</span>
                        <span>{{ number_format($invoice->total_amount, 2) }} MAD</span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-bold text-text-muted uppercase tracking-widest">
                        <span>Taxe (0%)</span>
                        <span>0,00 MAD</span>
                    </div>
                    <div class="pt-4 border-t border-border-subtle flex items-center justify-between">
                        <span class="text-sm font-black text-text-main uppercase">Total TTC</span>
                        <div class="text-right">
                            <div class="text-3xl font-black text-brand-primary">{{ number_format($invoice->total_amount, 2) }}</div>
                            <div class="text-[10px] font-bold text-text-muted uppercase tracking-widest">Dirhams (MAD)</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-20 pt-8 border-t border-border-subtle text-center text-[10px] font-black text-text-muted uppercase tracking-[0.3em] opacity-40">
                <i class="fas fa-print mr-2"></i> Documents Généré par STOKI ERP • 2026
            </div>
        </div>
    </x-ui.card>
</div>
@endsection