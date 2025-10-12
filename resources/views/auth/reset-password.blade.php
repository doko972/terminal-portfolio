@extends('layouts.auth')

@section('title', 'Réinitialiser le mot de passe')

@section('auth-title', 'Nouveau mot de passe')

@section('content')
<form method="POST" action="{{ route('password.store') }}" class="auth-form">
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email -->
    <div class="form-group">
        <label for="email">Email</label>
        <input 
            id="email" 
            type="email" 
            name="email" 
            value="{{ old('email', $request->email) }}" 
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
        <label for="password">Nouveau mot de passe</label>
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
        Réinitialiser le mot de passe
    </button>
</form>
@endsection