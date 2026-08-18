<x-app-layout>
    <x-slot name="header">
        <div class="terminal-header">
            <h2>
                <span class="prompt">root@portfolio:~$</span> touch new_experience.sh
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="admin-page-inner">
            
            <!-- Lien retour -->
            <div class="mb-6">
                <a href="{{ route('admin.experiences.index') }}" class="terminal-link">
                    <span class="prompt">←</span> Retour à la liste
                </a>
            </div>

            <div class="terminal-card">
                <h3 class="text-xl mb-6">
                    <span class="prompt">></span> Nouvelle expérience
                </h3>

                <form action="{{ route('admin.experiences.store') }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      class="experience-form">
                    @csrf

                    <!-- Type d'expérience -->
                    <div class="form-group">
                        <label for="type" class="form-label required">
                            <span class="prompt">></span> Type d'expérience
                        </label>
                        <select name="type" 
                                id="type" 
                                class="form-input @error('type') error @enderror" 
                                required>
                            <option value="">-- Sélectionnez --</option>
                            <option value="work" {{ old('type') === 'work' ? 'selected' : '' }}>
                                💼 Expérience professionnelle
                            </option>
                            <option value="education" {{ old('type') === 'education' ? 'selected' : '' }}>
                                🎓 Formation
                            </option>
                            <option value="certification" {{ old('type') === 'certification' ? 'selected' : '' }}>
                                🏆 Certification
                            </option>
                        </select>
                        @error('type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Titre du poste / Formation -->
                    <div class="form-group">
                        <label for="title" class="form-label required">
                            <span class="prompt">></span> Titre du poste / Formation
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="form-input @error('title') error @enderror"
                               value="{{ old('title') }}"
                               placeholder="Ex: Développeur Full Stack, Master Informatique..."
                               required>
                        @error('title')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Entreprise / École -->
                    <div class="form-group">
                        <label for="company" class="form-label required">
                            <span class="prompt">></span> Entreprise / École
                        </label>
                        <input type="text" 
                               name="company" 
                               id="company" 
                               class="form-input @error('company') error @enderror"
                               value="{{ old('company') }}"
                               placeholder="Ex: Acme Corp, Université de Paris..."
                               required>
                        @error('company')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Lieu -->
                    <div class="form-group">
                        <label for="location" class="form-label">
                            <span class="prompt">></span> Lieu (optionnel)
                        </label>
                        <input type="text" 
                               name="location" 
                               id="location" 
                               class="form-input @error('location') error @enderror"
                               value="{{ old('location') }}"
                               placeholder="Ex: Paris, France - Remote">
                        @error('location')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Dates (côte à côte) -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date" class="form-label required">
                                <span class="prompt">></span> Date de début
                            </label>
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date" 
                                   class="form-input @error('start_date') error @enderror"
                                   value="{{ old('start_date') }}"
                                   required>
                            @error('start_date')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="end_date" class="form-label">
                                <span class="prompt">></span> Date de fin
                            </label>
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date" 
                                   class="form-input @error('end_date') error @enderror"
                                   value="{{ old('end_date') }}"
                                   {{ old('is_current') ? 'disabled' : '' }}>
                            @error('end_date')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Poste actuel -->
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" 
                                   name="is_current" 
                                   id="is_current" 
                                   value="1"
                                   {{ old('is_current') ? 'checked' : '' }}
                                   class="checkbox-input">
                            <span>📍 Poste / Formation en cours</span>
                        </label>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            <span class="prompt">></span> Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  class="form-input @error('description') error @enderror"
                                  placeholder="Description générale du poste, de la formation ou de la certification...">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tâches / Réalisations -->
                    <div class="form-group">
                        <label for="tasks" class="form-label">
                            <span class="prompt">></span> Tâches / Réalisations (une par ligne)
                        </label>
                        <textarea name="tasks" 
                                  id="tasks" 
                                  rows="6" 
                                  class="form-input @error('tasks') error @enderror"
                                  placeholder="Développement de nouvelles fonctionnalités&#10;Gestion d'équipe de 5 développeurs&#10;Migration vers une architecture microservices">{{ old('tasks') }}</textarea>
                        <small class="form-hint">Chaque ligne = une puce dans la timeline</small>
                        @error('tasks')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Technologies -->
                    <div class="form-group">
                        <label for="technologies" class="form-label">
                            <span class="prompt">></span> Technologies utilisées (séparées par des virgules)
                        </label>
                        <input type="text" 
                               name="technologies" 
                               id="technologies" 
                               class="form-input @error('technologies') error @enderror"
                               value="{{ old('technologies') }}"
                               placeholder="PHP, Laravel, Vue.js, MySQL, Docker">
                        <small class="form-hint">Ex: Laravel, React, Node.js, PostgreSQL</small>
                        @error('technologies')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Logo entreprise -->
                    <div class="form-group">
                        <label for="company_logo" class="form-label">
                            <span class="prompt">></span> Logo entreprise / école
                        </label>
                        <input type="file" 
                               name="company_logo" 
                               id="company_logo" 
                               class="form-input-file @error('company_logo') error @enderror"
                               accept="image/*">
                        <small class="form-hint">Format: JPG, PNG, SVG (max 2 Mo)</small>
                        @error('company_logo')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div id="logo-preview" class="image-preview"></div>
                    </div>

                    <!-- URL entreprise -->
                    <div class="form-group">
                        <label for="company_url" class="form-label">
                            <span class="prompt">></span> Site web entreprise (optionnel)
                        </label>
                        <input type="url" 
                               name="company_url" 
                               id="company_url" 
                               class="form-input @error('company_url') error @enderror"
                               value="{{ old('company_url') }}"
                               placeholder="https://www.exemple.com">
                        @error('company_url')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- URL certificat -->
                    <div class="form-group">
                        <label for="certificate_url" class="form-label">
                            <span class="prompt">></span> Lien vers le certificat (optionnel)
                        </label>
                        <input type="url" 
                               name="certificate_url" 
                               id="certificate_url" 
                               class="form-input @error('certificate_url') error @enderror"
                               value="{{ old('certificate_url') }}"
                               placeholder="https://www.certification.com/verify/12345">
                        @error('certificate_url')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Options d'affichage (côte à côte) -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       name="is_visible" 
                                       value="1"
                                       checked
                                       class="checkbox-input">
                                <span>✓ Visible sur le site</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="order" class="form-label">
                                <span class="prompt">></span> Ordre d'affichage
                            </label>
                            <input type="number" 
                                   name="order" 
                                   id="order" 
                                   class="form-input"
                                   value="{{ old('order', 0) }}"
                                   min="0">
                            <small class="form-hint">0 = automatique</small>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="form-actions">
                        <button type="submit" class="terminal-btn primary">
                            <span class="prompt">✓</span> Créer l'expérience
                        </button>
                        <a href="{{ route('admin.experiences.index') }}" class="terminal-btn secondary">
                            <span class="prompt">✗</span> Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>