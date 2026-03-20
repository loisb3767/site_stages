<?php
require_once 'vendor/autoload.php';

use App\Services\Validator;
use App\Services\FileUploader;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $satisfaction = Validator::sanitize($_POST['motivation'] ?? '');
        $username = "Candidat " . time(); // Récuperer le username une fois qu'on a intégrer les connexions


        $filePath = "Aucun fichier";
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploader = new FileUploader();
            $filePath = $uploader->upload($_FILES['cv']);
        }

        $data = [
            'date' => date('r'),
            'username' => $username,
            'motivation' => $satisfaction,
            'cv' => $filePath
        ];

        $storageDir = 'postulerForms/';
        if (!is_dir($storageDir)) mkdir($storageDir, 755, true);
        
        $fileName = $storageDir . 'postuler_' . time() . '_' . uniqid() . '.json';
        file_put_contents($fileName, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        header('Location: index.php?page=postuler&status=success');
        exit;

    } catch (Exception $e) {
        $errorMsg = urlencode($e->getMessage());
        header("Location: index.php?page=postuler&error=$errorMsg");
        exit;
    }
}