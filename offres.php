<?php
require_once 'vendor/autoload.php';
require_once 'db.php'; 

$parPage = 10;
$sqlTotal = "SELECT COUNT(*) FROM offre";
$stmtTotal = $dbh->query($sqlTotal);
$total = $stmtTotal->fetchColumn();
$nbPages = ($total > 0) ? ceil($total / $parPage) : 1;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;
if ($page > $nbPages && $nbPages > 0) $page = $nbPages;

$indexDepart = ($page - 1) * $parPage;

$sql="
    SELECT
        o.id_offre,
        o.titre,
        o.description,
        o.gratification,
        o.date_offre,
        o.duree,
        e.nom_entreprise
    FROM offre o
    INNER JOIN entreprise e ON o.id_entreprise = e.id_entreprise
    ORDER BY o.date_offre DESC, o.id_offre DESC
    LIMIT :limit OFFSET :offset";

$stmt = $dbh->prepare($sql);
$stmt->bindValue(':limit', $parPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $indexDepart, PDO::PARAM_INT);
$stmt->execute();

$offresAffichées = $stmt->fetchAll(PDO::FETCH_ASSOC);

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('offres.twig.html', [
    'offres' => $offresAffichées,
    'p' => $page,
    'nbPages' => $nbPages,
    'active_page' => 'offres'
]);
?>