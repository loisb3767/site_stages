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
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';

        $selectedCompetences = isset($_GET['competences']) && is_array($_GET['competences'])
            ? array_map('intval', $_GET['competences'])
            : [];

        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        if ($currentPage < 1) $currentPage = 1;

        $totalOffres = $this->model->getTotalCount($selectedCompetences, $q);
        $nbPages = ($totalOffres > 0) ? (int) ceil($totalOffres / $parPage) : 1;

        if ($currentPage > $nbPages) $currentPage = $nbPages;

        $offres = $this->model->getPaginatedOffres($currentPage, $parPage, $q, $selectedCompetences);

        $user = null;
        if (isset($_SESSION['user']['id_utilisateur'])) {
            $user = $this->model->getUserById($_SESSION['user']['id_utilisateur']);
        }
        $wishlistOffreIds = $this->model->getWishlistOffreIdsByUserId($_SESSION['user']['id_utilisateur']);

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
            'user' => $user,
            'session' => $_SESSION,
            'q' => $q,
            'wishlistOffreIds' => $wishlistOffreIds,
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
            'topWishlist'   => $this->model->getTopWishlist(),
            'nbOffres'   => $this->model->getNbOffres(),
            'nbCandidaturesMoy' => $this->model->getNbCandidaturesParOffre()
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
    public function mon_espace() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $id_utilisateur = $_SESSION['user']['id_utilisateur'];
        $wishlist = $this->model->getWishlistByUserId($id_utilisateur);
        $offresDejaPostules = $this->model->offresDejaPostules($id_utilisateur);

        echo $this->templateEngine->render('mon_espace.twig.html', [
            'wishlist' => $wishlist,
            'offresDejaPostules' => $offresDejaPostules,
            'session' => $_SESSION
        ]);
    }   

    public function entreprises(): void
    {

        $user = null;
        if (isset($_SESSION['user']['id_utilisateur'])) {
            $user = $this->model->getUserById($_SESSION['user']['id_utilisateur']);
        }

        $parPage = 10;
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';

        $selectedSecteurs = isset($_GET['secteurs']) && is_array($_GET['secteurs'])
            ? array_map('intval', $_GET['secteurs'])
            : [];

        if ($q !== '') {
            $totalEntreprises = $this->model->getTotalEntreprisesSearch($q);
        } else {
            $totalEntreprises = $this->model->getTotalEntreprises($selectedSecteurs);
        }

        $nbPages = ($totalEntreprises > 0) ? (int) ceil($totalEntreprises / $parPage) : 1;
        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $nbPages) $currentPage = $nbPages;

        if ($q !== '') {
            $entreprises = $this->model->getPaginatedEntreprisesSearch($currentPage, $parPage, $q);
        } else {
            $entreprises = $this->model->getPaginatedEntreprises($currentPage, $parPage, $selectedSecteurs);
        }

        foreach ($entreprises as &$entreprise) {
            $secteurs = $this->model->getSecteursByEntrepriseId($entreprise['id_entreprise']);
            $entreprise['secteurs'] = array_map(fn($s) => $s['nom_secteur'], $secteurs);
        }

        echo $this->templateEngine->render('entreprises.twig.html', [
            'entreprises' => $entreprises,
            'categories' => $this->model->getAllSecteurs(),
            'selectedSecteurs' => $selectedSecteurs,
            'page' => $currentPage,
            'nbPages' => $nbPages,
            'active_page' => 'entreprises',
            'session' => $_SESSION,
            'user' => $user, 
            'q' => $q,
        ]);
    }

    public function supprimerWishlist() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $idOffre = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($idOffre <= 0) {
            die("ID offre invalide.");
        }

        $this->model->removeFromWishlist($_SESSION['user']['id_utilisateur'], $idOffre);
        header('Location: index.php?page=mes_offres');
        exit;
    }

    public function ajouterWishlist() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $idOffre = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($idOffre <= 0) {
            die("ID offre invalide.");
        }
        $p=isset($_GET['p']) ? (int) $_GET['p'] : 1;
        $this->model->addToWishlist($_SESSION['user']['id_utilisateur'], $idOffre);
        header('Location: index.php?page=offres&p=' . $p );
        exit;
    }

    public function supprimerOffre() {
        $id = $_POST['id_offre'] ?? null;
        
        if ($id) {
            $this->model->deleteOffre((int)$id);
        }
        
        header('Location: index.php?page=offres');
        exit;
    }

    public function modifierOffrePage() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        if ($_SESSION['user']['id_role'] == 0) {
            header('Location: index.php?page=offres');
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        $offre = $this->model->getOffreById($id);

        if (!$offre) {
            header('Location: index.php?page=offres');
            exit;
        }

        echo $this->templateEngine->render('modifierOffre.twig.html', [
            'offre' => $offre,
            'user' => $_SESSION['user'] ?? null,
            'session' => $_SESSION
        ]);

        unset($_SESSION['success'], $_SESSION['error']);
    }

    public function ajouterOffrePage() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] < 1) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $entreprises = $this->model->getAllEntreprises();
        $competences = $this->model->getAllCompetences();

        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);

        echo $this->templateEngine->render('ajouterOffre.twig.html', [
            'entreprises' => $entreprises,
            'competences' => $competences,
            'user' => $_SESSION['user'] ?? null,
            'session' => $_SESSION,
            'error' => $error,
            'success' => $success,
        ]);
    }

    public function modifierEntreprisePage() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] < 1) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        $entreprise = $this->model->getEntrepriseById($id);

        if (!$entreprise) {
            header('Location: index.php?page=entreprises');
            exit;
        }

        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);

        echo $this->templateEngine->render('modifierEntreprise.twig.html', [
            'entreprise' => $entreprise,
            'secteurs' => $this->model->getAllSecteurs(),
            'user' => $_SESSION['user'] ?? null,
            'session' => $_SESSION,
            'error' => $error,
            'success' => $success,
        ]);
    }

    public function supprimerEntreprise() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] < 1) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $id = (int)($_POST['id_entreprise'] ?? 0);

        if ($id > 0) {
            $this->model->deleteEntreprise($id);
        }

        header('Location: index.php?page=entreprises');
        exit;
    }


}



?>