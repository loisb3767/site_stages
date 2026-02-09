document.addEventListener('DOMContentLoaded', function() {
  var header = document.querySelector('header');
  var nav = document.querySelector('.navbar');
  if (!header || !nav) return;

  // Bouton sur mobile
  var toggle = document.createElement('button');
  toggle.className = 'menu-toggle';
  toggle.setAttribute('aria-label', 'Ouvrir le menu');
  toggle.setAttribute('aria-expanded', 'false');
  toggle.innerHTML = '<span class="bar"></span><span class="bar"></span><span class="bar"></span>';
  header.appendChild(toggle);

  // Menu navbar sur Mobile
  var mobile = document.createElement('nav');
  mobile.className = 'mobile-menu';
  mobile.setAttribute('aria-hidden', 'true');
  mobile.innerHTML = '<button class="mobile-nav-close" aria-label="Fermer le menu">&times;</button><div class="mobile-nav-content">' + nav.innerHTML + '</div>';
  document.body.appendChild(mobile);

  var overlay = document.createElement('div');
  overlay.className = 'mobile-overlay';
  document.body.appendChild(overlay);

  function openMenu() {
    mobile.classList.add('open');
    overlay.classList.add('open');
    toggle.classList.add('open');
    document.body.classList.add('menu-open');
    mobile.setAttribute('aria-hidden', 'false');
    toggle.setAttribute('aria-expanded', 'true');
  }

  function closeMenu() {
    mobile.classList.remove('open');
    overlay.classList.remove('open');
    toggle.classList.remove('open');
    document.body.classList.remove('menu-open');
    mobile.setAttribute('aria-hidden', 'true');
    toggle.setAttribute('aria-expanded', 'false');
  }

  toggle.addEventListener('click', function() {
    if (mobile.classList.contains('open')) closeMenu(); else openMenu();
  });

  overlay.addEventListener('click', closeMenu);
  var closeBtn = mobile.querySelector('.mobile-nav-close');
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);

  document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeMenu(); });
});
