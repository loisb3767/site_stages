<?php
require_once 'vendor/autoload.php';
require_once 'db.php';

use App\Services\Validator;

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=avis');
    exit;
}

try {
    if (!isset($_SESSION['user']['id_utilisateur'])) {
        throw new Exception('Vous devez être connecté pour laisser un avis.');
    }

    $idUtilisateur = (int) $_SESSION['user']['id_utilisateur'];
    $idEntreprise = (int) ($_POST['id_entreprise'] ?? 0);
    $note = (int) ($_POST['note'] ?? 0);
    $commentaire = trim(Validator::sanitize($_POST['commentaire'] ?? ''));

    if ($idEntreprise <= 0) {
        throw new Exception("Veuillez sélectionner une entreprise.");
    }

    if ($note < 1 || $note > 5) {
        throw new Exception("La note doit être comprise entre 1 et 5.");
    }

    if ($commentaire === '') {
        throw new Exception("Le commentaire est obligatoire.");
    }

    $checkEntreprise = $dbh->prepare('SELECT COUNT(*) FROM entreprise WHERE id_entreprise = :id_entreprise');
    $checkEntreprise->bindValue(':id_entreprise', $idEntreprise, PDO::PARAM_INT);
    $checkEntreprise->execute();

    if ((int) $checkEntreprise->fetchColumn() === 0) {
        throw new Exception("Entreprise introuvable.");
    }

    $sql = 'INSERT INTO avis (commentaire, note, date_avis, id_utilisateur, id_entreprise)
            VALUES (:commentaire, :note, CURDATE(), :id_utilisateur, :id_entreprise)';

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
    $stmt->bindValue(':note', $note, PDO::PARAM_INT);
    $stmt->bindValue(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
    $stmt->bindValue(':id_entreprise', $idEntreprise, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: index.php?page=avis&status=success');
    exit;
} catch (Exception $e) {
    $errorMsg = urlencode($e->getMessage());
    header("Location: index.php?page=avis&error={$errorMsg}");
    exit;
}
