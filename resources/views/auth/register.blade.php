@extends('layouts.auth')

@section('title', 'Inscription')

@section('auth-title', 'Inscription')

@section('content')
<form method="POST" action="{{ route('register') }}" class="auth-form">
    @csrf

    <!-- Name -->
    <div class="form-group">
        <label for="name">Nom</label>
        <input 
            id="name" 
            type="text" 
            name="name" 
            value="{{ old('name') }}" 
            required 
            autofocus 
            autocomplete="name"
            placeholder="John Doe"
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
            value="{{ old('email') }}" 
            required 
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
            autocomplete="new-password"
            placeholder="••••••••"
        >
        @error('password')
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
            placeholder="••••••••"
        >
        @error('password_confirmation')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <!-- Bouton Submit -->
    <button type="submit">
        S'inscrire
    </button>

    <!-- Lien connexion -->
    <div class="auth-alternate">
        Déjà un compte ? 
        <a href="{{ route('login') }}">Se connecter</a>
    </div>
</form>
@endsection