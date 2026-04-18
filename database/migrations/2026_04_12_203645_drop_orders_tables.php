<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }

    public function down(): void
    {
        // On ne recrée pas l'historique complet des commandes dans le down pour préserver la simplicité
        // S'il faut vraiment restaurer, il faudrait récupérer la vraie structure depuis les fichiers originaux.
    }
};
