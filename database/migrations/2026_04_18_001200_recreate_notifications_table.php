<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : Recréer la table notifications avec le schéma personnalisé
 *
 * Colonnes : id, user_id, title, body, type (info/success/warning/danger), is_read, created_at
 * Cette table remplace le système de notifications natif Laravel.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Supprimer l'ancienne table de notifications Laravel (UUID-based)
        Schema::dropIfExists('notifications');

        // Créer la nouvelle table avec le schéma spécifié
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->enum('type', ['info', 'success', 'warning', 'danger'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // Index pour les requêtes fréquentes
            $table->index(['user_id', 'is_read']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
