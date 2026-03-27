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
                a.id_adresse,
                a.nom_rue,
                a.code_postal,
                a.ville,
                a.latitude,
                a.longitude
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

        if (!$offre) {
            return null;
        }

        return $this->geocodeAdresseIfNeeded($offre);
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

    private function geocodeAdresseIfNeeded(array $offre): array
    {
        if (
            empty($offre['id_adresse']) ||
            (
                !empty($offre['latitude']) &&
                !empty($offre['longitude']) &&
                !empty($offre['ville'])
            )
        ) {
            return $offre;
        }

        $street = trim((string)($offre['nom_rue'] ?? ''));
        $postalCode = trim((string)($offre['code_postal'] ?? ''));
        $existingCity = trim((string)($offre['ville'] ?? ''));

        if ($street === '' && $postalCode === '' && $existingCity === '') {
            return $offre;
        }

        $geocoded = $this->fetchGeocodingData($street, $postalCode, $existingCity);

        if (!$geocoded) {
            return $offre;
        }

        $sql = "
            UPDATE adresse
            SET ville = :ville,
                latitude = :latitude,
                longitude = :longitude
            WHERE id_adresse = :id_adresse
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':ville', $geocoded['ville'], PDO::PARAM_STR);
        $stmt->bindValue(':latitude', $geocoded['latitude']);
        $stmt->bindValue(':longitude', $geocoded['longitude']);
        $stmt->bindValue(':id_adresse', $offre['id_adresse'], PDO::PARAM_INT);
        $stmt->execute();

        $offre['ville'] = $geocoded['ville'];
        $offre['latitude'] = $geocoded['latitude'];
        $offre['longitude'] = $geocoded['longitude'];

        return $offre;
    }

    private function fetchGeocodingData(string $street, string $postalCode): ?array
    {
        $queries = [];

        if ($street || $postalCode) {
            $queries[] = trim("$street, $postalCode, France");
        }

        if ($postalCode) {
            $queries[] = "$postalCode, France";
        }

        foreach ($queries as $query) {

            $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
                'q' => $query,
                'format' => 'jsonv2',
                'limit' => 1,
                'addressdetails' => 1,
                'countrycodes' => 'fr',
            ]);

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'User-Agent: job2main/1.0 (contact: test@test.com)',
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            if (!$response) continue;

            $data = json_decode($response, true);

            if (empty($data[0])) continue;

            $result = $data[0];
            $address = $result['address'] ?? [];

            // récupération intelligente de la ville
            $city =
                $address['city']
                ?? $address['town']
                ?? $address['village']
                ?? $address['municipality']
                ?? $address['hamlet']
                ?? null;

            if (!isset($result['lat'], $result['lon'])) continue;

            return [
                'latitude' => (float)$result['lat'],
                'longitude' => (float)$result['lon'],
                'ville' => $city ?? '',
            ];
        }

        return null;
    }

    public function getOffresByDuree(): array
    {
        $sql = "
            SELECT
                CASE
                    WHEN duree <= 2 THEN '1-2 mois'
                    WHEN duree <= 5 THEN '3-5 mois'
                    ELSE 'Plus de 5 mois'
                END AS tranche,
                COUNT(*) AS total
            FROM offre
            GROUP BY tranche
            ORDER BY MIN(duree)
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}