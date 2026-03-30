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

  // Menu navbar sur Mobile — inclure la search-bar de l'en-tête si présente
  var mobile = document.createElement('nav');
  mobile.className = 'mobile-menu';
  mobile.setAttribute('aria-hidden', 'true');
  var headerSearch = document.querySelector('header .search-bar');
  var searchHTML = headerSearch ? headerSearch.outerHTML : '';
  mobile.innerHTML = '<button class="mobile-nav-close" aria-label="Fermer le menu">&times;</button><div class="mobile-nav-content">' + searchHTML + nav.innerHTML + '</div>';document.body.appendChild(mobile);

  

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


// Header sticky sur desktop

let lastScroll = 0;
const header = document.getElementById("site-header");

window.addEventListener("scroll", () => {
  let currentScroll = window.pageYOffset;

  if (currentScroll > lastScroll) {
    header.classList.add("hide"); // scroll vers le bas = cacher
  } else {
    header.classList.remove("hide"); // scroll vers le haut = afficher
  }

  lastScroll = currentScroll;
});

// Carousel Accueil
document.querySelectorAll(".carousel-wrapper").forEach(wrapper => {
    const track = wrapper.querySelector(".carousel-track");
    const prevBtn = wrapper.querySelector(".prev");
    const nextBtn = wrapper.querySelector(".next");

    let index = 0;
    const total = track.children.length;

    function updateCarousel() {
        track.style.transform = `translateX(-${index * 100}%)`;
    }

    nextBtn.addEventListener("click", () => { 
      if (index < total - 1) { index++; } else { index = 0; } updateCarousel(); });

    prevBtn.addEventListener("click", () => { 
      if (index > 0) { index--; } else { index = total - 1; } updateCarousel(); });

    //  Auto-slide
    if (wrapper.closest(".accueil")) {
      setInterval(() => {
          if (index < total - 1) { index++; } else { index = 0; } updateCarousel(); }, 5000);
    }
});

//Carousel Statistiques
document.querySelectorAll(".carousel-wrapper-stats").forEach(wrapper => {
    const track = wrapper.querySelector(".carousel-track-stats");
    const prevBtn = wrapper.querySelector(".carousel-btn-stats.prev");
    const nextBtn = wrapper.querySelector(".carousel-btn-stats.next");

    let index = 0;
    const total = track.children.length;

    function updateCarousel() {
        track.style.transform = `translateX(-${index * 100}%)`;
    }

    nextBtn.addEventListener("click", () => {
        index = index < total - 1 ? index + 1 : 0;
        updateCarousel();
    });

    prevBtn.addEventListener("click", () => {
        index = index > 0 ? index - 1 : total - 1;
        updateCarousel();
    });
});

//Postuler Offre Vérifications
document.addEventListener('DOMContentLoaded', function () {
    // Vérification du CV à la sélection
    document.getElementById('cv').addEventListener('change', function () {
        var formats = ['.pdf', '.doc', '.docx', '.odt', '.rtf', '.jpg', '.jpeg', '.png'];
        var errCv = document.getElementById('err-cv');
        var file = this.files[0];

        if (!file) { errCv.style.display = 'none'; return; }

        var ext = file.name.toLowerCase().substring(file.name.lastIndexOf('.'));

        if (formats.indexOf(ext) === -1) {
            errCv.textContent = 'Format non autorisé : ' + ext;
            errCv.style.display = 'block';
            this.value = '';
        } else if (file.size > 2 * 1024 * 1024) {
            errCv.textContent = 'Le fichier dépasse 2 Mo.';
            errCv.style.display = 'block';
            this.value = '';
        } else {
            errCv.style.display = 'none';
        }
    });

    // Validation à l'envoi
    document.getElementById('formPostuler').addEventListener('submit', function (e) {
        var valid = true;
        
        var cvInput = document.getElementById('cv');
        var errCv = document.getElementById('err-cv');
        if (cvInput.files.length === 0) {
            errCv.textContent = 'Veuillez ajouter votre CV.';
            errCv.style.display = 'block';
            valid = false;
        }

        if (!valid) e.preventDefault();
    });

});