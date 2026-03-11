<?php
require_once 'vendor/autoload.php';

use App\Services\Validator;
use App\Services\FileUploader;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $fullname = Validator::sanitize($_POST['fullname'] ?? '');
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $subject = Validator::sanitize($_POST['subject'] ?? '');
        $feedback = Validator::sanitize($_POST['feedbacks'] ?? '');
        $satisfaction = Validator::sanitize($_POST['satisfaction'] ?? '');

        if (!$email) throw new Exception("Email invalide.");

        $filePath = "Aucun fichier";
        if (isset($_FILES['document']) && $_FILES['document']['error'] !== UPLOAD_ERR_NO_FILE) {
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
        if (!is_dir($storageDir)) mkdir($storageDir, 0755, true);
        
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