<?php
require_once 'vendor/autoload.php';

use App\Services\Validator;
use App\Services\FileUploader;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $satisfaction = Validator::sanitize($_POST['motivation'] ?? '');

        if (!$email) throw new Exception("Email invalide.");

        $filePath = "Aucun fichier";
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploader = new FileUploader();
            $filePath = $uploader->upload($_FILES['document']);
        }

        $data = [
            'date' => date('r'),
            'nom' => $fullname,
            'email' => $email,
            'sujet' => $subject,
            'satisfaction' => $satisfaction,
            'message' => $feedback,
            'fichier' => $filePath
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