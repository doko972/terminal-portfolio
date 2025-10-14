import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

import './portfolio';
import './experiences_form'
import './timeline';

// ============================================
// 1. MENU BURGER - TOGGLE & GESTION
// ============================================
function toggleMenu() {
  const nav = document.getElementById("mainNav");
  const overlay = document.getElementById("menuOverlay");
  const menuToggle = document.getElementById("menuToggle");

  if (nav && overlay && menuToggle) {
    nav.classList.toggle("active");
    overlay.classList.toggle("active");
    menuToggle.classList.toggle("active");

    // Empêcher le scroll quand le menu est ouvert
    if (nav.classList.contains("active")) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
  }
}

// Rendre la fonction globale
window.toggleMenu = toggleMenu;

// ============================================
// 2. FERMER LE MENU AU CLIC SUR UN LIEN
// ============================================
document.addEventListener('DOMContentLoaded', () => {

  document.querySelectorAll("nav a").forEach((link) => {
    link.addEventListener("click", () => {
      const nav = document.getElementById("mainNav");
      const overlay = document.getElementById("menuOverlay");
      const menuToggle = document.getElementById("menuToggle");

      if (nav && overlay && menuToggle) {
        nav.classList.remove("active");
        overlay.classList.remove("active");
        menuToggle.classList.remove("active");
        document.body.style.overflow = "";
      }
    });
  });

  // ============================================
  // 3. FERMER LE MENU AVEC LA TOUCHE ESCAPE
  // ============================================
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      const nav = document.getElementById("mainNav");
      if (nav && nav.classList.contains("active")) {
        toggleMenu();
      }
    }
  });

  // Fermer le menu au clic sur l'overlay
  const overlay = document.getElementById("menuOverlay");
  if (overlay) {
    overlay.addEventListener("click", toggleMenu);
  }

});

// ============================================
// 4. ANIMATION MATRIX BACKGROUND
// ============================================
window.addEventListener('load', () => {
  const canvas = document.getElementById("matrix-canvas");
  
  // Désactiver sur mobile pour les performances
  const isMobile = window.innerWidth <= 768;
  
  if (canvas && !isMobile) {
    const ctx = canvas.getContext("2d");
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    
    // Mix de symboles et mots courts pour rester lisible
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*(){}[]<>/";
    const techSymbols = ["PHP", "JS", "SQL", "CSS", "API", "GIT", "VUE", "DEV", "WEB", ">", "$", "{", "}"];
    
    // Mélanger caractères et symboles tech
    const allChars = chars.split("").concat(techSymbols);
    
    const fontSize = 18;
    const columns = canvas.width / fontSize;
    const drops = Array(Math.floor(columns)).fill(1);
    
    function drawMatrix() {
      ctx.fillStyle = "rgba(0, 0, 0, 0.05)";
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      ctx.fillStyle = "#0ee027";
      ctx.font = fontSize + "px monospace";
      
      for (let i = 0; i < drops.length; i++) {
        // Choisir aléatoirement un caractère ou un mot court
        const char = allChars[Math.floor(Math.random() * allChars.length)];
        ctx.fillText(char, i * fontSize, drops[i] * fontSize);
        
        // Réinitialiser la goutte quand elle atteint le bas
        if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
          drops[i] = 0;
        }
        
        drops[i]++;
      }
    }
    
    setInterval(drawMatrix, 50);
    
    // Resize canvas
    window.addEventListener("resize", () => {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    });
  }
});

// ============================================
// 5. SMOOTH SCROLL POUR LES ANCRES
// ============================================
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      const href = this.getAttribute("href");

      // Ignorer les liens vides ou juste "#"
      if (href === "#" || href === "") {
        e.preventDefault();
        return;
      }

      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    });
  });
});

// ============================================
// 6. ÉCRAN DE DÉMARRAGE (BOOT SCREEN)
// ============================================
(function () {
  const overlay = document.getElementById('boot-screen');
  const bar = document.querySelector('.boot-bar');
  const progress = document.querySelector('.boot-progress');
  const ready = document.querySelector('.boot-footer .ready');
  const skipBtn = document.getElementById('boot-skip');

  if (!overlay || !bar) return;

  let autoCloseTimeout = null;

  // Simule une progression "crédible"
  let p = 0;
  const steps = [12, 28, 41, 57, 63, 74, 86, 93, 97, 100];
  let i = 0;

  function step() {
    if (i >= steps.length) return;

    p = steps[i++];
    bar.style.width = p + '%';
    if (progress) progress.setAttribute('aria-valuenow', p);

    if (p >= 100) {
      // Quand la barre atteint 100%, afficher "system ready_"
      if (ready) ready.style.opacity = 1;

      // Fermer automatiquement après 2 secondes
      autoCloseTimeout = setTimeout(hideOverlay, 2000);
    } else {
      setTimeout(step, 200 + Math.random() * 450);
    }
  }

  function hideOverlay() {
    // Annuler le timeout automatique s'il existe
    if (autoCloseTimeout) {
      clearTimeout(autoCloseTimeout);
      autoCloseTimeout = null;
    }

    overlay.style.transition = 'opacity .45s ease';
    overlay.style.opacity = 0;

    setTimeout(() => {
      overlay.style.display = 'none';
      overlay.setAttribute('aria-hidden', 'true');
    }, 450);
  }

  // Bouton Skip + Échap
  if (skipBtn) skipBtn.addEventListener('click', hideOverlay);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') hideOverlay();
  });

  // Lancement de la progression
  setTimeout(step, 20);
})();

