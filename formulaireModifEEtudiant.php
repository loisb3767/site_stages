<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\TaskModel;

session_start();

$model = new TaskModel();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=liste_etudiant');
    exit;
}

if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] < 1) {
    $_SESSION['error'] = "Accès non autorisé.";
    header('Location: index.php?page=connexion');
    exit;
}

$id_etudiant = (int)($_POST['id_etudiant'] ?? 0);
$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

if (empty($id_etudiant)) {
    $_SESSION['error'] = "Étudiant introuvable.";
    header('Location: index.php?page=liste_etudiant');
    exit;
}

if (empty($nom) || empty($prenom) || empty($email)) {
    $_SESSION['error'] = "Le nom, prénom et email sont obligatoires.";
    header("Location: index.php?page=modifier_etudiant&id=$id_etudiant");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Format d'email invalide.";
    header("Location: index.php?page=modifier_etudiant&id=$id_etudiant");
    exit;
}

$existingUser = $model->getUserByEmail($email);
if ($existingUser && $existingUser['id_utilisateur'] != $id_etudiant) {
    $_SESSION['error'] = "Cet email est déjà utilisé.";
    header("Location: index.php?page=modifier_etudiant&id=$id_etudiant");
    exit;
}

if (!empty($password)) {
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: index.php?page=modifier_etudiant&id=$id_etudiant");
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères.";
        header("Location: index.php?page=modifier_etudiant&id=$id_etudiant");
        exit;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
}

$success = $model->updateUser(
    $id_etudiant,
    $nom,
    $prenom,
    $email,
    $telephone,
    !empty($password) ? $password : null
);

if ($success) {
    $_SESSION['success'] = "Étudiant modifié avec succès.";
    header('Location: index.php?page=liste_etudiant');
} else {
    $_SESSION['error'] = "Erreur lors de la modification.";
    header("Location: index.php?page=modifier_etudiant&id=$id_etudiant");
}
exit;