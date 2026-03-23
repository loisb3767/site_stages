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
        echo $this->templateEngine->render('accueil.twig.html');
    }

    public function offresPage(): void
    {
        $parPage = 10;
        $totalOffres = $this->model->getTotalCount();
        $nbPages = ($totalOffres > 0) ? (int) ceil($totalOffres / $parPage) : 1;

        $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;

        if ($currentPage < 1) {
            $currentPage = 1;
        }

        if ($currentPage > $nbPages) {
            $currentPage = $nbPages;
        }

        $data = [
            'offres' => $this->model->getPaginatedOffres($currentPage, $parPage),
            'page' => $currentPage,
            'nbPages' => $nbPages,
            'active_page' => 'offres',
        ];

        echo $this->templateEngine->render('offres.twig.html', $data);
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

        echo $this->templateEngine->render('detail-offre.twig.html', [
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

        echo $this->templateEngine->render('connexion.twig.html');
    }

    public function inscriptionPage() {

        echo $this->templateEngine->render('inscription.twig.html');
    }

    public function profilPage() {

        echo $this->templateEngine->render('profil.twig.html');
    }

    public function mentionsLegalesPage() {

        echo $this->templateEngine->render('mentions_legales.twig.html');
    }

    public function statistiquesPage() {

        echo $this->templateEngine->render('statistiques.twig.html');
    }

    public function avisPage() {

        echo $this->templateEngine->render('avis.twig.html');
    }

    public function postulerPage() {

        echo $this->templateEngine->render('postuler.twig.html');
    }

    public function e404Page() {

        echo $this->templateEngine->render('404.twig.html');
    }

}



?>