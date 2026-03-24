<?php
require_once 'vendor/autoload.php';
require_once 'db.php';

use App\Services\Validator;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = Validator::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide.");
        }

        // Validation mot de passe
        if (!$password || strlen($password) < 8) {
            throw new Exception("Mot de passe invalide.");
        }

        // Requête SQL
        $sql = "
            SELECT id_utilisateur, nom_utilisateur, prenom_utilisateur, email, mot_de_passe, id_role
            FROM utilisateur
            WHERE email = :email
            LIMIT 1
        ";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception("Utilisateur introuvable.");
        }

        // Vérification du mot de passe
        if ($password !== $user['mot_de_passe']) {
            throw new Exception("Mot de passe incorrect.");
        }

        // Création session
        $_SESSION['user'] = [
            'id_utilisateur' => $user['id_utilisateur'],
            'nom_utilisateur' => $user['nom_utilisateur'],
            'prenom_utilisateur' => $user['prenom_utilisateur'],
            'email' => $user['email'],
            'id_role' => $user['id_role'],
        ];

        header('Location: index.php?page=profil');
        exit;

    } catch (Exception $e) {
        $errorMsg = urlencode($e->getMessage());
        header("Location: index.php?page=connexion&error=$errorMsg");
        exit;
    }
}