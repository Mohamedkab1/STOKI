@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="space-y-8 animate-in" x-data="{ activeSection: 'general' }">

  <!-- Page Header -->
  <div class="page-header">
      <div>
          <h1 class="page-title">Paramètres</h1>
          <p class="text-text-muted mt-1 font-medium italic opacity-80">Gérez vos préférences et paramètres système.</p>
      </div>
  </div>

  <!-- Layout -->
  <div class="settings-layout">
      <!-- Sidebar Nav -->
      <div class="settings-nav">
          <button class="settings-nav-item" :class="{ 'active': activeSection === 'general' }" @click="activeSection = 'general'">
              Général
          </button>
          <button class="settings-nav-item" :class="{ 'active': activeSection === 'entreprise' }" @click="activeSection = 'entreprise'">
              Entreprise
          </button>
          <button class="settings-nav-item" :class="{ 'active': activeSection === 'notifications' }" @click="activeSection = 'notifications'">
              Notifications
          </button>
          <button class="settings-nav-item" :class="{ 'active': activeSection === 'securite' }" @click="activeSection = 'securite'">
              Sécurité
          </button>
      </div>

      <!-- Main Content -->
      <div class="settings-content">
          
          <!-- Général -->
          <div class="settings-section" :class="{ 'active': activeSection === 'general' }">
              <div class="settings-card">
                  <h2 class="text-lg font-medium text-text-primary mb-6">Préférences Générales</h2>
                  <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                      @csrf
                      @method('PUT')
                      <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
                          <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                          <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                          <div>
                              <label class="block text-[13px] font-medium text-text-primary mb-1">Fuseau horaire</label>
                              <select name="timezone" class="w-full h-10 rounded-lg border border-border-color bg-bg-surface px-3 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary">
                                  <option value="Africa/Casablanca" {{ auth()->user()->timezone == 'Africa/Casablanca' ? 'selected' : '' }}>Africa/Casablanca (GMT+1)</option>
                                  <option value="Europe/Paris" {{ auth()->user()->timezone == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (GMT+1/GMT+2)</option>
                              </select>
                          </div>
                          <div>
                              <label class="block text-[13px] font-medium text-text-primary mb-1">Format de date</label>
                              <select name="date_format" class="w-full h-10 rounded-lg border border-border-color bg-bg-surface px-3 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary">
                                  <option value="d/m/Y" {{ auth()->user()->date_format == 'd/m/Y' ? 'selected' : '' }}>JJ/MM/AAAA (ex: 31/12/2026)</option>
                                  <option value="Y-m-d" {{ auth()->user()->date_format == 'Y-m-d' ? 'selected' : '' }}>AAAA-MM-JJ (ex: 2026-12-31)</option>
                              </select>
                          </div>
                          <div>
                              <label class="block text-[13px] font-medium text-text-primary mb-1">Thème (Mode sombre)</label>
                              <label class="toggle-switch mt-2">
                                  <input type="checkbox" id="theme-toggle" :checked="document.documentElement.getAttribute('data-theme') === 'dark'" @change="document.documentElement.setAttribute('data-theme', $event.target.checked ? 'dark' : 'light'); localStorage.setItem('theme', $event.target.checked ? 'dark' : 'light')">
                                  <div class="toggle-slider"></div>
                              </label>
                          </div>
                      </div>
                      
                      <div class="pt-2">
                          <button type="submit" class="btn-filter">Enregistrer les préférences</button>
                      </div>
                  </form>
              </div>
          </div>

          <!-- Entreprise -->
          <div class="settings-section" :class="{ 'active': activeSection === 'entreprise' }">
              <div class="settings-card">
                  <h2 class="text-lg font-medium text-text-primary mb-6">Profil de l'Entreprise / Personnel</h2>
                  <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                      @csrf
                      @method('PUT')
                      <div class="space-y-4">
                          <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
                              <div>
                                  <label class="block text-[13px] font-medium text-text-primary mb-1">Nom complet</label>
                                  <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full h-10 rounded-lg border border-border-color bg-bg-surface px-3 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary">
                              </div>
                              <div>
                                  <label class="block text-[13px] font-medium text-text-primary mb-1">Email de contact</label>
                                  <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full h-10 rounded-lg border border-border-color bg-bg-surface px-3 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary">
                              </div>
                              <div>
                                  <label class="block text-[13px] font-medium text-text-primary mb-1">Téléphone</label>
                                  <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="+212 6..." class="w-full h-10 rounded-lg border border-border-color bg-bg-surface px-3 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary">
                              </div>
                          </div>

                          <div>
                              <label class="block text-[13px] font-medium text-text-primary mb-1">Adresse</label>
                              <textarea name="address" rows="2" class="w-full rounded-lg border border-border-color bg-bg-surface px-3 py-2 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary">{{ old('address', auth()->user()->address) }}</textarea>
                          </div>
                      </div>

                      <div class="pt-2">
                          <button type="submit" class="btn-filter">Enregistrer les modifications</button>
                      </div>
                  </form>
              </div>
          </div>

          <!-- Notifications -->
          <div class="settings-section" :class="{ 'active': activeSection === 'notifications' }">
              <div class="settings-card">
                  <h2 class="text-lg font-medium text-text-primary mb-6">Préférences de Notification</h2>
                  <form action="#" method="POST" class="space-y-2">
                      @csrf
                      <label class="toggle-switch">
                          <input type="checkbox" checked>
                          <div class="toggle-slider"></div>
                          <span class="toggle-label">Notifier les entrées de stock</span>
                      </label>
                      <label class="toggle-switch">
                          <input type="checkbox" checked>
                          <div class="toggle-slider"></div>
                          <span class="toggle-label">Notifier les sorties de stock</span>
                      </label>
                      <label class="toggle-switch">
                          <input type="checkbox" checked>
                          <div class="toggle-slider"></div>
                          <span class="toggle-label">Alertes de stock faible</span>
                      </label>
                      <label class="toggle-switch">
                          <input type="checkbox" checked>
                          <div class="toggle-slider"></div>
                          <span class="toggle-label">Notifications des factures</span>
                      </label>

                      <div class="pt-6">
                          <button type="button" class="btn-filter">Sauvegarder</button>
                      </div>
                  </form>
              </div>
          </div>

          <!-- Sécurité -->
          <div class="settings-section" :class="{ 'active': activeSection === 'securite' }">
              <div class="settings-card">
                  <h2 class="text-lg font-medium text-text-primary mb-6">Mot de Passe et Sécurité</h2>
                  <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                      @csrf
                      @method('PUT')
                      
                      <div>
                          <label class="block text-[13px] font-medium text-text-primary mb-1">Mot de passe actuel</label>
                          <input type="password" name="current_password" required class="w-full h-10 rounded-lg border border-border-color bg-bg-surface px-3 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary">
                      </div>

                      <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
                          <div>
                              <label class="block text-[13px] font-medium text-text-primary mb-1">Nouveau mot de passe</label>
                              <input type="password" name="password" required class="w-full h-10 rounded-lg border border-border-color bg-bg-surface px-3 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary">
                          </div>
                          <div>
                              <label class="block text-[13px] font-medium text-text-primary mb-1">Confirmer le mot de passe</label>
                              <input type="password" name="password_confirmation" required class="w-full h-10 rounded-lg border border-border-color bg-bg-surface px-3 text-[13px] text-text-primary focus:outline-none focus:border-brand-primary">
                          </div>
                      </div>

                      <div class="pt-2">
                          <button type="submit" class="btn-filter" style="background: var(--bg-surface); color: var(--text-primary); border: 1px solid var(--border-color);">Changer le mot de passe</button>
                      </div>
                  </form>
              </div>
          </div>
          
      </div>
  </div>

</div>
@endsection
