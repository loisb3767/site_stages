<?php
namespace App\Services;

use Exception;
use finfo;

class FileUploader
{
    private int $maxSize;
    private string $uploadDir;
    private array $allowedMimeTypes = [
        'application/pdf',
        'application/msword',                                                      // .doc
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
        'application/vnd.oasis.opendocument.text',                                 // .odt
        'application/rtf',
        'text/rtf',
        'image/jpeg',
        'image/png',
    ];

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

        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            throw new Exception("Format non autorisé.");
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        // Extension selon le mime type
        $extensions = [
            'application/pdf'        => 'pdf',
            'application/msword'     => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.oasis.opendocument.text' => 'odt',
            'application/rtf'        => 'rtf',
            'text/rtf'               => 'rtf',
            'image/jpeg'             => 'jpg',
            'image/png'              => 'png',
        ];

        $ext = $extensions[$mimeType];
        $fileName = uniqid('file', true) . '.' . $ext;
        $destination = $this->uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Error moving file.");
        }

        return $destination;
    }
}