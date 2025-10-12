@extends('layouts.terminal')

@section('title', 'Profil')

@section('content')
<div class="container">
    <section class="profile-section">
        <h2 class="section-title">Mon Profil</h2>

        <!-- Message de succès -->
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success">
                Profil mis à jour avec succès.
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="alert alert-success">
                Mot de passe modifié avec succès.
            </div>
        @endif

        <!-- Informations du profil -->
        <div class="profile-card">
            <h3 class="profile-card-title">Informations personnelles</h3>
            
            <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
                @csrf
                @method('PATCH')

                <!-- Name -->
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                        autofocus
                    >
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}" 
                        required
                    >
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <p class="email-unverified">
                            Votre adresse e-mail n'est pas vérifiée.
                            <form method="POST" action="{{ route('verification.send') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="link-button">
                                    Cliquez ici pour renvoyer l'e-mail de vérification.
                                </button>
                            </form>
                        </p>
                    @endif
                </div>

                <!-- Bouton Submit -->
                <button type="submit" class="btn-primary">
                    Enregistrer
                </button>
            </form>
        </div>

        <!-- Changer le mot de passe -->
        <div class="profile-card">
            <h3 class="profile-card-title">Changer le mot de passe</h3>
            
            <form method="POST" action="{{ route('password.update') }}" class="profile-form">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div class="form-group">
                    <label for="current_password">Mot de passe actuel</label>
                    <input 
                        id="current_password" 
                        type="password" 
                        name="current_password" 
                        required
                        autocomplete="current-password"
                    >
                    @error('current_password', 'updatePassword')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required
                        autocomplete="new-password"
                    >
                    @error('password', 'updatePassword')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required
                        autocomplete="new-password"
                    >
                    @error('password_confirmation', 'updatePassword')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Bouton Submit -->
                <button type="submit" class="btn-primary">
                    Modifier le mot de passe
                </button>
            </form>
        </div>

        <!-- Supprimer le compte -->
        <div class="profile-card profile-card-danger">
            <h3 class="profile-card-title">Zone dangereuse</h3>
            
            <p class="danger-warning">
                ⚠️ La suppression de votre compte est permanente. Toutes vos données seront définitivement supprimées.
            </p>

            <button 
                type="button" 
                class="btn-danger"
                onclick="document.getElementById('deleteModal').style.display='flex'"
            >
                Supprimer mon compte
            </button>
        </div>
    </section>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Confirmer la suppression</h3>
        <p class="modal-text">
            Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}" class="modal-form">
            @csrf
            @method('DELETE')

            <div class="form-group">
                <label for="password_delete">Mot de passe</label>
                <input 
                    id="password_delete" 
                    type="password" 
                    name="password" 
                    required
                    placeholder="Confirmez avec votre mot de passe"
                >
                @error('password', 'userDeletion')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="modal-actions">
                <button 
                    type="button" 
                    class="btn-secondary"
                    onclick="document.getElementById('deleteModal').style.display='none'"
                >
                    Annuler
                </button>
                <button type="submit" class="btn-danger">
                    Supprimer définitivement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection