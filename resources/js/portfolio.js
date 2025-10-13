window.projectsData = {};

document.addEventListener('DOMContentLoaded', function () {
    const portfolioSection = document.getElementById('portfolio');
    if (portfolioSection && portfolioSection.dataset.projects) {
        window.projectsData = JSON.parse(portfolioSection.dataset.projects);
    }
});
// ==========================================
// Fonction pour ouvrir le modal avec les détails du projet
// ==========================================

window.openProjectModal = function (projectId) {
    const project = window.projectsData[projectId];
    if (!project) return;

    // Parser les technologies
    let technologies = [];
    if (typeof project.technologies === 'string') {
        try {
            technologies = JSON.parse(project.technologies);
        } catch (e) {
            console.error('Erreur parsing technologies:', e);
            technologies = [];
        }
    } else if (Array.isArray(project.technologies)) {
        technologies = project.technologies;
    } else if (Array.isArray(project.technologies_array)) {
        technologies = project.technologies_array;
    }

    // Récupérer les images du projet
    const images = project.images || [];
    const hasMultipleImages = images.length > 1;

    // Construire le carrousel d'images
    let imagesHTML = '';
    if (images.length > 0) {
        imagesHTML = `
            <div class="modal-gallery">
                <div class="gallery-main">
<img src="/storage/${images[0].image_path}" 
     alt="${project.title}" 
     class="modal-project-image clickable"
     id="mainGalleryImage"
     onclick="openLightbox()">
                    ${hasMultipleImages ? `
                        <button class="gallery-nav gallery-prev" onclick="changeGalleryImage(-1)">
                            <span>‹</span>
                        </button>
                        <button class="gallery-nav gallery-next" onclick="changeGalleryImage(1)">
                            <span>›</span>
                        </button>
                        <div class="gallery-counter">
                            <span id="currentImageIndex">1</span> / ${images.length}
                        </div>
                    ` : ''}
                </div>
                ${hasMultipleImages ? `
                    <div class="gallery-thumbnails">
                        ${images.map((img, index) => `
                            <img src="/storage/${img.image_path}" 
                                 alt="Miniature ${index + 1}"
                                 class="gallery-thumbnail ${index === 0 ? 'active' : ''}"
                                 onclick="selectGalleryImage(${index})"
                                 data-index="${index}">
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        `;
    }

    const modalContent = `
        ${imagesHTML}
        
        <h2 class="modal-project-title">
            ${project.title}
            ${project.is_featured ? '<span class="featured-badge">★</span>' : ''}
        </h2>
        
        <p class="modal-project-description">${project.description}</p>
        
        <div class="modal-section">
            <h3><span class="prompt">></span> Technologies utilisées</h3>
            <div class="project-technologies">
                ${technologies.map(tech => `<span class="tech-badge">${tech}</span>`).join('')}
            </div>
        </div>
        
        ${project.completed_at ? `
        <div class="modal-section">
            <h3><span class="prompt">></span> Date de réalisation</h3>
            <p class="project-date"><span class="prompt">📅</span> ${new Date(project.completed_at).getFullYear()}</p>
        </div>
        ` : ''}
        
        ${(project.url || project.github_url) ? `
        <div class="modal-section">
            <h3><span class="prompt">></span> Liens</h3>
            <div class="modal-project-links">
                ${project.url ? `<a href="${project.url}" target="_blank"><span class="prompt">🌐</span> Voir le site</a>` : ''}
                ${project.github_url ? `<a href="${project.github_url}" target="_blank"><span class="prompt">💻</span> Voir sur GitHub</a>` : ''}
            </div>
        </div>
        ` : ''}
    `;
// ==========================================
// Stocker les images pour la navigation
// ==========================================

    window.currentGalleryImages = images;
    window.currentImageIndex = 0;

    document.getElementById('modalProjectContent').innerHTML = modalContent;
    document.getElementById('projectModal').classList.add('active');
    document.body.style.overflow = 'hidden';
};
// ==========================================
// Fonction pour changer d'image dans le carrousel
// ==========================================
window.changeGalleryImage = function (direction) {
    if (!window.currentGalleryImages || window.currentGalleryImages.length <= 1) return;

    window.currentImageIndex += direction;

    // Boucler au début/fin
    if (window.currentImageIndex < 0) {
        window.currentImageIndex = window.currentGalleryImages.length - 1;
    } else if (window.currentImageIndex >= window.currentGalleryImages.length) {
        window.currentImageIndex = 0;
    }

    updateGalleryDisplay();
};
// ==========================================
// Fonction pour sélectionner une image via les miniatures
// ==========================================
window.selectGalleryImage = function (index) {
    window.currentImageIndex = index;
    updateGalleryDisplay();
};
// ==========================================
// Fonction pour mettre à jour l'affichage de la galerie
// ==========================================
function updateGalleryDisplay() {
    const mainImage = document.getElementById('mainGalleryImage');
    const counter = document.getElementById('currentImageIndex');
    const thumbnails = document.querySelectorAll('.gallery-thumbnail');

    if (mainImage && window.currentGalleryImages[window.currentImageIndex]) {
        mainImage.src = `/storage/${window.currentGalleryImages[window.currentImageIndex].image_path}`;

        // Mettre à jour le compteur
        if (counter) {
            counter.textContent = window.currentImageIndex + 1;
        }

        // Mettre à jour les miniatures actives
        thumbnails.forEach((thumb, index) => {
            thumb.classList.toggle('active', index === window.currentImageIndex);
        });
    }
}
// ==========================================
// Fonction pour fermer le modal
// ==========================================
window.closeProjectModal = function () {
    document.getElementById('projectModal').classList.remove('active');
    document.body.style.overflow = 'auto';
};
// ==========================================
// Fermer le modal avec la touche Échap et naviguer avec les flèches
// ==========================================
document.addEventListener('keydown', function (e) {
    const modal = document.getElementById('projectModal');

    if (e.key === 'Escape') {
        window.closeProjectModal();
    }

    // Navigation au clavier dans la galerie
    if (modal && modal.classList.contains('active')) {
        if (e.key === 'ArrowLeft') {
            window.changeGalleryImage(-1);
        } else if (e.key === 'ArrowRight') {
            window.changeGalleryImage(1);
        }
    }
});
// ==========================================
// Filtres par technologies
// ==========================================
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.projects-grid .project-card');

    if (filterButtons.length === 0) return;

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const filter = this.getAttribute('data-filter');

            // Mettre à jour les boutons actifs
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Filtrer les projets
            projectCards.forEach(card => {
                if (filter === 'all') {
                    card.classList.remove('hidden');
                } else {
                    const technologies = card.getAttribute('data-technologies');
                    if (technologies.includes(filter)) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                }
            });

            // Animation de filtre
            projectCards.forEach((card, index) => {
                if (!card.classList.contains('hidden')) {
                    card.style.animation = 'none';
                    setTimeout(() => {
                        card.style.animation = `fadeInUp 0.5s ease-out ${index * 0.1}s both`;
                    }, 10);
                }
            });
        });
    });
});
// ==========================================
// LIGHTBOX PLEIN ÉCRAN
// ==========================================

