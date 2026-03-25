<?php
require_once 'vendor/autoload.php';
require_once 'db.php';

use App\Services\Validator;
use App\Services\FileUploader;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_SESSION['user'])) {
            throw new Exception("Vous devez être connecté pour postuler.");
        }

        $userId = $_SESSION['user']['id_utilisateur'];
        $idOffre = isset($_POST['id_offre']) ? (int) $_POST['id_offre'] : 0;
        $motivation = Validator::sanitize($_POST['motivation'] ?? '');

        if ($idOffre <= 0) {
            throw new Exception("Offre invalide.");
        }

        $filePath = null;

        if (isset($_FILES['cv']) && $_FILES['cv']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploader = new FileUploader();
            $filePath = $uploader->upload($_FILES['cv']);
        }

        // Vérifier que l'offre existe
        $sqlCheckOffre = "SELECT id_offre FROM offre WHERE id_offre = :id_offre LIMIT 1";
        $stmtCheckOffre = $dbh->prepare($sqlCheckOffre);
        $stmtCheckOffre->bindValue(':id_offre', $idOffre, PDO::PARAM_INT);
        $stmtCheckOffre->execute();

        if (!$stmtCheckOffre->fetch()) {
            throw new Exception("L'offre sélectionnée n'existe pas.");
        }

        // Éviter les doublons de candidature
        $sqlCheckCandidature = "
            SELECT id_candidature
            FROM candidature
            WHERE id_utilisateur = :id_utilisateur AND id_offre = :id_offre
            LIMIT 1
        ";
        $stmtCheckCandidature = $dbh->prepare($sqlCheckCandidature);
        $stmtCheckCandidature->bindValue(':id_utilisateur', $userId, PDO::PARAM_INT);
        $stmtCheckCandidature->bindValue(':id_offre', $idOffre, PDO::PARAM_INT);
        $stmtCheckCandidature->execute();

        if ($stmtCheckCandidature->fetch()) {
            throw new Exception("Vous avez déjà postulé à cette offre.");
        }

        $sqlInsert = "
            INSERT INTO candidature (
                cv,
                lettre_motivation,
                date_candidature,
                id_utilisateur,
                id_offre
            ) VALUES (
                :cv,
                :lettre_motivation,
                CURDATE(),
                :id_utilisateur,
                :id_offre
            )
        ";

        $stmtInsert = $dbh->prepare($sqlInsert);
        $stmtInsert->bindValue(':cv', $filePath, PDO::PARAM_STR);
        $stmtInsert->bindValue(':lettre_motivation', $motivation, PDO::PARAM_STR);
        $stmtInsert->bindValue(':id_utilisateur', $userId, PDO::PARAM_INT);
        $stmtInsert->bindValue(':id_offre', $idOffre, PDO::PARAM_INT);
        $stmtInsert->execute();

        header('Location: index.php?page=postuler&id=' . $idOffre . '&status=success');
        exit;

    } catch (Exception $e) {
        $errorMsg = urlencode($e->getMessage());
        header("Location: index.php?page=postuler&id=" . $idOffre . "&error=$errorMsg");
        exit;
    }
}