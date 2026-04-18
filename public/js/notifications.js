/**
 * =================================================================
 *  STOKI ERP — Système de Notifications par Catégories
 *  Fichier : public/js/notifications.js
 * =================================================================
 */

(function () {
    'use strict';

    var POLL_INTERVAL = 30000;
    var CSRF = document.querySelector('meta[name="csrf-token"]');
    var CSRF_TOKEN = CSRF ? CSRF.getAttribute('content') : '';

    var bellBtn, dropdown, badgeEl, listEl, markAllBtn;
    var isOpen = false;
    var currentCategory = 'entree'; // Défaut

    function init() {
        bellBtn    = document.getElementById('notificationBell');
        dropdown   = document.getElementById('notificationDropdown');
        badgeEl    = document.getElementById('notificationBadge');
        listEl     = document.getElementById('notificationList');
        markAllBtn = document.getElementById('markAllReadBtn');
    }

    function openDropdown() {
        if (!dropdown) return;
        dropdown.style.opacity = '1';
        dropdown.style.visibility = 'visible';
        dropdown.style.transform = 'translateY(0) scale(1)';
        dropdown.style.pointerEvents = 'auto';
        isOpen = true;
        if (bellBtn) bellBtn.setAttribute('aria-expanded', 'true');

        loadCategoryNotifications(currentCategory);

        setTimeout(function () {
            document.addEventListener('click', outsideClickHandler);
        }, 10);
    }

    function closeDropdown() {
        if (!dropdown) return;
        dropdown.style.opacity = '0';
        dropdown.style.visibility = 'hidden';
        dropdown.style.transform = 'translateY(-8px) scale(0.98)';
        dropdown.style.pointerEvents = 'none';
        isOpen = false;
        if (bellBtn) bellBtn.setAttribute('aria-expanded', 'false');
        document.removeEventListener('click', outsideClickHandler);
    }

    function outsideClickHandler(e) {
        if (!dropdown.contains(e.target) && !bellBtn.contains(e.target)) {
            closeDropdown();
        }
    }

    /**
     * Polling des compteurs
     */
    function refreshCounts() {
        fetch('/notifications/unread-count', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            // Mettre à jour badge principal
            if (badgeEl) {
                if (data.total && data.total > 0) {
                    badgeEl.textContent = data.total > 99 ? '99+' : data.total;
                    badgeEl.style.display = 'flex';
                } else {
                    badgeEl.style.display = 'none';
                }
            }
            
            // Mettre à jour les badges des onglets
            ['entree', 'sortie', 'alerte_stock', 'facture'].forEach(function(cat) {
                var tabBadge = document.querySelector('.tab-badge[data-category="'+cat+'"]');
                if (tabBadge) {
                    if (data[cat] && data[cat] > 0) {
                        tabBadge.textContent = data[cat];
                        tabBadge.style.display = 'inline-block';
                    } else {
                        tabBadge.style.display = 'none';
                    }
                }
            });
        })
        .catch(function(){}); // fail silencieux
    }

    /**
     * Charger liste par catégorie (Exporté pour être utilisé par les onglets)
     */
    window.loadCategoryNotifications = function(category) {
        currentCategory = category;
        if (!listEl) return;
        
        listEl.innerHTML = '<div style="padding: 40px; text-align: center;"><i class="fas fa-circle-notch fa-spin" style="color:var(--text-muted); font-size:24px;"></i></div>';

        fetch('/notifications/category/' + category, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) {
            if (!res.ok) throw new Error('Erreur réseau');
            return res.json();
        })
        .then(function(data) {
            renderList(data.notifications || [], category);
        })
        .catch(function(err) {
            listEl.innerHTML = renderEmpty('erreur');
        });
    };

    /**
     * Gérer les icônes localement si besoin
     */
    function typeStyle(n) {
        var map = {
            info:    { bgVar: 'var(--badge-info-bg)',    textVar: 'var(--badge-info-text)' },
            success: { bgVar: 'var(--badge-success-bg)', textVar: 'var(--badge-success-text)' },
            warning: { bgVar: 'var(--badge-warning-bg)', textVar: 'var(--badge-warning-text)' },
            danger:  { bgVar: 'var(--badge-danger-bg)',  textVar: 'var(--badge-danger-text)' }
        };
        var s = map[n.type] || map['info'];

        // Fallback couleur par category si info
        if (n.category === 'entree') { s.bgVar = 'var(--mouvement-entree-bg)'; s.textVar = 'var(--mouvement-entree-text)'; }
        if (n.category === 'sortie' && n.type === 'info') { s.bgVar = 'var(--mouvement-sortie-bg)'; s.textVar = 'var(--mouvement-sortie-text)'; }

        return s;
    }

    function renderList(notifications, category) {
        if (!listEl) return;

        if (notifications.length === 0) {
            listEl.innerHTML = renderEmpty('vide');
            return;
        }

        var html = '';
        notifications.forEach(function (n) {
            var s = typeStyle(n);
            var unreadBg = n.is_read ? '' : 'background: var(--bg-surface);';
            var icon = n.icon || 'fas fa-bell';

            html += '<div class="notif-item" data-id="' + n.id + '" ' +
                    'style="display: flex; align-items: flex-start; gap: 12px; ' +
                    'padding: 14px 16px; cursor: pointer; transition: background 0.15s; ' +
                    'border-bottom: 1px solid var(--border-subtle); ' + unreadBg + '" ' +
                    'onclick="markNotifRead(' + n.id + ')" ' +
                    'onmouseover="this.style.background=\'var(--table-row-hover)\';" ' +
                    'onmouseout="this.style.background=\'' + (n.is_read ? '' : 'var(--bg-surface)') + '\';">' +

                    // Icône colorée
                    '<div style="width: 36px; height: 36px; border-radius: 8px; display: flex; ' +
                    'align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; ' +
                    'background: ' + s.bgVar + '; color: ' + s.textVar + ';">' +
                        '<i class="' + icon + '"></i>' +
                    '</div>' +

                    // Contenu
                    '<div style="flex: 1; min-width: 0;">' +
                        '<p style="font-size: 13px; font-weight: ' + (n.is_read ? '500' : '700') + '; color: ' + (n.is_read ? 'var(--text-secondary)' : 'var(--text-primary)') + '; margin: 0 0 4px 0; line-height: 1.3;">' +
                            escapeHtml(n.title) +
                        '</p>' +
                        '<p style="font-size: 12px; color: var(--text-muted); margin: 0 0 6px 0; line-height: 1.4; word-break: break-word;">' +
                            escapeHtml(n.body) +
                        '</p>' +
                        '<span style="font-size: 11px; color: var(--text-muted); display: flex; align-items: center; gap: 4px; opacity: 0.7;">' +
                            '<i class="fas fa-clock" style="font-size: 9px;"></i> ' + escapeHtml(n.time_ago) +
                        '</span>' +
                    '</div>' +

                    // Point non-lu
                    (n.is_read ? '' :
                        '<div class="unread-dot" style="width: 8px; height: 8px; border-radius: 50%; background: ' + s.textVar + '; flex-shrink: 0; margin-top: 14px;"></div>'
                    ) +

                '</div>';
        });

        listEl.innerHTML = html;
    }

    function renderEmpty(type) {
        if (type === 'erreur') {
            return '<div style="padding: 40px 20px; text-align: center;">' +
                '<i class="fas fa-exclamation-triangle" style="font-size: 32px; color: var(--badge-danger-text); margin-bottom: 12px; display: block; opacity: 0.5;"></i>' +
                '<p style="font-size: 13px; font-weight: 600; color: var(--text-primary); margin: 0;">Erreur de chargement des notifications</p>' +
            '</div>';
        }
        return '<div style="padding: 40px 20px; text-align: center;">' +
            '<i class="fas fa-inbox" style="font-size: 36px; color: var(--text-muted); margin-bottom: 12px; display: block; opacity: 0.3;"></i>' +
            '<p style="font-size: 13px; font-weight: 500; color: var(--text-secondary); margin: 0;">Aucune notification dans cette catégorie</p>' +
        '</div>';
    }

    /**
     * Mark as Read POST
     */
    window.markNotifRead = function (id) {
        fetch('/notifications/' + id + '/read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function () {
            var item = document.querySelector('.notif-item[data-id="' + id + '"]');
            if (item) {
                item.style.background = '';
                var dot = item.querySelector('.unread-dot');
                if (dot) dot.remove();
                
                // Mettre le titre en font-weight 500
                var title = item.querySelector('p:first-of-type');
                if (title) {
                    title.style.fontWeight = '500';
                    title.style.color = 'var(--text-secondary)';
                }
            }
            refreshCounts();
        }).catch(function(e){});
    };

    /**
     * Mark All Read POST
     */
    function markAllRead() {
        fetch('/notifications/read-all', {
            method: 'POST',
            body: JSON.stringify({ category: currentCategory }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function () {
            // Mettre à jour visuellement la liste courante
            var items = document.querySelectorAll('.notif-item');
            items.forEach(function (item) {
                item.style.background = '';
                var dot = item.querySelector('.unread-dot');
                if (dot) dot.remove();
                var title = item.querySelector('p:first-of-type');
                if (title) {
                    title.style.fontWeight = '500';
                    title.style.color = 'var(--text-secondary)';
                }
            });
            refreshCounts();
        }).catch(function(e){});
    }

    function escapeHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function () {
        init();

        if (bellBtn) {
            bellBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (isOpen) closeDropdown();
                else openDropdown();
            });
        }

        if (markAllBtn) {
            markAllBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                markAllRead();
            });
        }

        if (dropdown) {
            dropdown.addEventListener('click', function (e) {
                // Laisser passer les actions onglets et liens
                if (e.target.tagName === 'A' || e.target.closest('a') || e.target.closest('.notif-tab')) return;
                e.stopPropagation();
            });
        }

        refreshCounts();
        setInterval(refreshCounts, POLL_INTERVAL);
    });

})();
