@extends('layouts.auth')

@section('title', 'Mot de passe oublié')

@section('auth-title', 'Réinitialisation')

@section('content')
<div class="auth-description">
    <p>Mot de passe oublié ? Aucun problème. Indiquez-nous votre adresse e-mail et nous vous enverrons un lien de réinitialisation.</p>
</div>

<form method="POST" action="{{ route('password.email') }}" class="auth-form">
    @csrf

    <!-- Email -->
    <div class="form-group">
        <label for="email">Email</label>
        <input 
            id="email" 
            type="email" 
            name="email" 
            value="{{ old('email') }}" 
            required 
            autofocus
            placeholder="user@terminal.sys"
        >
        @error('email')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <!-- Bouton Submit -->
    <button type="submit">
        Envoyer le lien
    </button>

    <!-- Lien retour connexion -->
    <div class="auth-alternate">
        <a href="{{ route('login') }}">Retour à la connexion</a>
    </div>
</form>
@endsection