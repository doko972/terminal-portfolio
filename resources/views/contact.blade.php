@extends('layouts.public')

@section('title', 'Contact')

@section('content')
<style>
    /* Style pour le wrapper reCAPTCHA */
.recaptcha-wrapper {
    margin-top: 10px;
    padding: 15px;
    background: rgba(0, 255, 0, 0.05);
    border: 1px solid #00ff00;
    border-radius: 4px;
    display: inline-block;
}

/* Ajustement pour le thème sombre du terminal */
.recaptcha-wrapper > div {
    transform-origin: 0 0;
}

/* Style pour les messages d'erreur du captcha */
.form-group .error-message {
    display: block;
    color: #ff4444;
    font-size: 0.9em;
    margin-top: 8px;
    padding-left: 20px;
}

/* Animation pour le label du captcha */
.terminal-label {
    display: block;
    margin-bottom: 8px;
    color: #00ff00;
    font-family: 'Courier New', monospace;
}

/* Responsive pour mobile */
@media (max-width: 768px) {
    .recaptcha-wrapper {
        transform: scale(0.85);
        transform-origin: 0 0;
        margin-bottom: 10px;
    }
}
</style>
    <section id="contact" class="terminal-section">
        <div class="container">
            <div class="terminal-window">
                <div class="terminal-header">
                    <span class="terminal-title">> contact.sh</span>
                    <div class="terminal-buttons">
                        <span class="btn-close"></span>
                        <span class="btn-minimize"></span>
                        <span class="btn-maximize"></span>
                    </div>
                </div>

                <div class="terminal-body">
                    <h2 class="section-title">
                        <span class="prompt">root@portfolio:~$</span> cat contact.txt
                    </h2>

                    <!-- Messages de succès/erreur -->
                    @if (session('success'))
                        <div class="terminal-message success">
                            <span class="blink">█</span> {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="terminal-message error">
                            <span class="blink">█</span> {{ session('error') }}
                        </div>
                    @endif

                    <!-- Informations de contact -->
                    <div class="contact-info">
                        <p class="terminal-line">> Email: <a href="mailto:david.grougi@gmail.com"
                                class="terminal-link">david.grougi@gmail.com</a></p>
                        <p class="terminal-line">> GitHub: <a href="https://github.com/doko972" target="_blank"
                                class="terminal-link">github.com/doko972</a></p>
                        <p class="terminal-line terminal-separator">_____________________________________</p>
                    </div>

                    <!-- Formulaire -->
                    <form action="{{ route('contact.send') }}" method="POST" class="contact-form">
                        @csrf

                        <div class="form-group">
                            <label for="nom" class="terminal-label">
                                <span class="prompt">></span> Nom:
                            </label>
                            <input type="text" name="nom" id="nom"
                                class="terminal-input @error('nom') error @enderror" value="{{ old('nom') }}"
                                placeholder="Votre nom..." required>
                            @error('nom')
                                <span class="error-message">! {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="terminal-label">
                                <span class="prompt">></span> Email:
                            </label>
                            <input type="email" name="email" id="email"
                                class="terminal-input @error('email') error @enderror" value="{{ old('email') }}"
                                placeholder="votre@email.com" required>
                            @error('email')
                                <span class="error-message">! {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sujet" class="terminal-label">
                                <span class="prompt">></span> Sujet:
                            </label>
                            <input type="text" name="sujet" id="sujet" class="terminal-input"
                                value="{{ old('sujet') }}" placeholder="Objet du message (optionnel)">
                        </div>

                        <div class="form-group">
                            <label for="message" class="terminal-label">
                                <span class="prompt">></span> Message:
                            </label>
                            <textarea name="message" id="message" rows="6" class="terminal-input @error('message') error @enderror"
                                placeholder="Votre message..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="error-message">! {{ $message }}</span>
                            @enderror
                        </div>

                        <!-- CAPTCHA -->
                        <div class="form-group">
                            <label class="terminal-label">
                                <span class="prompt">></span> Vérification de sécurité:
                            </label>
                            <div class="recaptcha-wrapper">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            </div>
                            @error('g-recaptcha-response')
                                <span class="error-message">! {{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="terminal-button">
                            <span class="prompt">></span> Envoyer le message
                            <span class="blink">_</span>
                        </button>
                    </form>

                    <p class="terminal-footer">
                        <span class="prompt">></span> Merci pour votre message, je vous répond au plus vite !
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Script reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
