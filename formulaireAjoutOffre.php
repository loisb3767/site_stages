<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\TaskModel;

session_start();

$model = new TaskModel();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=ajouter_offre');
    exit;
}

if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] < 1) {
    $_SESSION['error'] = "Accès non autorisé.";
    header('Location: index.php?page=connexion');
    exit;
}

$titre = trim($_POST['titre'] ?? '');
$description = trim($_POST['description'] ?? '');
$gratification = isset($_POST['gratification']) && $_POST['gratification'] !== '' ? (float)$_POST['gratification'] : null;
$date_offre = trim($_POST['date_offre'] ?? '');
$duree = trim($_POST['duree'] ?? '');
$id_entreprise = (int)($_POST['id_entreprise'] ?? 0);

if (empty($titre)) {
    $_SESSION['error'] = "Le titre est obligatoire.";
    header('Location: index.php?page=ajouter_offre');
    exit;
}

if (empty($date_offre)) {
    $_SESSION['error'] = "La date est obligatoire.";
    header('Location: index.php?page=ajouter_offre');
    exit;
}

if ($id_entreprise <= 0) {
    $_SESSION['error'] = "Veuillez sélectionner une entreprise.";
    header('Location: index.php?page=ajouter_offre');
    exit;
}

$competences = isset($_POST['competences']) && is_array($_POST['competences']) ? array_map('intval', $_POST['competences']) : [];


$success = $model->createOffre($titre, $description, $gratification, $date_offre, $duree, $id_entreprise, $competences);

if ($success) {
    $_SESSION['success'] = "Offre ajoutée avec succès.";
    header('Location: index.php?page=offres');
} else {
    $_SESSION['error'] = "Erreur lors de l'ajout de l'offre.";
    header('Location: index.php?page=ajouter_offre');
}
exit;