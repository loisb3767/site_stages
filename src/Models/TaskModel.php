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
                s.nom_secteur,
                a.nom_rue,
                a.code_postal
            FROM offre o
            INNER JOIN entreprise e ON o.id_entreprise = e.id_entreprise
            LEFT JOIN secteur s ON e.id_secteur = s.id_secteur
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

    public function getPaginatedOffres(int $page, int $parPage, array $competenceIds = []): array
    {
        $offset = ($page - 1) * $parPage;

        if (empty($competenceIds)) {
            $sql = "
                SELECT
                    o.id_offre,
                    o.titre,
                    o.description,
                    o.gratification,
                    o.date_offre,
                    o.duree,
                    e.nom_entreprise,
                    s.nom_secteur
                FROM offre o
                INNER JOIN entreprise e ON o.id_entreprise = e.id_entreprise
                LEFT JOIN secteur s ON e.id_secteur = s.id_secteur
                ORDER BY o.date_offre DESC, o.id_offre DESC
                LIMIT :limit OFFSET :offset
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $parPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        }

        // paramètres dynamiques
        $namedParams = [];
        $placeholders = [];

        foreach ($competenceIds as $index => $id) {
            $param = ':comp' . $index;
            $placeholders[] = $param;
            $namedParams[$param] = $id;
        }

        $sql = "
            SELECT
                o.id_offre,
                o.titre,
                o.description,
                o.gratification,
                o.date_offre,
                o.duree,
                e.nom_entreprise,
                s.nom_secteur
            FROM offre o
            INNER JOIN entreprise e ON o.id_entreprise = e.id_entreprise
            LEFT JOIN secteur s ON e.id_secteur = s.id_secteur
            INNER JOIN offre_competence oc ON o.id_offre = oc.id_offre
            WHERE oc.id_competence IN (" . implode(',', $placeholders) . ")
            GROUP BY
                o.id_offre, o.titre, o.description, o.gratification,
                o.date_offre, o.duree, e.nom_entreprise, s.nom_secteur
            HAVING COUNT(DISTINCT oc.id_competence) = :nb
            ORDER BY o.date_offre DESC, o.id_offre DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);

        foreach ($namedParams as $param => $value) {
            $stmt->bindValue($param, $value, PDO::PARAM_INT);
        }

        $stmt->bindValue(':nb', count($competenceIds), PDO::PARAM_INT);
        $stmt->bindValue(':limit', $parPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getTotalCount(array $competenceIds = []): int
    {
        if (empty($competenceIds)) {
            return (int) $this->pdo->query("SELECT COUNT(*) FROM offre")->fetchColumn();
        }

        $namedParams = [];
        $placeholders = [];

        foreach ($competenceIds as $index => $id) {
            $param = ':comp' . $index;
            $placeholders[] = $param;
            $namedParams[$param] = $id;
        }

        $sql = "
            SELECT COUNT(*) FROM (
                SELECT o.id_offre
                FROM offre o
                INNER JOIN offre_competence oc ON o.id_offre = oc.id_offre
                WHERE oc.id_competence IN (" . implode(',', $placeholders) . ")
                GROUP BY o.id_offre
                HAVING COUNT(DISTINCT oc.id_competence) = :nb
            ) AS filtered
        ";

        $stmt = $this->pdo->prepare($sql);

        foreach ($namedParams as $param => $value) {
            $stmt->bindValue($param, $value, PDO::PARAM_INT);
        }

        $stmt->bindValue(':nb', count($competenceIds), PDO::PARAM_INT);

        $stmt->execute();

        return (int) $stmt->fetchColumn();
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

    public function getAllSecteurs(): array
    {
        $sql = "
            SELECT id_secteur, nom_secteur
            FROM secteur
            ORDER BY nom_secteur ASC
        ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getAllCompetences(): array
    {
        $sql = "
            SELECT id_competence, nom_competence
            FROM competence
            ORDER BY nom_competence ASC
        ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function getUserById($id) {
        $sql = "SELECT email, nom_utilisateur, prenom_utilisateur, telephone FROM utilisateur WHERE id_utilisateur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getLatestOffres(int $limit = 5): array {
        $sql="
            SELECT
                o.id_offre,
                o.titre,
                o.date_offre,
                e.nom_entreprise
            FROM offre o
            INNER JOIN entreprise e ON o.id_entreprise = e.id_entreprise
            ORDER BY o.date_offre DESC, o.id_offre DESC
            LIMIT :limit
        ";

        $stmt=$this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}