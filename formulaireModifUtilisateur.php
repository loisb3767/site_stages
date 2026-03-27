<?php
require_once 'vendor/autoload.php';

use App\Models\TaskModel;

session_start();

$model = new TaskModel();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=modifier_profil');
    exit;
}

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] < 0) {
    $_SESSION['error'] = "Accès non autorisé.";
    header('Location: index.php?page=connexion');
    exit;
}

$id = $_SESSION['user']['id_utilisateur'];
$nom = trim($_POST['fullname'] ?? '');
$prenom = trim($_POST['firstname'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['phone'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');


if (empty($nom) || empty($prenom) || empty($email)) {
    $_SESSION['error'] = "Le nom, prénom et email sont obligatoires.";
    header('Location: index.php?page=modifier_profil');
    exit;
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Format d'email invalide.";
    header('Location: index.php?page=modifier_profil');
    exit;
}


$existingUser = $model->getUserByEmail($email);
if ($existingUser && $existingUser['id_utilisateur'] != $id) {
    $_SESSION['error'] = "Cet email est déjà utilisé par un autre compte.";
    header('Location: index.php?page=modifier_profil');
    exit;
}

if (!empty($password)) {
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header('Location: index.php?page=modifier_profil');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères.";
        header('Location: index.php?page=modifier_profil');
        exit;
    }
}


$success = $model->updateUser(
    $id,
    $nom,
    $prenom,
    $email,
    $telephone,
    !empty($password) ? $password : null 
);

if ($success) {
    $_SESSION['user']['nom_utilisateur'] = $nom;
    $_SESSION['user']['prenom_utilisateur'] = $prenom;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['telephone'] = $telephone;

    $_SESSION['success'] = "Profil modifié avec succès.";
    header('Location: index.php?page=profil');
} else {
    $_SESSION['error'] = "Erreur lors de la modification du profil.";
    header('Location: index.php?page=modifier_profil');
}
exit;