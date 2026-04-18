{{-- 
    Composant Toast / Alert (x-toast-container)
    Système de toasts empilés en bas à droite
    4 variantes : success, error, warning, info
    Auto-dismiss après 4 secondes avec barre de progression
    
    Utilisation depuis Blade:
    - Les messages flash Laravel sont automatiquement convertis en toasts
    
    Utilisation depuis JS:
    - window.showToast({ type: 'success', title: 'Titre', message: 'Message' })
--}}

{{-- Conteneur des toasts --}}
<div id="toastStack" class="toast-stack"></div>

@push('scripts')
<script>
(function() {
    'use strict';

    var TOAST_DURATION = 4000; // 4 secondes
    var toastStack = document.getElementById('toastStack');

    /**
     * Icônes par type de toast
     */
    var toastIcons = {
        success: 'fas fa-check-circle',
        error:   'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        info:    'fas fa-info-circle'
    };

    /**
     * Titres par défaut
     */
    var defaultTitles = {
        success: 'Succès',
        error:   'Erreur',
        warning: 'Attention',
        info:    'Information'
    };

    /**
     * Afficher un toast — Fonction globale
     * @param {Object} options
     * @param {string} options.type - 'success', 'error', 'warning', 'info'
     * @param {string} options.title - Titre du toast (optionnel)
     * @param {string} options.message - Message du toast
     * @param {number} options.duration - Durée en ms (défaut: 4000)
     */
    window.showToast = function(options) {
        var type = options.type || 'info';
        var title = options.title || defaultTitles[type] || 'Notification';
        var message = options.message || '';
        var duration = options.duration || TOAST_DURATION;

        // Créer l'élément toast
        var toast = document.createElement('div');
        toast.className = 'toast-item toast-item--' + type;

        toast.innerHTML = 
            '<div class="toast-item__icon"><i class="' + toastIcons[type] + '"></i></div>' +
            '<div class="toast-item__content">' +
                '<p class="toast-item__title">' + escapeHtml(title) + '</p>' +
                (message ? '<p class="toast-item__message">' + escapeHtml(message) + '</p>' : '') +
            '</div>' +
            '<button class="toast-item__close" onclick="dismissToast(this.parentElement)" type="button">' +
                '<i class="fas fa-times"></i>' +
            '</button>' +
            '<div class="toast-item__progress-track"></div>' +
            '<div class="toast-item__progress" style="width: 100%;"></div>';

        toastStack.appendChild(toast);

        // Animer la barre de progression
        var progressBar = toast.querySelector('.toast-item__progress');
        requestAnimationFrame(function() {
            progressBar.style.transitionDuration = duration + 'ms';
            progressBar.style.width = '0%';
        });

        // Auto-dismiss après la durée spécifiée
        var timeout = setTimeout(function() {
            dismissToast(toast);
        }, duration);

        // Stocker le timeout pour annulation manuelle
        toast._timeout = timeout;

        // Pause au survol
        toast.addEventListener('mouseenter', function() {
            clearTimeout(toast._timeout);
            progressBar.style.transitionDuration = '0ms';
            var currentWidth = progressBar.getBoundingClientRect().width;
            var parentWidth = progressBar.parentElement.getBoundingClientRect().width;
            toast._remainingPercent = (currentWidth / parentWidth) * 100;
            progressBar.style.width = toast._remainingPercent + '%';
        });

        toast.addEventListener('mouseleave', function() {
            var remaining = toast._remainingPercent || 100;
            var remainingTime = (remaining / 100) * duration;
            progressBar.style.transitionDuration = remainingTime + 'ms';
            progressBar.style.width = '0%';
            toast._timeout = setTimeout(function() {
                dismissToast(toast);
            }, remainingTime);
        });
    };

    /**
     * Fermer un toast avec animation
     * @param {HTMLElement} toast - Élément toast à fermer
     */
    window.dismissToast = function(toast) {
        if (!toast || toast._dismissed) return;
        toast._dismissed = true;

        clearTimeout(toast._timeout);
        toast.classList.add('toast-item--exiting');

        setTimeout(function() {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
        }, 300);
    };

    /**
     * Échapper le HTML
     */
    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    /**
     * Convertir automatiquement les messages flash Laravel en toasts
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Récupérer les messages flash injectés par Blade
        var flashMessages = document.querySelectorAll('[data-flash-toast]');
        flashMessages.forEach(function(el) {
            var type = el.getAttribute('data-flash-type') || 'info';
            var message = el.getAttribute('data-flash-message') || '';
            
            // Mapper les types Laravel aux types de toast
            var typeMap = {
                'success': 'success',
                'error': 'error',
                'danger': 'error',
                'warning': 'warning',
                'info': 'info'
            };

            window.showToast({
                type: typeMap[type] || 'info',
                message: message
            });

            // Retirer l'élément flash du DOM
            el.remove();
        });
    });
})();
</script>
@endpush
