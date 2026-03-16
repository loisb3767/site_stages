<?php
namespace App\Controllers;

use App\Models\TaskModel;

class TaskController extends Controller {

    public function __construct($templateEngine) {
        $this->model = new TaskModel();
        $this->templateEngine = $templateEngine;
    }

    public function accueilPage() {

        echo $this->templateEngine->render('accueil.twig.html');
    }

    public function offresPage() {
        include 'offres_data.php';
        
        $parPage = 10;
        $totalOffres = $this->model->getTotalCount();
        $nbPages = ceil($totalOffres / $parPage);
        
        $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $nbPages) $currentPage = $nbPages;

        $data['offres'] = $this->model->getPaginatedOffres($currentPage, $parPage);
        $data['page'] = $currentPage;
        $data['nbPages'] = $nbPages;

        echo $this->templateEngine->render('offres.twig.html', $data);
    }

    public function detailOffrePage() {

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $data['offre'] = $this->model->getOffreById($id);
        
        if (!$data['offre']) {
            http_response_code(404);
            $this->e404Page();
            exit;
        } else {
            $template = 'detailOffre.twig.html';
        }
        echo $this->templateEngine->render($template, $data);
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