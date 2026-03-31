<?php

require_once 'vendor/autoload.php';

use App\Models\TaskModel;

session_start();

$model = new TaskModel();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=offres');
    exit;
}

// Vérification que l'utilisateur est connecté et est pilote ou admin
if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] < 1) {
    $_SESSION['error'] = "Accès non autorisé.";
    header('Location: index.php?page=connexion');
    exit;
}

$id_offre = (int)($_POST['id_offre'] ?? 0);
$titre = trim($_POST['titre'] ?? '');
$description = trim($_POST['description'] ?? '');
$gratification = isset($_POST['gratification']) && $_POST['gratification'] !== '' ? (float)$_POST['gratification'] : null;
$date_offre = trim($_POST['date_offre'] ?? '');
$duree = trim($_POST['duree'] ?? '');

// Validation
if (empty($id_offre)) {
    $_SESSION['error'] = "Offre introuvable.";
    header('Location: index.php?page=offres');
    exit;
}

if (empty($titre)) {
    $_SESSION['error'] = "Le titre est obligatoire.";
    header("Location: index.php?page=modifier_offre&id=$id_offre");
    exit;
}

if (empty($date_offre)) {
    $_SESSION['error'] = "La date est obligatoire.";
    header("Location: index.php?page=modifier_offre&id=$id_offre");
    exit;
}

if (!empty($gratification) && $gratification < 0) {
    $_SESSION['error'] = "La gratification ne peut pas être négative.";
    header("Location: index.php?page=modifier_offre&id=$id_offre");
    exit;
}

$success = $model->updateOffre($id_offre, $titre, $description, $gratification, $date_offre, $duree);

if ($success) {
    $_SESSION['success'] = "Offre modifiée avec succès.";
    header('Location: index.php?page=offres');
} else {
    $_SESSION['error'] = "Erreur lors de la modification de l'offre.";
    header("Location: index.php?page=modifier_offre&id=$id_offre");
}
exit;