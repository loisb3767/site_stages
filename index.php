<?php
require_once 'vendor/autoload.php';

// Configuration de Twig
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false, // Désactivé en dev
]);

// Récupération de la page demandée (par défaut 'accueil')
$page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

// Initialisation des données par défaut
$data = ['page_active' => $page];

switch ($page) {
    case 'offres':
        include 'offres_data.php'; // Charge ton tableau d'offres
        
        // Logique de pagination
        $parPage = 10;
        $total = count($offres);
        $nbPages = ceil($total / $parPage);
        $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        if ($currentPage < 1) $currentPage = 1;
        
        $indexDepart = ($currentPage - 1) * $parPage;
        
        // On ajoute les infos spécifiques aux offres dans $data
        $data['offres'] = array_slice($offres, $indexDepart, $parPage);
        $data['page'] = $currentPage;
        $data['nbPages'] = $nbPages;
        
        $template = 'offres.twig.html';
        break;

    case 'accueil':
        $template = 'accueil.twig.html';
        break;

    case 'contact':
        $template = 'contact.twig.html';
        break;

    case 'a-propos':
        $template = 'a_propos.twig.html';
        break;
    
    case 'connexion':
        $template = 'connexion.twig.html';
        break;

    case 'inscription':
        $template = 'inscription.twig.html';
        break;
    
    case 'profil':
        $template = 'profil.twig.html';
        break;

    case 'mention-légales':
        $template = 'mentions_legales.twig.html';
        break;

    case 'statistiques':
        $template = 'statistiques.twig.html';
        break;

    case 'contact':
        $template = 'contact.twig.html';
        break;
    
    case 'avis':
        $template = 'avis.twig.html';
        break;

    case 'postuler':
        $template = 'postuler.twig.html';
        break;

    case 'detail-offre':
        $template = 'detailOffre.twig.html';
        break;

    
    // Rajouter les autres mais la flemme
    
    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée";
        exit;
}

$data['GET'] = $_GET;
echo $twig->render($template, $data);