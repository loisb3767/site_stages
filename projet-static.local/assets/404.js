document.addEventListener('DOMContentLoaded', () => {
    /* ── 1. ANIMATION INITIALE 404 ── */
    const container = document.getElementById('e404-num');
    if (!container) return;

    // Création des spans pour le compteur
    '404'.split('').forEach((char, i) => {
        const span = document.createElement('span');
        span.textContent = '0';
        span.style.opacity = '0';
        span.style.display = 'inline-block';
        span.style.animation = `slideUp 0.5s ease ${i * 0.15}s both`;
        container.appendChild(span);
    });

    const spans = container.querySelectorAll('span');
    let count = 0;
    const target = 404;

    const counter = setInterval(() => {
        count += Math.ceil((target - count) / 8);
        if (count >= target) {
            count = target;
            clearInterval(counter);
        }
        const str = String(count).padStart(3, '0');
        spans.forEach((s, i) => s.textContent = str[i] ?? '');
    }, 40);

    /* ── 2. KONAMI CODE & EFFET TREMBLEMENT ── */
    const konami = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
    let konamiIndex = 0;
    let isGaming = false;

    document.addEventListener('keydown', (e) => {
        if (isGaming) return handleGameInput(e);

        const key = (e.key === 'B' || e.key === 'A') ? e.key.toLowerCase() : e.key;

        if (key === konami[konamiIndex]) {
            konamiIndex++;

            // Effet de tremblement sur le body
            document.body.classList.remove('shake-hit');
            void document.body.offsetWidth; // Reset l'animation
            document.body.classList.add('shake-hit');

            if (konamiIndex === konami.length) {
                document.body.classList.remove('shake-hit');
                startSnake();
                konamiIndex = 0;
            }
        } else {
            konamiIndex = 0;
        }
    });

    /* ── 3. MOTEUR DU JEU SNAKE ── */
    const canvas = document.getElementById('snakeCanvas');
    let ctx, gameLoop, snake, food, direction;
    const box = 20; // Taille de la grille

    function startSnake() {
        isGaming = true;
        ctx = canvas.getContext('2d');
        
        // Cache le contenu textuel et affiche le jeu
        const normalContent = document.getElementById('normal-content');
        if(normalContent) normalContent.style.display = 'none';
        canvas.style.display = 'block';

        // Initialisation serpent (au milieu)
        snake = [{ x: 10 * box, y: 10 * box }];
        direction = "RIGHT";
        
        spawnFood();
        if (gameLoop) clearInterval(gameLoop);
        gameLoop = setInterval(drawGame, 100); // Vitesse
    }

    function spawnFood() {
        food = {
            x: Math.floor(Math.random() * (canvas.width / box)) * box,
            y: Math.floor(Math.random() * (canvas.height / box)) * box
        };
    }

    function handleGameInput(e) {
        // Empêche le scroll avec les flèches
        if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) e.preventDefault();

        if (e.key === "ArrowLeft" && direction !== "RIGHT") direction = "LEFT";
        else if (e.key === "ArrowUp" && direction !== "DOWN") direction = "UP";
        else if (e.key === "ArrowRight" && direction !== "LEFT") direction = "RIGHT";
        else if (e.key === "ArrowDown" && direction !== "UP") direction = "DOWN";
    }

    function drawGame() {
        // Fond du canvas
        ctx.fillStyle = "#081c30"; // Couleur --text-color
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Dessin du serpent
        for (let i = 0; i < snake.length; i++) {
            ctx.fillStyle = (i === 0) ? "#00a5cf" : "#afe0ff"; // Tête primary, corps light-blue
            ctx.fillRect(snake[i].x, snake[i].y, box, box);
            ctx.strokeStyle = "#081c30";
            ctx.strokeRect(snake[i].x, snake[i].y, box, box);
        }

        // Dessin nourriture
        ctx.fillStyle = "#0f0"; // --good-color
        ctx.fillRect(food.x, food.y, box, box);

        // Position actuelle de la tête
        let snakeX = snake[0].x;
        let snakeY = snake[0].y;

        // Calcul du mouvement
        if (direction === "LEFT") snakeX -= box;
        if (direction === "UP") snakeY -= box;
        if (direction === "RIGHT") snakeX += box;
        if (direction === "DOWN") snakeY += box;

        // Vérification Collision Murs ou Soi-même
        if (snakeX < 0 || snakeX >= canvas.width || snakeY < 0 || snakeY >= canvas.height || collision(snakeX, snakeY, snake)) {
            clearInterval(gameLoop);
            alert("GAME OVER ! Score : " + (snake.length - 1));
            location.reload(); // Redémarre proprement la page
            return;
        }

        // Vérification si mange la nourriture
        if (snakeX === food.x && snakeY === food.y) {
            spawnFood();
        } else {
            snake.pop(); // Enlève la queue
        }

        // Ajoute la nouvelle tête
        const newHead = { x: snakeX, y: snakeY };
        snake.unshift(newHead);
    }

    function collision(x, y, array) {
        for (let i = 0; i < array.length; i++) {
            if (x === array[i].x && y === array[i].y) return true;
        }
        return false;
    }
});