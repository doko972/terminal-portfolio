function toggleMenu() {
  const nav = document.getElementById("mainNav");
  const overlay = document.getElementById("menuOverlay");
  const menuToggle = document.getElementById("menuToggle");

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

// Fermer le menu au clic sur un lien
document.querySelectorAll("nav a").forEach((link) => {
  link.addEventListener("click", () => {
    const nav = document.getElementById("mainNav");
    const overlay = document.getElementById("menuOverlay");
    const menuToggle = document.getElementById("menuToggle");

    nav.classList.remove("active");
    overlay.classList.remove("active");
    menuToggle.classList.remove("active");
    document.body.style.overflow = "";
  });
});

// Fermer le menu avec la touche Escape
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    const nav = document.getElementById("mainNav");
    if (nav.classList.contains("active")) {
      toggleMenu();
    }
  }
});

// Animation Matrix Background
const canvas = document.getElementById("matrix-canvas");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*(){}[]<>/";
const charArray = chars.split("");
const fontSize = 14;
const columns = canvas.width / fontSize;
const drops = Array(Math.floor(columns)).fill(1);

function drawMatrix() {
  ctx.fillStyle = "rgba(0, 0, 0, 0.05)";
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  ctx.fillStyle = "#0ee027";
  ctx.font = fontSize + "px monospace";

  for (let i = 0; i < drops.length; i++) {
    const char = charArray[Math.floor(Math.random() * charArray.length)];
    ctx.fillText(char, i * fontSize, drops[i] * fontSize);

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

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({ behavior: "smooth", block: "start" });
    }
  });
});
