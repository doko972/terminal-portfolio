// ====================================
// TIMELINE - JavaScript Interactif
// ====================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. FILTRES PAR TYPE
    // ==========================================
    const filterButtons = document.querySelectorAll('.filter-btn');
    const timelineItems = document.querySelectorAll('.timeline-item');
    
    if (filterButtons.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Mettre à jour les boutons actifs
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filtrer les items
                timelineItems.forEach(item => {
                    const itemType = item.getAttribute('data-type');
                    
                    if (filter === 'all' || filter === itemType) {
                        item.classList.remove('hidden');
                        // Réanimer l'item
                        item.style.animation = 'none';
                        setTimeout(() => {
                            item.style.animation = 'fadeInUp 0.6s forwards';
                        }, 10);
                    } else {
                        item.classList.add('hidden');
                    }
                });
                
                // Vérifier s'il reste des items visibles
                const visibleItems = document.querySelectorAll('.timeline-item:not(.hidden)');
                const timelineEmpty = document.querySelector('.timeline-empty');
                
                if (visibleItems.length === 0 && !timelineEmpty) {
                    const timeline = document.querySelector('.timeline');
                    const emptyMessage = document.createElement('div');
                    emptyMessage.className = 'timeline-empty';
                    emptyMessage.innerHTML = '<p><span class="prompt">!</span> Aucune expérience de ce type</p>';
                    timeline.appendChild(emptyMessage);
                } else if (visibleItems.length > 0 && timelineEmpty) {
                    timelineEmpty.remove();
                }
            });
        });
    }
    
    // ==========================================
    // 2. ANIMATION AU SCROLL (Intersection Observer)
    // ==========================================
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observer tous les items de la timeline
    timelineItems.forEach(item => {
        // Réinitialiser l'animation pour l'observer
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = 'opacity 0.6s, transform 0.6s';
        
        observer.observe(item);
    });
    
    // ==========================================
    // 3. COMPTEUR ANIMÉ POUR LES STATS
    // ==========================================
    const statValues = document.querySelectorAll('.stat-value');
    
    function animateCounter(element) {
        const target = parseFloat(element.textContent);
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                if (target % 1 !== 0) {
                    element.textContent = current.toFixed(1);
                } else {
                    element.textContent = Math.floor(current);
                }
            }
        }, 16);
    }
    
    // Observer les stats pour déclencher l'animation
    const statsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                animateCounter(entry.target);
                entry.target.classList.add('counted');
            }
        });
    }, { threshold: 0.5 });
    
    statValues.forEach(stat => {
        statsObserver.observe(stat);
    });
    
    // ==========================================
    // 4. SMOOTH SCROLL POUR LES ANCRES
    // ==========================================
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href === '#' || href === '') return;
            
            const target = document.querySelector(href);
            
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // ==========================================
    // 5. EFFET PARALLAXE SUR LES CARDS (optionnel)
    // ==========================================
    function handleParallax() {
        const cards = document.querySelectorAll('.timeline-card');
        const scrolled = window.pageYOffset;
        
        cards.forEach((card, index) => {
            const cardTop = card.getBoundingClientRect().top + scrolled;
            const cardHeight = card.offsetHeight;
            const windowHeight = window.innerHeight;
            
            if (scrolled + windowHeight > cardTop && scrolled < cardTop + cardHeight) {
                const speed = 0.05;
                const yPos = -(scrolled - cardTop) * speed;
                card.style.transform = `translateY(${yPos}px)`;
            }
        });
    }
    
    // Désactiver sur mobile pour les performances
    if (window.innerWidth > 768) {
        window.addEventListener('scroll', handleParallax);
    }
    
    // ==========================================
    // 6. EFFET DE TYPING SUR LE TITRE (optionnel)
    // ==========================================
    function typeWriter(element, text, speed = 100) {
        let i = 0;
        element.textContent = '';
        
        function type() {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        }
        
        type();
    }
    
    // Appliquer l'effet typing au titre
    const titleElement = document.querySelector('.section-title');
    if (titleElement) {
        const originalText = titleElement.textContent;
        // Attendre un peu avant de démarrer
        setTimeout(() => {
            typeWriter(titleElement, originalText, 50);
        }, 500);
    }
    
    // ==========================================
    // 7. GESTION DES TECHNOLOGIES - Click to copy (optionnel)
    // ==========================================
    const techBadges = document.querySelectorAll('.tech-badge');
    
    techBadges.forEach(badge => {
        badge.style.cursor = 'pointer';
        badge.title = 'Cliquez pour copier';
        
        badge.addEventListener('click', function() {
            const techName = this.textContent.trim();
            
            // Copier dans le presse-papier
            navigator.clipboard.writeText(techName).then(() => {
                // Feedback visuel
                const originalBg = this.style.background;
                this.style.background = 'rgba(14, 224, 39, 0.3)';
                this.textContent = '✓ Copié !';
                
                setTimeout(() => {
                    this.style.background = originalBg;
                    this.textContent = techName;
                }, 1000);
            }).catch(err => {
                console.error('Erreur de copie:', err);
            });
        });
    });
    
    // ==========================================
    // 8. RÉORGANISATION DYNAMIQUE AU RESIZE
    // ==========================================
    let resizeTimeout;
    
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        
        resizeTimeout = setTimeout(() => {
            // Réinitialiser les animations si besoin
            timelineItems.forEach(item => {
                if (!item.classList.contains('hidden')) {
                    item.style.animation = 'none';
                    setTimeout(() => {
                        item.style.animation = 'fadeInUp 0.6s forwards';
                    }, 10);
                }
            });
        }, 250);
    });
    
    // ==========================================
    // 9. PROGRESS BAR AU SCROLL (optionnel)
    // ==========================================
    function updateProgressBar() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        
        let progressBar = document.getElementById('progress-bar');
        
        if (!progressBar) {
            progressBar = document.createElement('div');
            progressBar.id = 'progress-bar';
            progressBar.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 0%;
                height: 3px;
                background: linear-gradient(to right, #0ee027, #0cc021);
                z-index: 9999;
                transition: width 0.1s;
                box-shadow: 0 0 10px rgba(14, 224, 39, 0.5);
            `;
            document.body.appendChild(progressBar);
        }
        
        progressBar.style.width = scrolled + '%';
    }
    
    window.addEventListener('scroll', updateProgressBar);
    
    // ==========================================
    // 10. LOG DE DÉMARRAGE DANS LA CONSOLE
    // ==========================================
    console.log('%c> Timeline chargée avec succès ✓', 'color: #0ee027; font-family: monospace; font-size: 14px;');
    console.log(`%c> ${timelineItems.length} expériences affichées`, 'color: #0ee027; font-family: monospace; font-size: 12px;');
});