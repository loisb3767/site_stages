document.addEventListener('DOMContentLoaded', function() {
  var header = document.querySelector('header');
  var nav = document.querySelector('.navbar');
  if (!header || !nav) return;

  var toggle = document.createElement('button');
  toggle.className = 'menu-toggle';
  toggle.setAttribute('aria-label', 'Ouvrir le menu');
  toggle.setAttribute('aria-expanded', 'false');
  toggle.innerHTML = '<span class="bar"></span><span class="bar"></span><span class="bar"></span>';
  header.appendChild(toggle);

  var mobile = document.createElement('nav');
  mobile.className = 'mobile-menu';
  mobile.setAttribute('aria-hidden', 'true');
  var headerSearch = document.querySelector('header .search-bar');
  var searchHTML = headerSearch ? headerSearch.outerHTML : '';
  mobile.innerHTML = '<button class="mobile-nav-close" aria-label="Fermer le menu">&times;</button><div class="mobile-nav-content">' + searchHTML + nav.innerHTML + '</div>';
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

// Recherche
var searchInput = document.getElementById('searchInput');
var searchBtn = document.querySelector('.search-btn');

function doSearch() {
  var q = searchInput.value.trim();
  var params = new URLSearchParams(window.location.search);
  if (q.length === 0) {
    window.location.href = 'index.php?' + params.toString();
  };

  var params = new URLSearchParams(window.location.search);
  var page = params.get('page') || 'accueil';

  if (page === 'offres' || page === 'entreprises') {
    params.set('q', q);
    params.delete('p');
    window.location.href = 'index.php?' + params.toString();
  }
}

if (searchInput) {
  searchInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') doSearch();
  });
}
if (searchBtn) {
  searchBtn.addEventListener('click', doSearch);
}

// Header sticky sur desktop
var lastScroll = 0;
var siteHeader = document.getElementById("site-header");

window.addEventListener("scroll", function() {
  var currentScroll = window.pageYOffset;
  if (currentScroll > lastScroll) {
    siteHeader.classList.add("hide");
  } else {
    siteHeader.classList.remove("hide");
  }
  lastScroll = currentScroll;
});

// Carousel Accueil
document.querySelectorAll(".carousel-wrapper").forEach(function(wrapper) {
    var track = wrapper.querySelector(".carousel-track");
    var prevBtn = wrapper.querySelector(".prev");
    var nextBtn = wrapper.querySelector(".next");

    var index = 0;
    var total = track.children.length;

    function updateCarousel() {
        track.style.transform = 'translateX(-' + (index * 100) + '%)';
    }

    nextBtn.addEventListener("click", function() {
      if (index < total - 1) { index++; } else { index = 0; } updateCarousel();
    });

    prevBtn.addEventListener("click", function() {
      if (index > 0) { index--; } else { index = total - 1; } updateCarousel();
    });

    if (wrapper.closest(".accueil")) {
      setInterval(function() {
          if (index < total - 1) { index++; } else { index = 0; } updateCarousel();
      }, 5000);
    }
});

// Carousel Statistiques
document.querySelectorAll(".carousel-wrapper-stats").forEach(function(wrapper) {
    var track = wrapper.querySelector(".carousel-track-stats");
    var prevBtn = wrapper.querySelector(".carousel-btn-stats.prev");
    var nextBtn = wrapper.querySelector(".carousel-btn-stats.next");

    var index = 0;
    var total = track.children.length;

    function updateCarousel() {
        track.style.transform = 'translateX(-' + (index * 100) + '%)';
    }

    nextBtn.addEventListener("click", function() {
        index = index < total - 1 ? index + 1 : 0;
        updateCarousel();
    });

    prevBtn.addEventListener("click", function() {
        index = index > 0 ? index - 1 : total - 1;
        updateCarousel();
    });
});

// Postuler
document.addEventListener('DOMContentLoaded', function () {
    var cvInput = document.getElementById('cv');
    var formPostuler = document.getElementById('formPostuler');
    if (!cvInput || !formPostuler) return;

    cvInput.addEventListener('change', function () {
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

    formPostuler.addEventListener('submit', function (e) {
        var valid = true;
        var errCv = document.getElementById('err-cv');
        if (cvInput.files.length === 0) {
            errCv.textContent = 'Veuillez ajouter votre CV.';
            errCv.style.display = 'block';
            valid = false;
        }
        if (!valid) e.preventDefault();
    });
});