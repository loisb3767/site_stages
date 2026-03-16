<?php
require_once 'vendor/autoload.php';
include 'offres_data.php'; 

$parPage = 10;
$total = count($offres);
$nbPages = ceil($total / $parPage);
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;
if ($page > $nbPages && $nbPages > 0) $page = $nbPages;

$indexDepart = ($page - 1) * $parPage;
$offresAffichées = array_slice($offres, $indexDepart, $parPage);

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('offres.twig.html', [
    'offres' => $offresAffichées,
    'p' => $page,
    'nbPages' => $nbPages,
    'active_page' => 'offres'
]);
?>