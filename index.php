<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'vendor/autoload.php';

use App\Controllers\TaskController;
require_once 'config.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'debug' => $debug_env ?? false,
]);
$twig->addGlobal('session', $_SESSION);

$page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

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
    
    case 'avis':
        $controller -> avisPage();
        break;

    case 'postuler':
        $controller -> postulerPage();
        break;

    case 'detail-offre':
        $controller -> detailOffrePage();
        break;

    case 'logout':
        $controller -> logoutPage();
        break;

    case 'modifier_profil':
        $controller -> modifierProfilPage();
        break;
    
    case 'mon_espace':
        $controller->mon_espace();
        break;

    case 'entreprises':
        $controller -> entreprises();
        break;

    case 'supprimer-wishlist':
        $controller -> supprimerWishlist();
        break;

    case 'supprimer_offre':
        $controller -> supprimerOffre();
        break;

    case 'ajouter_wishlist':
        $controller -> ajouterWishlist();
        break;

    case 'modifier_offre':
        $controller -> modifierOffrePage();
        break;

    case 'ajouter_offre':
        $controller -> ajouterOffrePage();
        break;

    case 'modifier_entreprise':
        $controller -> modifierEntreprisePage();
        break;

    case 'supprimer_entreprise':
        $controller -> supprimerEntreprise();
        break;

    case 'liste_etudiant':
        $controller -> liste_etudiantPage();
        break;
    case 'liste_admin':
        $controller -> liste_adminPage();
        break;
    case 'detail_entreprise':
        $controller -> detailEntreprisePage();
        break;

    case 'creer_entreprise':
        $controller -> creerEntreprisePage();
        break;

    case 'modifier_etudiant':
        $controller -> modifierEtudiantPage();
        break;

    case 'candidatures_etudiant':
        $controller -> candidaturesEtudiantPage();
        break;

    case 'supprimer_etudiant':
        $controller -> supprimerEtudiant();
        break;

    default:
        $controller -> e404Page();
        break;

    
}

?>