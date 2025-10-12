// ==========================================
// PORTFOLIO - Modal et Filtres
// ==========================================

// Récupérer les données des projets
let projectsData = {};

document.addEventListener('DOMContentLoaded', function() {
    const portfolioSection = document.getElementById('portfolio');
    if (portfolioSection && portfolioSection.dataset.projects) {
        projectsData = JSON.parse(portfolioSection.dataset.projects);
    }
});

// Fonction pour ouvrir le modal avec les détails du projet
// Fonction pour ouvrir le modal avec les détails du projet
window.openProjectModal = async function(projectId) {
    try {
        const response = await fetch(`/api/project/${projectId}`);
        const project = await response.json();
        
        // Parser les technologies si c'est une string JSON
        const technologiesArray = typeof project.technologies === 'string' 
            ? JSON.parse(project.technologies) 
            : project.technologies;
        
        const modalContent = `
            ${project.image ? `<img src="/storage/${project.image}" alt="${project.title}" class="modal-project-image">` : ''}
            
            <h2 class="modal-project-title">
                ${project.title}
                ${project.is_featured ? '<span class="featured-badge">★</span>' : ''}
            </h2>
            
            <p class="modal-project-description">${project.description}</p>
            
            <div class="modal-section">
                <h3><span class="prompt">></span> Technologies utilisées</h3>
                <div class="project-technologies">
                    ${technologiesArray.map(tech => `<span class="tech-badge">${tech}</span>`).join('')}
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

        document.getElementById('modalProjectContent').innerHTML = modalContent;
        document.getElementById('projectModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    } catch (error) {
        console.error('Erreur lors du chargement du projet:', error);
    }
};

// Reste du code identique (closeProjectModal, filtres, etc.)

// Fonction pour fermer le modal
window.closeProjectModal = function() {
    document.getElementById('projectModal').classList.remove('active');
    document.body.style.overflow = 'auto';
};

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        window.closeProjectModal();
    }
});

// Filtres par technologies
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.projects-grid .project-card');

    if (filterButtons.length === 0) return; // Pas de filtres sur cette page

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
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