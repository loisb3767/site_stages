document.addEventListener('DOMContentLoaded', function () {
  const mapEl = document.getElementById('offre-map');
  if (!mapEl) return;

  const lat = parseFloat(mapEl.dataset.lat);
  const lng = parseFloat(mapEl.dataset.lng);
  const title = mapEl.dataset.title || 'Offre';

  if (isNaN(lat) || isNaN(lng)) return;

  const map = L.map('offre-map').setView([lat, lng], 14);

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  L.marker([lat, lng]).addTo(map).bindPopup(title).openPopup();
});