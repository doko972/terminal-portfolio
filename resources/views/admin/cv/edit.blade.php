@php use App\Support\Cv; @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="terminal-header">
            <h2>
                <span class="prompt">root@portfolio:~$</span> vim cv.pdf
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="admin-page-inner">

            @if (session('success'))
                <div class="terminal-alert success">
                    <span class="prompt">✓</span> {{ session('success') }}
                </div>
            @endif

            <div class="terminal-card">
                <h3 class="section-subtitle">
                    <span class="prompt">></span> CV téléchargeable
                </h3>

                {{-- État courant --}}
                @if (Cv::exists())
                    <div class="cv-status online">
                        <p>
                            <span class="prompt">●</span> Un CV est en ligne
                        </p>
                        <ul class="cv-meta">
                            <li><span class="prompt">></span> Poids : {{ Cv::humanSize() }}</li>
                            <li><span class="prompt">></span> Mis en ligne le
                                {{ Cv::updatedAt()->timezone(config('app.timezone'))->format('d/m/Y à H:i') }}
                            </li>
                            <li>
                                <span class="prompt">></span>
                                <a href="{{ route('cv.download') }}" class="terminal-link" target="_blank"
                                   rel="noopener noreferrer">
                                    Télécharger pour vérifier
                                </a>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="cv-status offline">
                        <p>
                            <span class="prompt">○</span> Aucun CV en ligne — le bouton de téléchargement
                            reste masqué sur la page d'accueil.
                        </p>
                    </div>
                @endif

                {{-- Téléversement --}}
                <form action="{{ route('admin.cv.update') }}" method="POST" enctype="multipart/form-data"
                      class="cv-form">
                    @csrf

                    <div class="form-group">
                        <label for="cv" class="form-label required">
                            <span class="prompt">></span>
                            {{ Cv::exists() ? 'Remplacer le CV' : 'Déposer le CV' }}
                        </label>

                        <input type="file" name="cv" id="cv" accept="application/pdf"
                               class="terminal-input-file @error('cv') error @enderror" required>

                        <small class="form-hint">
                            PDF uniquement, {{ round(Cv::MAX_KB / 1024, 1) }} Mo maximum.
                            @if (Cv::exists())
                                Le fichier actuel sera écrasé.
                            @endif
                        </small>

                        @error('cv')
                            <span class="error-message">! {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="terminal-btn primary">
                            <span class="prompt">✓</span> Mettre en ligne
                        </button>
                        <a href="{{ route('dashboard') }}" class="terminal-btn secondary">
                            <span class="prompt">←</span> Retour
                        </a>
                    </div>
                </form>

                {{-- Retrait --}}
                @if (Cv::exists())
                    <form action="{{ route('admin.cv.destroy') }}" method="POST" class="cv-delete-form"
                          onsubmit="return confirm('Retirer le CV du site ? Le bouton de téléchargement disparaîtra de la page d\'accueil.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="terminal-btn danger">
                            <span class="prompt">✗</span> Retirer le CV du site
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
