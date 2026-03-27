<?php
require_once 'vendor/autoload.php';

use App\Models\TaskModel;

session_start();

$model = new TaskModel();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=inscription');
    exit;
}

if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] <= 0) {
    $_SESSION['error'] = "Accès non autorisé.";
    header('Location: index.php?page=inscription');
    exit;
}

$nom = trim($_POST['nom_utilisateur'] ?? '');
$prenom = trim($_POST['prenom_utilisateur'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$password = trim($_POST['password'] ?? '');
$id_role = isset($_POST['id_role']) ? (int) $_POST['id_role'] : -1;

if (empty($email) || empty($password)) {
    $_SESSION['error'] = "L'email et le mot de passe sont obligatoires.";
    header('Location: index.php?page=inscription');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Format d'email invalide.";
    header('Location: index.php?page=inscription');
    exit;
}

if (!$model->roleExists($id_role)) {
    $_SESSION['error'] = "Le rôle sélectionné est invalide.";
    header('Location: index.php?page=inscription');
    exit;
}

if ($model->getUserByEmail($email)) {
    $_SESSION['error'] = "Cet email est déjà utilisé.";
    header('Location: index.php?page=inscription');
    exit;
}

// sécurité côté serveur
if ($_SESSION['user']['id_role'] == 1 && $id_role != 0) {
    $_SESSION['error'] = "Un pilote ne peut créer qu'un compte étudiant.";
    header('Location: index.php?page=inscription');
    exit;
}

$success = $model->createUser(
    $nom,
    $prenom,
    $email,
    $telephone,
    $password,
    $id_role
);

if ($success) {
    $_SESSION['success'] = "Utilisateur créé avec succès.";
} else {
    $_SESSION['error'] = "Erreur lors de la création de l'utilisateur.";
}

header('Location: index.php?page=inscription');
exit;