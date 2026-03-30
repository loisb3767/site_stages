document.addEventListener('DOMContentLoaded', () => {

  /* ── 1. Affichage lettre par lettre du "404" ── */
  const container = document.getElementById('e404-num');
  if (!container) return;

  '404'.split('').forEach((char, i) => {
    const span = document.createElement('span');
    span.textContent = char;
    span.style.opacity = '0';
    span.style.animation = `slideUp 0.5s ease ${i * 0.15}s both`;
    container.appendChild(span);
  });

  /* ── 2. Compteur animé 000 → 404 ── */
  const spans = container.querySelectorAll('span');
  let count = 0;
  const target = 404;

  const counter = setInterval(() => {
    count += Math.ceil((target - count) / 8);
    if (count >= target) { count = target; clearInterval(counter); }
    const str = String(count).padStart(3, '0');
    spans.forEach((s, i) => s.textContent = str[i] ?? '');
  }, 40);

  /* ── 3. Particules au clic ── */
  const colors = ['#00a5cf', '#004e64', '#afe0ff'];

  document.addEventListener('click', (e) => {
    for (let i = 0; i < 10; i++) {
      const p = document.createElement('div');
      p.className = 'particle';
      const size = Math.random() * 10 + 5;
      p.style.cssText = `
        width: ${size}px;
        height: ${size}px;
        left: ${e.clientX + (Math.random() - 0.5) * 40}px;
        top: ${e.clientY + (Math.random() - 0.5) * 40}px;
        background: ${colors[Math.floor(Math.random() * colors.length)]};
        animation-duration: ${0.6 + Math.random() * 0.6}s;
        animation-delay: ${Math.random() * 0.2}s;
      `;
      document.body.appendChild(p);
      p.addEventListener('animationend', () => p.remove());
    }
  });

});