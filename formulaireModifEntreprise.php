<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\TaskModel;

session_start();

$model = new TaskModel();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=entreprises');
    exit;
}

if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] < 1) {
    $_SESSION['error'] = "Accès non autorisé.";
    header('Location: index.php?page=connexion');
    exit;
}

$id_entreprise = (int)($_POST['id_entreprise'] ?? 0);
$nom = trim($_POST['nom_entreprise'] ?? '');
$description = trim($_POST['description'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$id_secteur = isset($_POST['id_secteur']) && $_POST['id_secteur'] !== '' ? (int)$_POST['id_secteur'] : null;

if (empty($id_entreprise)) {
    $_SESSION['error'] = "Entreprise introuvable.";
    header('Location: index.php?page=entreprises');
    exit;
}

if (empty($nom)) {
    $_SESSION['error'] = "Le nom est obligatoire.";
    header("Location: index.php?page=modifier_entreprise&id=$id_entreprise");
    exit;
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Format d'email invalide.";
    header("Location: index.php?page=modifier_entreprise&id=$id_entreprise");
    exit;
}

$success = $model->updateEntreprise($id_entreprise, $nom, $description, $email, $telephone, $id_secteur);

if ($success) {
    $_SESSION['success'] = "Entreprise modifiée avec succès.";
    header('Location: index.php?page=entreprises');
} else {
    $_SESSION['error'] = "Erreur lors de la modification.";
    header("Location: index.php?page=modifier_entreprise&id=$id_entreprise");
}
exit;