// Drag to scroll pour les tableaux
document.addEventListener('DOMContentLoaded', function () {
  const sliders = document.querySelectorAll('.terminal-table-wrapper');

  sliders.forEach(slider => {
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
      isDown = true;
      slider.style.cursor = 'grabbing';
      startX = e.pageX - slider.offsetLeft;
      scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener('mouseleave', () => {
      isDown = false;
      slider.style.cursor = 'grab';
    });

    slider.addEventListener('mouseup', () => {
      isDown = false;
      slider.style.cursor = 'grab';
    });

    slider.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - slider.offsetLeft;
      const walk = (x - startX) * 2; // Vitesse du scroll
      slider.scrollLeft = scrollLeft - walk;
    });

    // Support tactile pour mobile
    slider.addEventListener('touchstart', (e) => {
      startX = e.touches[0].pageX - slider.offsetLeft;
      scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener('touchmove', (e) => {
      const x = e.touches[0].pageX - slider.offsetLeft;
      const walk = (x - startX) * 2;
      slider.scrollLeft = scrollLeft - walk;
    });
  });
});
// Fonction toggle menu burger
window.toggleMenu = function () {
  const nav = document.getElementById('mainNav');
  const toggle = document.getElementById('menuToggle');

  if (nav && toggle) {
    nav.classList.toggle('active');
    toggle.classList.toggle('active');
  }
};

// Fermer le menu en cliquant sur un lien
document.addEventListener('DOMContentLoaded', function () {
  const navLinks = document.querySelectorAll('#mainNav a');

  navLinks.forEach(link => {
    link.addEventListener('click', function () {
      const nav = document.getElementById('mainNav');
      const toggle = document.getElementById('menuToggle');

      if (nav && toggle && nav.classList.contains('active')) {
        nav.classList.remove('active');
        toggle.classList.remove('active');
      }
    });
  });
});
// ==========================================
// PRÉVISUALISATION MULTI-IMAGES
// ==========================================

let selectedFiles = [];

window.previewImages = function (event) {
  const files = Array.from(event.target.files);
  const previewContainer = document.getElementById('imagesPreview');

  // Ajouter les nouveaux fichiers
  selectedFiles = [...selectedFiles, ...files];

  // Limiter à 10 images
  if (selectedFiles.length > 10) {
    alert('Vous ne pouvez uploader que 10 images maximum.');
    selectedFiles = selectedFiles.slice(0, 10);
  }

  // Vider le conteneur
  previewContainer.innerHTML = '';

  // Créer les prévisualisations
  selectedFiles.forEach((file, index) => {
    const reader = new FileReader();

    reader.onload = function (e) {
      const div = document.createElement('div');
      div.className = 'preview-image-item';
      div.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
                <button type="button" class="preview-remove" onclick="removePreviewImage(${index})" title="Supprimer">
                    ✕
                </button>
                ${index === 0 ? '<span class="main-badge">★ Principale</span>' : ''}
            `;
      previewContainer.appendChild(div);
    };

    reader.readAsDataURL(file);
  });

  // Mettre à jour l'input file
  updateFileInput(event.target);
};

window.removePreviewImage = function (index) {
  selectedFiles.splice(index, 1);

  const fileInput = document.getElementById('images');
  updateFileInput(fileInput);

  // Re-déclencher la prévisualisation
  window.previewImages({ target: fileInput });
};

function updateFileInput(input) {
  const dataTransfer = new DataTransfer();
  selectedFiles.forEach(file => dataTransfer.items.add(file));
  input.files = dataTransfer.files;
}

// ============================================
// 7. CODE SECRET - CTRL + SHIFT + L
// ============================================
document.addEventListener('keydown', (e) => {
  // CTRL + SHIFT + L
  if (e.ctrlKey && e.shiftKey && e.key === 'L') {
    e.preventDefault();
    const hiddenLink = document.getElementById('hiddenLoginLink');
    if (hiddenLink) {
      hiddenLink.style.display = 'block';
      hiddenLink.style.opacity = '0';
      setTimeout(() => {
        hiddenLink.style.transition = 'opacity 0.5s ease';
        hiddenLink.style.opacity = '1';
      }, 10);
      console.log('%c> Admin access activated', 'color: #0ee027; font-weight: bold; font-family: monospace;');
    }
  }
});