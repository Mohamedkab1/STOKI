

<div class="notification-bell-wrapper" style="position: relative;" id="bellWrapper">
    
    <button
        id="notificationBell"
        class="theme-toggle-btn"
        title="Notifications"
        aria-label="Notifications"
        aria-expanded="false"
        aria-haspopup="true"
        type="button"
    >
        <i class="fas fa-bell" style="font-size: 15px; color: var(--text-secondary); transition: color 0.2s;"></i>
        
        <span
            id="notificationBadge"
            style="
                position: absolute;
                top: -4px;
                right: -4px;
                min-width: 18px;
                height: 18px;
                padding: 0 5px;
                border-radius: 50px;
                background: var(--badge-danger-text, #dc2626);
                color: #ffffff;
                font-size: 10px;
                font-weight: 800;
                display: none;
                align-items: center;
                justify-content: center;
                line-height: 1;
                box-shadow: 0 2px 8px rgba(220, 38, 38, 0.4);
            "
        >0</span>
    </button>

    
    <div id="notificationDropdown" style="
        position: absolute;
        top: calc(100% + 12px);
        right: 0;
        width: 420px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: var(--shadow-xl, 0 20px 25px -5px rgba(0, 0, 0, 0.1));
        z-index: 9999;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px) scale(0.98);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
        display: flex;
        flex-direction: column;
    ">
        
        <div style="background: var(--bg-surface); border-bottom: 1px solid var(--border-color);">
            <div style="
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 16px;
            ">
                <h6 style="
                    font-size: 14px;
                    font-weight: 800;
                    color: var(--text-primary);
                    margin: 0;
                    text-transform: uppercase;
                    letter-spacing: 0.02em;
                ">
                    Notifications
                </h6>
                <button
                    id="markAllReadBtn"
                    type="button"
                    title="Tout marquer comme lu (onglet actuel)"
                    style="
                        border: none;
                        background: transparent;
                        color: var(--accent);
                        cursor: pointer;
                        font-size: 12px;
                        font-weight: 600;
                        transition: color 0.15s;
                    "
                    onmouseover="this.style.color='var(--accent-hover)'"
                    onmouseout="this.style.color='var(--accent)'"
                >
                    Tout marquer lu
                </button>
            </div>
            
            
            <div id="notificationTabs" style="display: flex; gap: 4px; padding: 0 16px;">
                
                <button class="notif-tab active" data-category="entree" style="
                    background: transparent;
                    border: none;
                    border-bottom: 2px solid var(--mouvement-entree-text);
                    color: var(--text-primary);
                    font-weight: 700;
                    font-size: 12px;
                    padding: 8px 12px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    opacity: 1;
                    transition: opacity 0.2s, background 0.2s;
                ">
                    Entrées
                    <span class="tab-badge" data-category="entree" style="display: none; background: var(--mouvement-entree-bg); color: var(--mouvement-entree-text); border-radius: 4px; padding: 2px 6px; font-size: 10px;">0</span>
                </button>
                
                
                <button class="notif-tab" data-category="sortie" style="
                    background: transparent;
                    border: none;
                    border-bottom: 2px solid transparent;
                    color: var(--text-secondary);
                    font-weight: 600;
                    font-size: 12px;
                    padding: 8px 12px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    opacity: 0.7;
                    transition: opacity 0.2s, background 0.2s;
                ">
                    Sorties
                    <span class="tab-badge" data-category="sortie" style="display: none; background: var(--mouvement-sortie-bg); color: var(--mouvement-sortie-text); border-radius: 4px; padding: 2px 6px; font-size: 10px;">0</span>
                </button>
                
                
                <button class="notif-tab" data-category="alerte_stock" style="
                    background: transparent;
                    border: none;
                    border-bottom: 2px solid transparent;
                    color: var(--text-secondary);
                    font-weight: 600;
                    font-size: 12px;
                    padding: 8px 12px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    opacity: 0.7;
                    transition: opacity 0.2s, background 0.2s;
                ">
                    Alertes
                    <span class="tab-badge" data-category="alerte_stock" style="display: none; background: var(--badge-danger-bg); color: var(--badge-danger-text); border-radius: 4px; padding: 2px 6px; font-size: 10px;">0</span>
                </button>

                
                <button class="notif-tab" data-category="facture" style="
                    background: transparent;
                    border: none;
                    border-bottom: 2px solid transparent;
                    color: var(--text-secondary);
                    font-weight: 600;
                    font-size: 12px;
                    padding: 8px 12px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    opacity: 0.7;
                    transition: opacity 0.2s, background 0.2s;
                ">
                    Factures
                    <span class="tab-badge" data-category="facture" style="display: none; background: var(--badge-info-bg); color: var(--badge-info-text); border-radius: 4px; padding: 2px 6px; font-size: 10px;">0</span>
                </button>
            </div>
        </div>

        
        <div id="notificationList" style="
            max-height: 400px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--border-color) transparent;
        ">
            
            <div style="padding: 40px 20px; text-align: center;">
                <i class="fas fa-circle-notch fa-spin" style="font-size: 24px; color: var(--text-muted); margin-bottom: 12px; display: block;"></i>
            </div>
        </div>

        
        <div style="
            padding: 12px 20px;
            border-top: 1px solid var(--border-color);
            background: var(--bg-surface);
            text-align: center;
        ">
            <a href="<?php echo e(route('notifications.all')); ?>" style="
                font-size: 12px;
                font-weight: 700;
                color: var(--text-muted);
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                transition: color 0.2s ease;
            " onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-muted)'">
                Voir l'historique complet
            </a>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('.notif-tab');
        
        const colors = {
            'entree': 'var(--mouvement-entree-text)',
            'sortie': 'var(--mouvement-sortie-text)',
            'alerte_stock': 'var(--badge-danger-text)',
            'facture': 'var(--badge-info-text)'
        };

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active from all
                tabs.forEach(t => {
                    t.classList.remove('active');
                    t.style.borderBottomColor = 'transparent';
                    t.style.color = 'var(--text-secondary)';
                    t.style.opacity = '0.7';
                    t.style.fontWeight = '600';
                });
                
                // Set active to current
                this.classList.add('active');
                const cat = this.getAttribute('data-category');
                
                this.style.borderBottomColor = colors[cat] || 'var(--accent)';
                this.style.color = 'var(--text-primary)';
                this.style.opacity = '1';
                this.style.fontWeight = '700';

                // Call global loader function if defined
                if (window.loadCategoryNotifications) {
                    window.loadCategoryNotifications(cat);
                }
            });
        });
    });
</script>
<?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\components\notification-bell.blade.php ENDPATH**/ ?>