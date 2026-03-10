<?php
namespace App\Services;

use Exception;
use finfo;

class FileUploader
{
    private int $maxSize;
    private string $uploadDir;

    public function __construct(int $maxSize = 2097152, string $uploadDir = "uploads/")
    {
        $this->maxSize = $maxSize;
        $this->uploadDir = $uploadDir;
    }

    public function upload(array $file): string
    {
        if ($file['error'] !== 0) {
            throw new Exception("Error uploading file.");
        }

        if ($file['size'] > $this->maxSize) {
            throw new Exception("File too large (max 2MB).");
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if ($mimeType !== 'application/pdf') {
            throw new Exception("Only PDF files allowed.");
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        $fileName = uniqid('file', true) . '.pdf';
        $destination = $this->uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Error moving file.");
        }

        return $destination;
    }
}