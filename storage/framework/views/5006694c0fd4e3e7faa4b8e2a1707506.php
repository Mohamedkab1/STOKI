<!-- resources/views/invoices/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture <?php echo e($invoice->invoice_number); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 28px;
        }
        .header h3 {
            margin: 5px 0 0;
            color: #666;
            font-weight: normal;
        }
        .invoice-info {
            margin-bottom: 30px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            padding: 5px;
        }
        .invoice-info .label {
            font-weight: bold;
            width: 150px;
        }
        .product-details {
            margin-bottom: 30px;
        }
        .product-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .product-details th {
            background: #333;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .product-details td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .product-details .total-row {
            font-weight: bold;
            background: #f0f0f0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }
        .badge-warning {
            background: #ffc107;
            color: black;
        }
        .badge-danger {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>GESTION DE STOCK</h1>
        <h3>Facture <?php echo e($invoice->invoice_number); ?></h3>
    </div>
    
    <div class="invoice-info">
        <table>
            <tr>
                <td class="label">Date :</td>
                <td><?php echo e($invoice->invoice_date->format('d/m/Y H:i')); ?></td>
            </tr>
            <tr>
                <td class="label">Type :</td>
                <td>
                    <?php if($invoice->type == 'purchase'): ?>
                        <span class="badge badge-success">Achat</span>
                    <?php else: ?>
                        <span class="badge badge-success">Vente</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="label">Client/Fournisseur :</td>
                <td><?php echo e($invoice->customer_supplier ?? 'Non spécifié'); ?></td>
            </tr>
            <tr>
                <td class="label">Mode de paiement :</td>
                <td><?php echo e($invoice->payment_method ?? 'Non spécifié'); ?></td>
            </tr>
            <tr>
                <td class="label">Statut :</td>
                <td>
                    <?php if($invoice->payment_status == 'paid'): ?>
                        <span class="badge badge-success">Payé</span>
                    <?php elseif($invoice->payment_status == 'pending'): ?>
                        <span class="badge badge-warning">En attente</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Annulé</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="product-details">
        <h3>Détails du produit</h3>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if($invoice->items->count() > 0): ?>
                    <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->product->name); ?></td>
                        <td><?php echo e($item->quantity); ?></td>
                        <td><?php echo e(number_format($item->unit_price, 2)); ?> DH</td>
                        <td><?php echo e(number_format($item->subtotal, 2)); ?> DH</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr>
                        <td><?php echo e($invoice->product->name); ?></td>
                        <td><?php echo e($invoice->quantity); ?></td>
                        <td><?php echo e(number_format($invoice->unit_price, 2)); ?> DH</td>
                        <td><?php echo e(number_format($invoice->total_amount, 2)); ?> DH</td>
                    </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;"><strong>TOTAL :</strong></td>
                    <td><strong><?php echo e(number_format($invoice->total_amount, 2)); ?> DH</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <?php if($invoice->reason): ?>
    <div style="margin-top: 30px; padding: 15px; background: #f9f9f9; border-radius: 5px;">
        <strong>Motif :</strong>
        <p><?php echo e($invoice->reason); ?></p>
    </div>
    <?php endif; ?>
    
    <div class="footer">
        <p>Merci de votre confiance !</p>
        <p>Cette facture est générée automatiquement par le système de gestion de stock.</p>
    </div>
</body>
</html><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views/invoices/pdf.blade.php ENDPATH**/ ?>