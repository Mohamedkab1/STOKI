<?php
// database/migrations/2024_01_01_000004_create_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->enum('type', ['purchase', 'sale']); // achat ou vente
            $table->enum('movement_type', ['in', 'out']); // entrée ou sortie
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->text('reason')->nullable();
            $table->string('customer_supplier')->nullable(); // nom du client/fournisseur
            $table->string('payment_method')->nullable(); // espèce, carte, virement
            $table->enum('payment_status', ['paid', 'pending', 'cancelled'])->default('pending');
            $table->timestamp('invoice_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};