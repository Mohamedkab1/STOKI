<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-in" x-data="notificationsPage()">

  <!-- Page Header -->
  <div class="page-header">
      <div>
          <h1 class="page-title">Notifications</h1>
          <p class="text-text-muted mt-1 font-medium italic opacity-80" x-text="unreadTotal + ' non lue(s)'"></p>
      </div>
      <div>
          <button @click="markAllAsRead" class="btn-filter" style="width: auto; background: var(--bg-surface); color: var(--text-primary); border: 1px solid var(--border-color);">
              Tout marquer comme lu
          </button>
      </div>
  </div>

  <!-- Tabs -->
  <div class="tabs-bar">
      
      <button class="tab-btn" :class="{ 'active': activeTab === '' }" @click="setActiveTab('')">
          Tout <span class="tab-count" x-show="unreadTotal > 0" x-text="unreadTotal"></span>
      </button>
      <button class="tab-btn" :class="{ 'active': activeTab === 'entree' }" @click="setActiveTab('entree')">
          Entrées <span class="tab-count" x-show="unreadCategories.entree > 0" x-text="unreadCategories.entree"></span>
      </button>
      <button class="tab-btn" :class="{ 'active': activeTab === 'sortie' }" @click="setActiveTab('sortie')">
          Sorties <span class="tab-count" x-show="unreadCategories.sortie > 0" x-text="unreadCategories.sortie"></span>
      </button>
      <button class="tab-btn" :class="{ 'active': activeTab === 'alerte_stock' }" @click="setActiveTab('alerte_stock')">
          Alertes stock <span class="tab-count" x-show="unreadCategories.alerte_stock > 0" x-text="unreadCategories.alerte_stock"></span>
      </button>
      
  </div>

  <!-- Notifications List -->
  <div class="notif-list">
      <template x-for="notif in notifications" :key="notif.id">
          <div class="notif-row" :class="{ 'unread': !notif.is_read }" @click="markAsRead(notif)">
              <div class="notif-icon-circle" :style="getIconStyle(notif.category || notif.type)">
                  <i :class="notif.icon || getIconClass(notif.category || notif.type)"></i>
              </div>
              <div class="notif-body">
                  <div class="notif-title" x-text="notif.title"></div>
                  <div class="notif-text" x-text="notif.body"></div>
                  <div class="notif-time" x-text="notif.time_ago || 'À l\'instant'"></div>
              </div>
              <template x-if="!notif.is_read">
                  <div class="notif-dot"></div>
              </template>
          </div>
      </template>

      <!-- Empty State -->
      <div x-show="notifications.length === 0" class="products-empty" style="padding: 60px 20px;">
          Aucune notification
      </div>
  </div>

</div>

<script>
function notificationsPage() {
    return {
        activeTab: '',
        notifications: [],
        unreadTotal: 0,
        unreadCategories: { entree: 0, sortie: 0, alerte_stock: 0, facture: 0 },
        
        init() {
            this.fetchUnreadCount();
            this.fetchNotifications('');
        },
        
        setActiveTab(tab) {
            this.activeTab = tab;
            this.fetchNotifications(tab);
        },
        
        getIconStyle(category) {
            const styles = {
                'entree': 'background: var(--badge-success-bg); color: var(--badge-success-text);',
                'sortie': 'background: var(--badge-danger-bg); color: var(--badge-danger-text);',
                'alerte_stock': 'background: var(--badge-warning-bg); color: var(--badge-warning-text);',
                'facture': 'background: var(--badge-info-bg); color: var(--badge-info-text);'
            };
            return styles[category] || 'background: rgba(139, 92, 246, 0.1); color: #8b5cf6;';
        },
        
        getIconClass(category) {
            const icons = {
                'entree': 'fas fa-arrow-down',
                'sortie': 'fas fa-arrow-up',
                'alerte_stock': 'fas fa-exclamation-triangle',
                'facture': 'fas fa-file-invoice'
            };
            return icons[category] || 'fas fa-bell';
        },
        
        async fetchUnreadCount() {
            try {
                // Assuming route notifications/unread-count returns JSON
                const res = await fetch('/notifications/unread-count');
                if (res.ok) {
                    const data = await res.json();
                    this.unreadTotal = data.total || 0;
                    this.unreadCategories = data;
                }
            } catch (e) { console.error('Fetch count error:', e); }
        },
        
        async fetchNotifications(category) {
            this.notifications = [];
            // L'URL pour "Tout" est /notifications, sinon /notifications/category/{id}
            let url = category === '' ? '/notifications' : `/notifications/category/${category}`;
            
            try {
                const res = await fetch(url);
                if (res.ok) {
                    const data = await res.json();
                    this.notifications = data.notifications || [];
                }
            } catch (e) {
                console.error('Fetch notifs error:', e);
            }
        },
        
        async markAsRead(notif) {
            if (notif.is_read) return;
            notif.is_read = true;
            this.unreadTotal = Math.max(0, this.unreadTotal - 1);
            if(this.unreadCategories[notif.category]) {
                this.unreadCategories[notif.category] = Math.max(0, this.unreadCategories[notif.category] - 1);
            }
            
            try {
                await fetch(`/notifications/${notif.id}/read`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' }
                });
            } catch (e) {}
        },
        
        async markAllAsRead() {
            try {
                let url = '/notifications/read-all';
                let data = {};
                if (this.activeTab !== '') {
                    data.category = this.activeTab;
                }
                
                await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                // Rafraîchir UI
                this.notifications.forEach(n => n.is_read = true);
                this.fetchUnreadCount();
            } catch (e) {}
        }
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pc\OneDrive - OFPPT\Bureau\ALL FOLDERS\PROJETS REALISER\app-GESTION_STOCK\GESTION-STOCK\resources\views\notifications\index.blade.php ENDPATH**/ ?>