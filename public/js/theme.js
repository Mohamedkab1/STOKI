/**
 * =================================================================
 *  STOKI ERP — Gestion du Thème Clair / Sombre
 *  Fichier : public/js/theme.js
 *
 *  Le thème est stocké dans localStorage sous la clé "theme".
 *  La classe .dark est ajoutée/retirée de <html>.
 *  L'attribut data-theme est aussi synchronisé pour la compatibilité Tailwind.
 * =================================================================
 */

(function () {
    'use strict';

    var STORAGE_KEY = 'theme';

    /**
     * Lire le thème sauvegardé ou détecter la préférence système
     * @returns {string} 'light' ou 'dark'
     */
    function getSavedTheme() {
        var saved = localStorage.getItem(STORAGE_KEY);
        if (saved === 'dark' || saved === 'light') return saved;
        // Préférence système
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    /**
     * Appliquer le thème sur la page
     * @param {string} theme - 'light' ou 'dark'
     * @param {boolean} animate - Animation de rotation sur l'icône
     */
    function applyTheme(theme, animate) {
        var html = document.documentElement;

        // Classe .dark sur <html>
        if (theme === 'dark') {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        // Attribut data-theme pour compatibilité Tailwind / CSS existant
        html.setAttribute('data-theme', theme);

        // Synchroniser avec Bootstrap
        html.setAttribute('data-bs-theme', theme);

        // Sauvegarder
        localStorage.setItem(STORAGE_KEY, theme);

        // Mettre à jour les icônes
        updateIcons(theme, animate || false);
    }

    /**
     * Mettre à jour toutes les icônes de toggle sur la page
     * @param {string} theme - Thème actuel
     * @param {boolean} animate - Animation
     */
    function updateIcons(theme, animate) {
        var buttons = document.querySelectorAll('.theme-toggle-btn');

        buttons.forEach(function (btn) {
            var icon = btn.querySelector('.theme-icon');
            if (!icon) return;

            // Animation de transition
            if (animate) {
                btn.classList.add('theme-switching');
                setTimeout(function () {
                    btn.classList.remove('theme-switching');
                }, 500);
            }

            // Soleil = mode sombre actif (cliquer pour passer en clair)
            // Lune = mode clair actif (cliquer pour passer en sombre)
            if (theme === 'dark') {
                icon.className = 'fas fa-sun theme-icon';
                btn.setAttribute('title', 'Passer en mode clair');
                btn.setAttribute('aria-label', 'Passer en mode clair');
            } else {
                icon.className = 'fas fa-moon theme-icon';
                btn.setAttribute('title', 'Passer en mode sombre');
                btn.setAttribute('aria-label', 'Passer en mode sombre');
            }
        });

        // Ancien toggle legacy (si présent)
        var legacy = document.getElementById('themeToggle');
        if (legacy && !legacy.classList.contains('theme-toggle-btn')) {
            var legacyIcon = legacy.querySelector('i');
            if (legacyIcon) {
                legacyIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        }
    }

    /**
     * Basculer le thème — Fonction globale appelée depuis le HTML
     */
    window.toggleTheme = function () {
        var html = document.documentElement;
        var current = html.classList.contains('dark') ? 'dark' : 'light';
        var target = current === 'dark' ? 'light' : 'dark';
        applyTheme(target, true);
    };

    // =================================================================
    //  INITIALISATION — Exécuté immédiatement (avant DOMContentLoaded)
    // =================================================================
    applyTheme(getSavedTheme(), false);

    // Synchroniser les icônes après le chargement complet du DOM
    document.addEventListener('DOMContentLoaded', function () {
        var theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        updateIcons(theme, false);
    });

    // Écouter les changements de préférence système
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
        if (!localStorage.getItem(STORAGE_KEY)) {
            applyTheme(e.matches ? 'dark' : 'light', true);
        }
    });

})();
