(function(){
  const overlay = document.getElementById('boot-screen');
  const bar = document.querySelector('.boot-bar');
  const progress = document.querySelector('.boot-progress');
  const ready = document.querySelector('.boot-footer .ready');
  const skipBtn = document.getElementById('boot-skip');
  if(!overlay || !bar) return;
  
  let autoCloseTimeout = null;
  
  // Simule une progression "crédible"
  let p = 0;
  const steps = [12, 28, 41, 57, 63, 74, 86, 93, 97, 100];
  let i = 0;
  
  function step(){
    if(i >= steps.length) return;
    p = steps[i++];
    bar.style.width = p + '%';
    if(progress) progress.setAttribute('aria-valuenow', p);
    
    if(p >= 100){
      // Quand la barre atteint 100%, afficher "system ready_"
      if(ready) ready.style.opacity = 1;
      
      // Fermer automatiquement après 2 secondes
      autoCloseTimeout = setTimeout(hideOverlay, 2000);
    } else {
      setTimeout(step, 200 + Math.random()*450);
    }
  }
  
  function hideOverlay(){
    // Annuler le timeout automatique s'il existe
    if(autoCloseTimeout){
      clearTimeout(autoCloseTimeout);
      autoCloseTimeout = null;
    }
    
    overlay.style.transition = 'opacity .45s ease';
    overlay.style.opacity = 0;
    
    setTimeout(()=> {
      overlay.style.display = 'none';
      overlay.setAttribute('aria-hidden', 'true');
    }, 450);
  }
  
  // Bouton Skip + Échap
  if(skipBtn) skipBtn.addEventListener('click', hideOverlay);
  document.addEventListener('keydown', (e)=> {
    if(e.key === 'Escape') hideOverlay();
  });
  
  // Lancement de la progression
  setTimeout(step, 20);
})();