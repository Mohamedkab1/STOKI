<?php
// database/migrations/2024_03_14_xxxxxx_add_price_columns_to_stock_movements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas avant de les ajouter
            if (!Schema::hasColumn('stock_movements', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->nullable()->after('quantity');
            }
            
            if (!Schema::hasColumn('stock_movements', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable()->after('unit_price');
            }
            
            if (!Schema::hasColumn('stock_movements', 'invoice_id')) {
                $table->foreignId('invoice_id')
                      ->nullable()
                      ->constrained()
                      ->onDelete('set null')
                      ->after('total_price');
            }
        });
    }

    public function down()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'total_price']);
            $table->dropForeign(['invoice_id']);
            $table->dropColumn('invoice_id');
        });
    }
};