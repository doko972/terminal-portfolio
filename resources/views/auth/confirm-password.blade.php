@extends('layouts.auth')

@section('title', 'Confirmer le mot de passe')

@section('auth-title', 'Zone sécurisée')

@section('content')
<div class="auth-description">
    <p>Ceci est une zone sécurisée de l'application. Veuillez confirmer votre mot de passe avant de continuer.</p>
</div>

<form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
    @csrf

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

    <!-- Bouton Submit -->
    <button type="submit">
        Confirmer
    </button>
</form>
@endsection