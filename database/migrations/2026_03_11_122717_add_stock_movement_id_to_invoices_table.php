<?php
// database/migrations/2024_03_11_xxxxxx_add_stock_movement_id_to_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Vérifier si la colonne n'existe pas déjà
            if (!Schema::hasColumn('invoices', 'stock_movement_id')) {
                $table->foreignId('stock_movement_id')
                      ->nullable()
                      ->constrained('stock_movements')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'stock_movement_id')) {
                $table->dropForeign(['stock_movement_id']);
                $table->dropColumn('stock_movement_id');
            }
        });
    }
};