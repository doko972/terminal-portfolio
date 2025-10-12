@extends('layouts.auth')

@section('title', 'Connexion')

@section('auth-title', 'Connexion')

@section('content')
<form method="POST" action="{{ route('login') }}" class="auth-form">
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
            autocomplete="username"
            placeholder="user@terminal.sys"
        >
        @error('email')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input 
            id="password" 
            type="password" 
            name="password" 
            required 
            autocomplete="current-password"
            placeholder="••••••••"
        >
        @error('password')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="form-checkbox">
        <input 
            id="remember_me" 
            type="checkbox" 
            name="remember"
        >
        <label for="remember_me">Se souvenir de moi</label>
    </div>

    <!-- Liens -->
    <div class="form-links">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                Mot de passe oublié ?
            </a>
        @endif
    </div>

    <!-- Bouton Submit -->
    <button type="submit">
        Se connecter
    </button>

    <!-- Lien inscription -->
    <div class="auth-alternate">
        Pas encore de compte ? 
        <a href="{{ route('register') }}">S'inscrire</a>
    </div>
</form>
@endsection