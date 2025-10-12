@extends('layouts.auth')

@section('title', 'Vérifier l\'email')

@section('auth-title', 'Vérification email')

@section('content')
<div class="auth-description">
    <p>Merci de vous être inscrit ! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer ?</p>
    <p style="margin-top: 10px;">Si vous n'avez pas reçu l'e-mail, nous vous en enverrons un autre avec plaisir.</p>
</div>

@if (session('status') == 'verification-link-sent')
    <div class="alert alert-success">
        Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
    </div>
@endif

<div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-top: 20px;">
    <form method="POST" action="{{ route('verification.send') }}" style="flex: 1;">
        @csrf
        <button type="submit" class="btn-primary">
            Renvoyer l'email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="flex: 1;">
        @csrf
        <button type="submit" class="btn-primary" style="background-color: rgba(255, 68, 68, 0.1); border-color: #ff4444;">
            Se déconnecter
        </button>
    </form>
</div>
@endsection