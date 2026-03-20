<?php
require_once 'vendor/autoload.php';

use App\Services\Validator;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $auth = Validator::sanitize($_POST['auth'] ?? '');
        $password = Validator::sanitize($_POST['password'] ?? '');

        //Vérification Email et username
        $isEmail = filter_var($auth, FILTER_VALIDATE_EMAIL);
        $isUsername = strlen($auth) >= 3;

        if (!$auth || (!$isEmail && !$isUsername)) {
            throw new Exception("Email ou identifiant invalide.");
        }

        // Validation du mot de passe
        if (!$password || strlen($password) < 8) {
            throw new Exception("Mot de passe invalide min 8 characteres.");
        }

        $data = [
            'date'     => date('r'),
            'auth'     => $auth,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ];

        $storageDir = 'connexionForms/';
        if (!is_dir($storageDir)) mkdir($storageDir, 0755, true);

        $fileName = $storageDir . 'connexion_' . time() . '_' . uniqid() . '.json';
        file_put_contents($fileName, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        header('Location: index.php?page=connexion&status=success');
        exit;

    } catch (Exception $e) {
        $errorMsg = urlencode($e->getMessage());
        header("Location: index.php?page=connexion&error=$errorMsg");
        exit;
    }
}

