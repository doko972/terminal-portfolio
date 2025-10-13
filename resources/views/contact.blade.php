@extends('layouts.public')

@section('title', 'Contact')

@section('content')
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
                        <p class="terminal-line">> Email: <a href="mailto:votre@email.com"
                                class="terminal-link">david.grougi@gmail.com</a></p>
                        <p class="terminal-line">> GitHub: <a href="https://github.com/votre-username" target="_blank"
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
@endsection
