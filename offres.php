<?php 
include 'offres_data.php'; 

$parPage = 10;
$total = count($offres);
$nbPages = ceil($total / $parPage);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) $page = 1;
if ($page > $nbPages) $page = $nbPages;

$indexDepart = ($page - 1) * $parPage;

$offresAffichées = array_slice($offres, $indexDepart, $parPage);

?>

<!doctype html> 
<html lang="fr"> 
   <head> 
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	  <meta name="author" content="Groupe 4">
	  <meta name="description" content="Parcourez nos offres de stages et trouvez celle qui vous correspond !">
      <title>Parcours des offres - Job2main</title> 
	  <link rel="stylesheet" href="assets/style.css">
    </head> 
    <body>
        <header>
		    <div class="logo"><img src="./images/JOB_2_MAIN_mini.png" alt="Lebonplan - Le meilleur site d'annonces en ligne"></div>
		    <nav class="navbar">
				<a href="accueil.html">Accueil</a>&nbsp;
                <a href="a-propos.html">A propos</a>&nbsp;
                <a href="statistiques.html">Statistiques</a>&nbsp;
                <a href="avis.html">Avis</a>&nbsp;
                <a href="contact.html">Contact</a>
		</nav>
        <div class="logreg">
			<a class="login" href="connexion.html">
				<img src="./images/user_icon.png" alt="">
				Se connecter
			</a>
		</div>
        </header>

        <main class="offers-page">
            <aside class="sidebar">
                <div class="sidebar-title">catégories</div>
                <ul class="specialites">
                    <li><input type="checkbox" id="spec1"> <label for="spec1">spécialités</label></li>
                    <li><input type="checkbox" id="spec2"> <label for="spec2">spécialités</label></li>
                    <li><input type="checkbox" id="spec3"> <label for="spec3">spécialités</label></li>
                    <li><input type="checkbox" id="spec4"> <label for="spec4">spécialités</label></li>
                    <li><input type="checkbox" id="spec5"> <label for="spec5">spécialités</label></li>
                    <li><input type="checkbox" id="spec6"> <label for="spec6">spécialités</label></li>
                    <li><input type="checkbox" id="spec7"> <label for="spec7">spécialités</label></li>
                    <li><input type="checkbox" id="spec8"> <label for="spec8">spécialités</label></li>
                    <li><input type="checkbox" id="spec9"> <label for="spec9">spécialités</label></li>
                    <li><input type="checkbox" id="spec10"> <label for="spec10">spécialités</label></li>
                </ul>
            </aside>

            <section class="offers-content">
                <?php foreach ($offresAffichées as $offre): ?>
                    <article class="offer-card">
                        <div class="offer-info">
                            <h3><?= htmlspecialchars($offre['titre']) ?></h3>
                            <p class="resume"><?= htmlspecialchars($offre['resume']) ?></p>
                            <?php foreach ($offre['competences'] as $comp): ?>
                                <div class="competence-bar"><?= htmlspecialchars($comp) ?></div>
                            <?php endforeach; ?>
                        </div>
                        <a href="detailOffre.php?id=<?= $offre['id'] ?>" class="info-btn">plus d'info</a>
                    </article>
                <?php endforeach; ?>

                <div class="pagination" style="margin-top: 20px; text-align: center;">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>" style="padding: 10px; background: #004f66; color: white; text-decoration: none;">Précédent</a>
                    <?php endif; ?>

                    <span style="margin: 0 15px;"> Page <?= $page ?> sur <?= $nbPages ?> </span>

                    <?php if ($page < $nbPages): ?>
                        <a href="?page=<?= $page + 1 ?>" style="padding: 10px; background: #004f66; color: white; text-decoration: none;">Suivant</a>
                    <?php endif; ?>
                </div>
            </section>
        </main>
     	<footer>
		<p>&copy;2026 - Tous droits réservés -	JGT</p>
		<a href="mentionslégales.html">Mentions légales</a>&nbsp;
 	</footer>
      <script src="assets/menu.js"></script>
 	 
    </body> 
</html>

