<!-- resources/views/invoices/show.blade.php -->
@extends('layouts.app')

@section('title', 'Facture ' . $invoice->invoice_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
    <div>
        <h1 class="text-gradient mb-2">
            <i class="fas fa-file-invoice me-2"></i>
            Facture {{ $invoice->invoice_number }}
        </h1>
        <p class="text-white-50">Générée le {{ $invoice->created_at->format('d/m/Y à H:i') }}</p>
    </div>
    <div>
        <a href="{{ route('invoices.pdf', $invoice) }}" class="btn-premium me-2">
            <i class="fas fa-file-pdf me-2"></i>
            Télécharger PDF
        </a>
        <a href="{{ route('invoices.index') }}" class="btn-premium">
            <i class="fas fa-arrow-left me-2"></i>
            Retour
        </a>
    </div>
</div>

<!-- Message de succès -->
@if(session('success'))
    <div class="alert alert-premium alert-dismissible fade show" role="alert" data-aos="fade-down">
        <i class="fas fa-check-circle me-2" style="color: #4285f4;"></i>
        {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- La facture -->
<div class="premium-card mb-4" id="facture-content" data-aos="fade-up">
    <div class="card-body p-5">
        <!-- En-tête de la facture -->
        <div class="row mb-5">
            <div class="col-6">
                <h2 class="text-gradient" style="font-family: 'Playfair Display', serif;">STOKI</h2>
                <p class="text-white-50 mb-0">123 Rue du Commerce</p>
                <p class="text-white-50 mb-0">75001 Paris, France</p>
                <p class="text-white-50 mb-0">Tél: 01 23 45 67 89</p>
                <p class="text-white-50">Email: contact@stoki.com</p>
            </div>
            <div class="col-6 text-end">
                <h3 class="text-white-50">FACTURE</h3>
                <h1 class="display-6" style="color: #4285f4;">{{ $invoice->invoice_number }}</h1>
                <p class="text-white-50 mb-0">Date: {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                <p class="text-white-50">Heure: {{ $invoice->invoice_date->format('H:i') }}</p>
            </div>
        </div>

        <!-- Informations client/fournisseur -->
        <div class="row mb-5">
            <div class="col-6">
                <div class="p-4" style="background: rgba(66, 133, 244, 0.1); border-radius: 15px; border-left: 4px solid #4285f4;">
                    <h5 class="mb-3" style="color: #4285f4;">
                        @if($invoice->type == 'purchase')
                            <i class="fas fa-truck me-2"></i>Fournisseur:
                        @else
                            <i class="fas fa-user me-2"></i>Client:
                        @endif
                    </h5>
                    <h4 class="text-white">{{ $invoice->customer_supplier }}</h4>
                </div>
            </div>
            <div class="col-6">
                <div class="p-4" style="background: rgba(212, 165, 154, 0.1); border-radius: 15px; border-left: 4px solid #D4A59A;">
                    <h5 class="mb-3" style="color: #D4A59A;">
                        <i class="fas fa-credit-card me-2"></i>Paiement:
                    </h5>
                    <p class="text-white-50 mb-1">
                        <i class="fas fa-money-bill me-2"></i>Mode: {{ $invoice->payment_method }}
                    </p>
                    <p class="text-white-50 mb-1">
                        <i class="fas fa-info-circle me-2"></i>Statut: 
                        @if($invoice->payment_status == 'paid')
                            <span class="badge" style="background: linear-gradient(135deg, #4285f4 0%, #5C2018 100%);">Payé</span>
                        @elseif($invoice->payment_status == 'pending')
                            <span class="badge" style="background: linear-gradient(135deg, #BC4639 0%, #5C2018 100%);">En attente</span>
                        @else
                            <span class="badge" style="background: #5C2018;">Annulé</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Détails des articles -->
        <div class="row mb-5">
            <div class="col-12">
                <h5 class="text-gradient mb-3">Détails de la facture</h5>
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>SKU</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-end">Prix unitaire</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-white">{{ $invoice->product->name }}</td>
                            <td class="text-white-50">{{ $invoice->product->sku }}</td>
                            <td class="text-center text-white">{{ $invoice->quantity }}</td>
                            <td class="text-end text-white">{{ number_format($invoice->unit_price, 2) }} €</td>
                            <td class="text-end" style="color: #4285f4; font-weight: 700;">{{ number_format($invoice->total_amount, 2) }} €</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-active">
                        <tr>
                            <td colspan="4" class="text-end text-white"><strong>Sous-total:</strong></td>
                            <td class="text-end text-white">{{ number_format($invoice->total_amount, 2) }} €</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end text-white"><strong>TVA (0%):</strong></td>
                            <td class="text-end text-white">0.00 €</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end text-white"><strong class="h5">TOTAL TTC:</strong></td>
                            <td class="text-end"><strong class="h5" style="color: #4285f4;">{{ number_format($invoice->total_amount, 2) }} €</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Motif / Description -->
        @if($invoice->reason)
        <div class="row mb-4">
            <div class="col-12">
                <div class="p-4" style="background: rgba(243, 224, 220, 0.05); border-radius: 15px;">
                    <h5 class="mb-3" style="color: #D4A59A;">
                        <i class="fas fa-comment me-2"></i>Description:
                    </h5>
                    <p class="text-white-50 mb-0">{{ $invoice->reason }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Pied de page -->
        <div class="row">
            <div class="col-12 text-center">
                <hr style="border-color: rgba(66, 133, 244, 0.3);">
                <p class="text-white-50">
                    <i class="fas fa-print me-2" style="color: #4285f4;"></i>
                    Facture générée automatiquement par Stoki.
                    <br>
                    <span style="color: #D4A59A;">Merci de votre confiance !</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Mise à jour du statut de paiement -->
@if($invoice->payment_status != 'paid')
<div class="premium-card mb-4" data-aos="fade-up">
    <div class="card-header-premium">
        <h5 class="mb-0">
            <i class="fas fa-credit-card me-2"></i>
            Mettre à jour le paiement
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('invoices.update-payment-status', $invoice) }}" method="POST" class="row g-3">
            @csrf
            @method('PATCH')
            <div class="col-md-4">
                <select name="payment_status" class="form-control bg-dark text-white border-0">
                    <option value="paid" {{ $invoice->payment_status == 'paid' ? 'selected' : '' }}>Payé</option>
                    <option value="pending" {{ $invoice->payment_status == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="cancelled" {{ $invoice->payment_status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-premium w-100">
                    <i class="fas fa-sync-alt me-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Boutons d'action -->
<div class="text-center">
    <button onclick="window.print()" class="btn-premium me-2">
        <i class="fas fa-print me-2"></i>Imprimer
    </button>
    <a href="{{ route('invoices.pdf', $invoice) }}" class="btn-premium">
        <i class="fas fa-file-pdf me-2"></i>Télécharger PDF
    </a>
</div>

<style>
@media print {
    body {
        background: white !important;
    }
    body::before, body::after {
        display: none !important;
    }
    .navbar-premium, .footer-premium, .btn-premium, .alert-premium {
        display: none !important;
    }
    #facture-content {
        background: white !important;
        color: black !important;
        box-shadow: none !important;
    }
    #facture-content .text-gradient {
        -webkit-text-fill-color: #4285f4 !important;
    }
    #facture-content .table-dark {
        background: white !important;
        color: black !important;
    }
    #facture-content .text-white, 
    #facture-content .text-white-50 {
        color: black !important;
    }
    #facture-content .card-body {
        padding: 20px !important;
    }
}
</style>
@endsection