// Ouvrir la lightbox
window.openLightbox = function (imageIndex = null) {
    if (!window.currentGalleryImages || window.currentGalleryImages.length === 0) return;

    if (imageIndex !== null) {
        window.currentImageIndex = imageIndex;
    }

    const lightbox = document.getElementById('imageLightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxIndexSpan = document.getElementById('lightboxImageIndex');
    const lightboxTotalSpan = document.getElementById('lightboxImageTotal');

    // Afficher l'image
    lightboxImage.src = `/storage/${window.currentGalleryImages[window.currentImageIndex].image_path}`;
    lightboxIndexSpan.textContent = window.currentImageIndex + 1;
    lightboxTotalSpan.textContent = window.currentGalleryImages.length;

    // Afficher la lightbox
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
};

// Fermer la lightbox
window.closeLightbox = function () {
    const lightbox = document.getElementById('imageLightbox');
    lightbox.classList.remove('active');
    document.body.style.overflow = 'auto';
};

// Naviguer dans la lightbox
window.navigateLightbox = function (direction) {
    if (!window.currentGalleryImages || window.currentGalleryImages.length === 0) return;

    window.currentImageIndex += direction;

    // Boucler au début/fin
    if (window.currentImageIndex < 0) {
        window.currentImageIndex = window.currentGalleryImages.length - 1;
    } else if (window.currentImageIndex >= window.currentGalleryImages.length) {
        window.currentImageIndex = 0;
    }

    // Mettre à jour l'affichage
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxIndexSpan = document.getElementById('lightboxImageIndex');

    lightboxImage.src = `/storage/${window.currentGalleryImages[window.currentImageIndex].image_path}`;
    lightboxIndexSpan.textContent = window.currentImageIndex + 1;

    // Mettre à jour aussi la galerie du modal si elle est visible
    updateGalleryDisplay();
};

document.addEventListener('keydown', function (e) {
    const modal = document.getElementById('projectModal');
    const lightbox = document.getElementById('imageLightbox');
    
    if (e.key === 'Escape') {
        if (lightbox && lightbox.classList.contains('active')) {
            window.closeLightbox();
        } else {
            window.closeProjectModal();
        }
    }
    
    // Navigation au clavier dans la galerie ou lightbox
    if ((modal && modal.classList.contains('active')) || (lightbox && lightbox.classList.contains('active'))) {
        if (e.key === 'ArrowLeft') {
            if (lightbox && lightbox.classList.contains('active')) {
                window.navigateLightbox(-1);
            } else {
                window.changeGalleryImage(-1);
            }
        } else if (e.key === 'ArrowRight') {
            if (lightbox && lightbox.classList.contains('active')) {
                window.navigateLightbox(1);
            } else {
                window.changeGalleryImage(1);
            }
        }
    }
});
