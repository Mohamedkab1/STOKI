<?php
// database/migrations/2024_03_13_000001_create_product_batches_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('batch_number')->unique(); // Numéro de lot
            $table->decimal('purchase_price', 10, 2); // Prix d'achat
            $table->integer('initial_quantity'); // Quantité initiale
            $table->integer('remaining_quantity'); // Quantité restante
            $table->date('manufacturing_date')->nullable(); // Date de fabrication
            $table->date('expiry_date')->nullable(); // Date d'expiration
            $table->date('received_date'); // Date de réception
            $table->string('supplier')->nullable(); // Fournisseur
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index(['product_id', 'expiry_date']);
            $table->index(['product_id', 'is_active']);
            $table->index('received_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_batches');
    }
};