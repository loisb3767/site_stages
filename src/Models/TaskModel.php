<?php
namespace App\Models;

use PDO;

class TaskModel extends Model
{
    private PDO $pdo;

    public function __construct()
    {
        require 'db.php';
        $this->pdo = $dbh;
    }

    public function getAllOffres(): array
    {
        $sql = "
            SELECT
                o.id_offre,
                o.titre,
                o.description,
                o.gratification,
                o.date_offre,
                o.duree,
                e.nom_entreprise
            FROM offre o
            INNER JOIN entreprise e ON o.id_entreprise = e.id_entreprise
            ORDER BY o.date_offre DESC, o.id_offre DESC
        ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getOffreById(int $id): array|null
    {
        $sql = "
            SELECT
                o.id_offre,
                o.titre,
                o.description,
                o.gratification,
                o.date_offre,
                o.duree,
                e.nom_entreprise,
                e.description AS entreprise_description,
                e.email AS entreprise_email,
                e.telephone AS entreprise_telephone,
                a.nom_rue,
                a.code_postal
            FROM offre o
            INNER JOIN entreprise e ON o.id_entreprise = e.id_entreprise
            LEFT JOIN entreprise_adresse ea ON e.id_entreprise = ea.id_entreprise
            LEFT JOIN adresse a ON ea.id_adresse = a.id_adresse
            WHERE o.id_offre = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $offre = $stmt->fetch();

        return $offre ?: null;
    }

    public function getPaginatedOffres(int $page, int $parPage): array
    {
        $indexDepart = ($page - 1) * $parPage;

        $sql = "
            SELECT
                o.id_offre,
                o.titre,
                o.description,
                o.gratification,
                o.date_offre,
                o.duree,
                e.nom_entreprise
            FROM offre o
            INNER JOIN entreprise e ON o.id_entreprise = e.id_entreprise
            ORDER BY o.date_offre DESC, o.id_offre DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $parPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $indexDepart, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getTotalCount(): int
    {
        $sql = "SELECT COUNT(*) FROM offre";
        return (int) $this->pdo->query($sql)->fetchColumn();
    }

    public function getCompetencesByOffreId(int $idOffre): array
    {
        $sql = "
            SELECT c.nom_competence
            FROM offre_competence oc
            INNER JOIN competence c ON oc.id_competence = c.id_competence
            WHERE oc.id_offre = :id_offre
            ORDER BY c.nom_competence ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_offre', $idOffre, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function detailOffrePage(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            die('ID offre invalide.');
        }

        $offre = $this->model->getOffreById($id);

        if (!$offre) {
            die('Offre introuvable.');
        }

        $competences = $this->model->getCompetencesByOffreId($id);

        $offre['competences'] = array_map(
            fn($comp) => $comp['nom_competence'],
            $competences
        );

        echo $this->templateEngine->render('detailOffre.twig.html', [
            'offre' => $offre,
            'active_page' => 'offres',
        ]);
    }
}