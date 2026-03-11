<?php
require_once 'vendor/autoload.php';

use App\Controllers\TaskController;

// Configuration de Twig
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false, // Désactivé en dev
]);

// Récupération de la page demandée (par défaut 'accueil')
$page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

// Initialisation des données par défaut
$data = ['page_active' => $page];

$controller = new TaskController($twig);

switch ($page) {
    case 'offres':
        $controller -> offresPage();
        break;

    case 'accueil':
        $controller -> accueilPage();
        break;

    case 'contact':
        $controller -> contactPage();
        break;

    case 'a-propos':
        $controller -> aProposPage();
        break;
    
    case 'connexion':
        $controller -> connexionPage();
        break;

    case 'inscription':
        $controller -> inscriptionPage();
        break;
    
    case 'profil':
        $controller -> profilPage();
        break;

    case 'mention-légales':
        $controller -> mentionsLegalesPage();
        break;

    case 'statistiques':
        $controller -> statistiquesPage();
        break;

    case 'contact':
        $controller -> contactPage();
        break;
    
    case 'avis':
        $controller -> avisPage();
        break;

    case 'postuler':
        $controller -> postulerPage();
        break;

    case 'detail-offre':
        $controller -> detailOffrePage();
        break;

    
    // Rajouter les autres mais la flemme
    
    default:
        http_response_code(404);
        break;
}

?>