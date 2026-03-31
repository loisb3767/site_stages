<?php

namespace App\Controllers;

use App\Models\TaskModel;

class TaskController extends Controller
{
    public function __construct($templateEngine)
    {
        $this->model = new TaskModel();
        $this->templateEngine = $templateEngine;
    }

    public function accueilPage(): void
    {
        $latestOffres = $this->model->getLatestOffres();
        
        echo $this->templateEngine->render('accueil.twig.html', [
            'latestOffres' => $latestOffres
        ]);
    }

    public function offresPage(): void
    {
        $parPage = 10;

        $selectedCompetences = isset($_GET['competences']) && is_array($_GET['competences'])
            ? array_map('intval', $_GET['competences'])
            : [];

        $totalOffres = $this->model->getTotalCount($selectedCompetences);
        $nbPages = ($totalOffres > 0) ? (int) ceil($totalOffres / $parPage) : 1;

        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;

        if ($currentPage < 1) {
            $currentPage = 1;
        }

        if ($currentPage > $nbPages) {
            $currentPage = $nbPages;
        }

        $offres = $this->model->getPaginatedOffres($currentPage, $parPage, $selectedCompetences);

        foreach ($offres as &$offre) {
            $competences = $this->model->getCompetencesByOffreId($offre['id_offre']);
            $offre['competences'] = array_map(
                fn($comp) => $comp['nom_competence'],
                $competences
            );
        }

        echo $this->templateEngine->render('offres.twig.html', [
            'offres' => $offres,
            'categories' => $this->model->getAllCompetences(),
            'selectedCompetences' => $selectedCompetences,
            'page' => $currentPage,
            'nbPages' => $nbPages,
            'active_page' => 'offres',
        ]);
    }

    public function detailOffrePage(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            die('ID offre invalide.');
        }

        $offre = $this->model->getOffreById($id);

        if (!$offre) {
            die('Offre introuvable.');
        }

        $competences = $this->model->getCompetencesByOffreId($id);

        $offre['competences'] = array_map(
            fn($comp) => $comp['nom_competence'],
            $competences
        );

        echo $this->templateEngine->render('detailOffre.twig.html', [
            'offre' => $offre,
            'active_page' => 'offres',
        ]);
    }

    public function contactPage() {

        echo $this->templateEngine->render('contact.twig.html');
    }

    public function aProposPage() {

        echo $this->templateEngine->render('a_propos.twig.html');
    }

    public function connexionPage() {
        if (isset($_SESSION['user'])) {
            header('Location: index.php?page=profil');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->model->getUserByEmail($_POST['email']);
            if ($user && $_POST['password'] === $user['mot_de_passe']) { 
                $_SESSION['user'] = $user;
                header('Location: index.php?page=profil');
                exit;
            }
            $error = "Identifiants invalides";
        }
        echo $this->templateEngine->render('connexion.twig.html', ['error' => $error ?? null]);
    }

    public function inscriptionPage() {
        $user = null;

        if (isset($_SESSION['user']['id_utilisateur'])) {
            $user = $this->model->getUserById($_SESSION['user']['id_utilisateur']);
        }

        $roles = $this->model->getAllRoles();

        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;

        unset($_SESSION['error'], $_SESSION['success']);

        echo $this->templateEngine->render('inscription.twig.html', [
            'user' => $user,
            'roles' => $roles,
            'error' => $error,
            'success' => $success,
            'session' => $_SESSION
        ]);
    }

    public function mentionsLegalesPage() {

        echo $this->templateEngine->render('mentions_legales.twig.html');
    }

    public function avisPage() {

        echo $this->templateEngine->render('avis.twig.html');
    }

    public function postulerPage()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            die("Offre invalide.");
        }

        $offre = $this->model->getOffreById($id);

        if (!$offre) {
            die("Offre introuvable.");
        }

        $error = isset($_GET['error']) ? urldecode($_GET['error']) : null;
        $status = $_GET['status'] ?? null;

        echo $this->templateEngine->render('postuler.twig.html', [
            'offre' => $offre,
            'error' => $error,
            'status' => $status,
        ]);
    }

    public function e404Page() {

        echo $this->templateEngine->render('404.twig.html');
    }

    public function profilPage() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $user = $this->model->getUserById($_SESSION['user']['id_utilisateur']);

        echo $this->templateEngine->render('profil.twig.html', [
            'user' => $user,
            'session' => $_SESSION
        ]);
    }

    public function logoutPage() {
        session_destroy();
        header('Location: index.php?page=accueil');
        exit;
    }
    
    //Carrousel
    public function statistiquesPage()
    {
        echo $this->templateEngine->render('statistiques.twig.html', [
            'offresByDuree'   => $this->model->getOffresByDuree(),
            'topWishlist'   => $this->model->getTopWishlist()
        ]);
    }    

    public function modifierProfilPage() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $user = $this->model->getUserById($_SESSION['user']['id_utilisateur']);

        echo $this->templateEngine->render('modifier_profil.twig.html', [
            'user' => $user,
            'session' => $_SESSION
        ]);
    }
    public function mes_offres() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $id_utilisateur = $_SESSION['user']['id_utilisateur'];
        $wishlist = $this->model->getWishlistByUserId($id_utilisateur);

        echo $this->templateEngine->render('mes_offres.twig.html', [
            'wishlist' => $wishlist,
            'session' => $_SESSION
        ]);
    }


    public function entreprises(): void{
        $parPage = 10;
        // Si tu veux filtrer par secteur par exemple
        $selectedSecteurs = isset($_GET['secteurs']) && is_array($_GET['secteurs'])
            ? array_map('intval', $_GET['secteurs'])
            : [];

        // Total entreprises
        $totalEntreprises = $this->model->getTotalEntreprises($selectedSecteurs);
        $nbPages = ($totalEntreprises > 0) ? (int) ceil($totalEntreprises / $parPage) : 1;

        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;

        if ($currentPage < 1) {
            $currentPage = 1;
        }

        if ($currentPage > $nbPages) {
            $currentPage = $nbPages;
        }

        // Récupération des entreprises paginées
        $entreprises = $this->model->getPaginatedEntreprises($currentPage, $parPage, $selectedSecteurs);

        // Exemple : ajouter des infos liées (facultatif)
        foreach ($entreprises as &$entreprise) {
            $secteurs = $this->model->getSecteursByEntrepriseId($entreprise['id_entreprise']);
            $entreprise['secteurs'] = array_map(
                fn($s) => $s['nom_secteur'],
                $secteurs
            );
        }

        echo $this->templateEngine->render('entreprises.twig.html', [
            'entreprises' => $entreprises,
            'categories' => $this->model->getAllSecteurs(),
            'selectedSecteurs' => $selectedSecteurs,
            'page' => $currentPage,
            'nbPages' => $nbPages,
            'active_page' => 'entreprises',
        ]);
    }

}



?>