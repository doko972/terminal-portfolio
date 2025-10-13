// ====================================
// ADMIN - Expériences Form JavaScript
// ====================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // Gestion du checkbox "Poste en cours"
    // ==========================================
    const isCurrentCheckbox = document.getElementById('is_current');
    const endDateInput = document.getElementById('end_date');
    
    if (isCurrentCheckbox && endDateInput) {
        isCurrentCheckbox.addEventListener('change', function() {
            if (this.checked) {
                endDateInput.value = '';
                endDateInput.disabled = true;
                endDateInput.style.opacity = '0.5';
            } else {
                endDateInput.disabled = false;
                endDateInput.style.opacity = '1';
            }
        });
        
        // État initial
        if (isCurrentCheckbox.checked) {
            endDateInput.disabled = true;
            endDateInput.style.opacity = '0.5';
        }
    }
    
    // ==========================================
    // Preview du logo uploadé
    // ==========================================
    const logoInput = document.getElementById('company_logo');
    const logoPreview = document.getElementById('logo-preview');
    
    if (logoInput && logoPreview) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Vérifier que c'est une image
                if (!file.type.startsWith('image/')) {
                    alert('⚠️ Veuillez sélectionner une image valide');
                    this.value = '';
                    return;
                }
                
                // Vérifier la taille (2 Mo max)
                const maxSize = 2 * 1024 * 1024; // 2 Mo en bytes
                if (file.size > maxSize) {
                    alert('⚠️ L\'image ne doit pas dépasser 2 Mo');
                    this.value = '';
                    return;
                }
                
                // Créer le preview
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    logoPreview.innerHTML = `
                        <img src="${event.target.result}" 
                             alt="Preview logo" 
                             style="max-width: 200px; max-height: 200px; border: 2px solid rgba(14, 224, 39, 0.3); border-radius: 4px; padding: 8px; background: rgba(0, 0, 0, 0.3);">
                    `;
                    logoPreview.classList.add('active');
                };
                
                reader.readAsDataURL(file);
            } else {
                logoPreview.innerHTML = '';
                logoPreview.classList.remove('active');
            }
        });
    }
    
    // ==========================================
    // Validation avant soumission
    // ==========================================
    const experienceForm = document.querySelector('.experience-form');
    
    if (experienceForm) {
        experienceForm.addEventListener('submit', function(e) {
            const startDate = document.getElementById('start_date')?.value;
            const endDate = document.getElementById('end_date')?.value;
            const isCurrent = document.getElementById('is_current')?.checked;
            
            // Vérifier que la date de fin est après la date de début
            if (startDate && endDate && !isCurrent) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end < start) {
                    e.preventDefault();
                    alert('⚠️ La date de fin doit être après la date de début');
                    return false;
                }
            }
            
            // Vérifier qu'au moins un champ est rempli parmi description/tasks
            const description = document.getElementById('description')?.value.trim();
            const tasks = document.getElementById('tasks')?.value.trim();
            
            if (!description && !tasks) {
                const confirm = window.confirm('⚠️ Vous n\'avez renseigné ni description ni tâches. Continuer quand même ?');
                if (!confirm) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    }
    
    // ==========================================
    // Auto-focus sur le premier champ avec erreur
    // ==========================================
    const firstError = document.querySelector('.form-input.error');
    if (firstError) {
        firstError.focus();
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // ==========================================
    // Compteur de caractères pour les textareas (optionnel)
    // ==========================================
    const textareas = document.querySelectorAll('textarea.form-input');
    
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');
        
        if (maxLength) {
            const counter = document.createElement('small');
            counter.className = 'form-hint';
            counter.style.textAlign = 'right';
            textarea.parentNode.appendChild(counter);
            
            function updateCounter() {
                const remaining = maxLength - textarea.value.length;
                counter.textContent = `${remaining} caractères restants`;
                
                if (remaining < 50) {
                    counter.style.color = '#ef4444';
                } else {
                    counter.style.color = 'rgba(14, 224, 39, 0.6)';
                }
            }
            
            textarea.addEventListener('input', updateCounter);
            updateCounter();
        }
    });
    
    // ==========================================
    // Helper: Formater les technologies en temps réel
    // ==========================================
    const techInput = document.getElementById('technologies');
    
    if (techInput) {
        techInput.addEventListener('blur', function() {
            // Nettoyer et formater les technologies
            let technologies = this.value
                .split(',')
                .map(tech => tech.trim())
                .filter(tech => tech.length > 0)
                .join(', ');
            
            this.value = technologies;
        });
    }
});