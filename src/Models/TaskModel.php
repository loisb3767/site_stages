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
            ORDER BY o.date_offre ASC
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
    public function getTotalCountEntreprises(array $competenceId = []): int{
        if (empty($competenceId)) {
            return (int) $this->pdo->query("SELECT COUNT(*) FROM entreprise")->fetchColumn();
        }

        $placeholders = [];
        $namedParams = [];

        foreach ($competenceId as $index => $id) {
            $param = ':secteur' . $index;
            $placeholders[] = $param;
            $namedParams[$param] = $id;
        }

        $sql = "
            SELECT COUNT(*)
            FROM entreprise e
            WHERE e.id_secteur IN (" . implode(',', $placeholders) . ")
            ";

        $stmt = $this->pdo->prepare($sql);
        foreach ($namedParams as $param => $value) {
        $stmt->bindValue($param, $value, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
            
    public function getPaginatedOffres(int $page, int $parPage, string $q = '', array $competenceIds = []): array
    {
        $offset = ($page - 1) * $parPage;

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
            " . (!empty($competenceIds) ? "INNER JOIN offre_competence oc ON o.id_offre = oc.id_offre" : "") . "
            WHERE o.date_offre >= CURDATE()
        ";

        $params = [];

        if ($q !== '') {
            $sql .= " AND (o.titre LIKE :q OR o.description LIKE :q OR e.nom_entreprise LIKE :q) ";
            $params[':q'] = '%' . $q . '%';
        }

        if (!empty($competenceIds)) {
            $placeholders = [];
            foreach ($competenceIds as $i => $id) {
                $ph = ':comp' . $i;
                $placeholders[] = $ph;
                $params[$ph] = $id;
            }

            $sql .= " AND oc.id_competence IN (" . implode(',', $placeholders) . ") ";
            $sql .= "
                GROUP BY o.id_offre, o.titre, o.description, o.gratification, o.date_offre, o.duree, e.nom_entreprise, s.nom_secteur
                HAVING COUNT(DISTINCT oc.id_competence) = :nb
            ";
            $params[':nb'] = count($competenceIds);
        }

        $sql .= " ORDER BY o.date_offre ASC, o.id_offre ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }
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
        $sql = "SELECT email, nom_utilisateur, prenom_utilisateur, telephone, id_role FROM utilisateur WHERE id_utilisateur = :id";
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
            WHERE o.date_offre >= CURRENT_DATE
            ORDER BY o.date_offre ASC, o.id_offre DESC
            LIMIT :limit
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function geocodeAdresseIfNeeded(array $entity): array
    {
        if (
            empty($entity['id_adresse']) ||
            (
                !empty($entity['latitude']) &&
                !empty($entity['longitude']) &&
                !empty($entity['ville'])
            )
        ) {
            return $entity;
        }

        $street = trim((string)($entity['nom_rue'] ?? ''));
        $postalCode = trim((string)($entity['code_postal'] ?? ''));
        $existingCity = trim((string)($entity['ville'] ?? ''));

        if ($street === '' && $postalCode === '' && $existingCity === '') {
            return $entity;
        }

        $geocoded = $this->fetchGeocodingData($street, $postalCode, $existingCity);

        if (!$geocoded) {
            return $entity;
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
        $stmt->bindValue(':id_adresse', $entity['id_adresse'], PDO::PARAM_INT);
        $stmt->execute();

        $entity['ville'] = $geocoded['ville'];
        $entity['latitude'] = $geocoded['latitude'];
        $entity['longitude'] = $geocoded['longitude'];

        return $entity;
    }

    private function fetchGeocodingData(string $street, string $postalCode, string $existingCity = ''): ?array
    {
        $queries = [];

        if ($street || $postalCode || $existingCity) {
            $queries[] = trim(implode(', ', array_filter([
                $street,
                $postalCode,
                $existingCity,
                'France'
            ])));
        }

        if ($street || $postalCode) {
            $query = trim(implode(', ', array_filter([
                $street,
                $postalCode,
                'France'
            ])));

            if (!in_array($query, $queries, true)) {
                $queries[] = $query;
            }
        }

        if ($postalCode) {
            $query = trim(implode(', ', array_filter([
                $postalCode,
                'France'
            ])));

            if (!in_array($query, $queries, true)) {
                $queries[] = $query;
            }
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
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response = curl_exec($ch);
            curl_close($ch);

            if (!$response) {
                continue;
            }

            $data = json_decode($response, true);

            if (empty($data[0])) {
                continue;
            }

            $result = $data[0];
            $address = $result['address'] ?? [];

            $city =
                $address['city']
                ?? $address['town']
                ?? $address['village']
                ?? $address['municipality']
                ?? $address['hamlet']
                ?? $existingCity
                ?? '';

            if (!isset($result['lat'], $result['lon'])) {
                continue;
            }

            return [
                'latitude' => (float) $result['lat'],
                'longitude' => (float) $result['lon'],
                'ville' => $city,
            ];
        }

        return null;
    }
    
    //Données Carrousel nombre d'offres par durée
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

    public function getTopWishlist(): array
    {
        $sql = "
            SELECT 
                o.titre,
                e.nom_entreprise,
                COUNT(DISTINCT w.id_utilisateur) AS nb_wishlist
            FROM offre o
            JOIN wishlist w ON o.id_offre = w.id_offre
            JOIN entreprise e ON o.id_entreprise = e.id_entreprise
            GROUP BY o.id_offre, o.titre, e.nom_entreprise
            ORDER BY nb_wishlist DESC
            LIMIT 3
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function createUser($nom, $prenom, $email, $password, $id_role, $telephone = NULL) {

    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO utilisateur (
                nom_utilisateur,
                prenom_utilisateur,
                email,
                telephone,
                mot_de_passe,
                id_role
            ) VALUES (
                :nom,
                :prenom,
                :email,
                :telephone,
                :password,
                :id_role
            )";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $email,
        ':telephone' => $telephone,
        ':password' => $password,
        ':id_role' => $id_role
    ]);
    }

    public function getAllRoles() {
        $sql = "SELECT id_role, nom_role FROM role ORDER BY id_role";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function roleExists($id_role) {
        $sql = "SELECT COUNT(*) FROM role WHERE id_role = :id_role";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_role' => $id_role]);
        return $stmt->fetchColumn() > 0;
    }

    public function updateUser($id, $nom, $prenom, $email, $telephone, $password = null) {
        $sql = "UPDATE utilisateur SET
                    nom_utilisateur = :nom,
                    prenom_utilisateur = :prenom,
                    email = :email,
                    telephone = :telephone";

        if ($password !== null) {
            $sql .= ", mot_de_passe = :password";
        }

        $sql .= " WHERE id_utilisateur = :id";

        $stmt = $this->pdo->prepare($sql);

        $params = [
            'id' => $id,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $telephone
        ];

        if ($password !== null) {
            $params['password'] = $password;
        }

        return $stmt->execute($params);
    }

    public function getWishlistByUserId($id) {
    $sql = "SELECT o.* 
            FROM wishlist w
            JOIN offre o ON w.id_offre = o.id_offre
            WHERE w.id_utilisateur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }

    public function offresDejaPostules($id) {
    $sql = "SELECT o.titre, o.description, c.date_candidature, c.id_offre, c.statut
            FROM candidature c
            JOIN offre o ON c.id_offre = o.id_offre
            WHERE c.id_utilisateur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }
    
    public function getTotalEntreprises(array $secteurs = []): int
    {
        if (empty($secteurs)) {
            return (int) $this->pdo->query("SELECT COUNT(*) FROM entreprise")->fetchColumn();
        }

        $placeholders = implode(',', array_fill(0, count($secteurs), '?'));

        $sql = "
            SELECT COUNT(*)
            FROM entreprise
            WHERE id_secteur IN ($placeholders)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($secteurs));

        return (int) $stmt->fetchColumn();
    }

    public function getPaginatedEntreprises(int $page, int $parPage, array $secteurs = []): array{
        $offset = ($page - 1) * $parPage;

        if (empty($secteurs)) {
            $sql = "SELECT * FROM entreprise LIMIT ? OFFSET ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $parPage, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        $placeholders = implode(',', array_fill(0, count($secteurs), '?'));

        $sql = "
            SELECT *
            FROM entreprise
            WHERE id_secteur IN ($placeholders)
            LIMIT ? OFFSET ?
        ";

        $stmt = $this->pdo->prepare($sql);

        $i = 1;
        foreach ($secteurs as $secteur) {
            $stmt->bindValue($i++, $secteur, PDO::PARAM_INT);
        }

        $stmt->bindValue($i++, $parPage, PDO::PARAM_INT);
        $stmt->bindValue($i, $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getSecteursByEntrepriseId(int $id): array
    {
        $sql = "
            SELECT s.nom_secteur
            FROM secteur s
            JOIN entreprise e ON e.id_secteur = s.id_secteur
            WHERE e.id_entreprise = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function removeFromWishlist($userId, $offreId) {
        try {
        $sql = "DELETE FROM wishlist WHERE id_utilisateur = :userId AND id_offre = :offreId";
        $stmt = $this->pdo->prepare($sql);
        $params = [
            'userId' => $userId,
            'offreId' => $offreId
        ];
        $stmt->execute($params);
        } catch (Exception $e) {
            die("Erreur lors de la suppression de la wishlist : " . $e->getMessage());
        }
    }

    public function addToWishlist($userId, $offreId) {
        if($userId != 0) {
            try {
            
                $sql = "INSERT IGNORE INTO wishlist (id_utilisateur, id_offre) VALUES (:userId, :offreId)";
                $stmt = $this->pdo->prepare($sql);
                $params = [
                    'userId' => $userId,
                    'offreId' => $offreId
                ];
                $stmt->execute($params);
            }
            catch (Exception $e) {
                die("Erreur lors de l'ajout à la wishlist : " . $e->getMessage());
            }
        }
    }

    public function getNbOffres(): int
        {
            $sql = "SELECT COUNT(*) FROM offre";
            $stmt = $this->pdo->query($sql);
            return (int) $stmt->fetchColumn();
        }

    public function getNbCandidaturesParOffre(): float
    {
        $sql = "
            SELECT AVG(nb_candidatures) FROM (
                SELECT COUNT(c.id_candidature) AS nb_candidatures
                FROM offre o
                LEFT JOIN candidature c ON c.id_offre = o.id_offre
                GROUP BY o.id_offre
            ) AS sous_requete
        ";

        $stmt = $this->pdo->query($sql);
        return round((float) $stmt->fetchColumn(), 1);
    }

    public function getPaginatedOffresSearch(int $page, int $parPage, string $q): array
    {
        $offset = ($page - 1) * $parPage;
        $like = '%' . $q . '%';

        $sql = "
            SELECT o.id_offre, o.titre, o.description, o.gratification,
                o.date_offre, o.duree, e.nom_entreprise, s.nom_secteur
            FROM offre o
            INNER JOIN entreprise e ON o.id_entreprise = e.id_entreprise
            LEFT JOIN secteur s ON e.id_secteur = s.id_secteur
            WHERE (o.titre LIKE :q OR o.description LIKE :q OR e.nom_entreprise LIKE :q)
            ORDER BY o.date_offre ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':q', $like);
        $stmt->bindValue(':limit', $parPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalCountSearch(string $q): int
    {
        $like = '%' . $q . '%';

        $sql = "
            SELECT COUNT(*)
            FROM offre o
            INNER JOIN entreprise e ON o.id_entreprise = e.id_entreprise
            WHERE (o.titre LIKE :q OR o.description LIKE :q OR e.nom_entreprise LIKE :q)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':q', $like);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    } 
    public function getPaginatedEntreprisesSearch(int $page, int $parPage, string $q): array
    {
        $offset = ($page - 1) * $parPage;
        $like = '%' . $q . '%';

        $sql = "
            SELECT *
            FROM entreprise
            WHERE nom_entreprise LIKE :q
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':q', $like);
        $stmt->bindValue(':limit', $parPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalEntreprisesSearch(string $q): int
    {
        $like = '%' . $q . '%';
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM entreprise WHERE nom_entreprise LIKE :q");
        $stmt->bindValue(':q', $like);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function deleteOffre(int $id): bool {
        // Supprimer les compétences liées à l'offre
        $stmt = $this->pdo->prepare("DELETE FROM offre_competence WHERE id_offre = :id");
        $stmt->execute([':id' => $id]);

        // Supprimer les candidatures liées à l'offre
        $stmt = $this->pdo->prepare("DELETE FROM candidature WHERE id_offre = :id");
        $stmt->execute([':id' => $id]);

        // Supprimer les wishlists liées à l'offre
        $stmt = $this->pdo->prepare("DELETE FROM wishlist WHERE id_offre = :id");
        $stmt->execute([':id' => $id]);

        // Supprimer l'offre
        $stmt = $this->pdo->prepare("DELETE FROM offre WHERE id_offre = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function updateOffre(int $id, string $titre, string $description, ?float $gratification, string $date_offre, string $duree): bool {
        $sql = "UPDATE offre SET
                    titre = :titre,
                    description = :description,
                    gratification = :gratification,
                    date_offre = :date_offre,
                    duree = :duree
                WHERE id_offre = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':titre' => $titre,
            ':description' => $description,
            ':gratification' => $gratification,
            ':date_offre' => $date_offre,
            ':duree' => $duree
        ]);
    }

    public function createOffre(string $titre, string $description, ?float $gratification, string $date_offre, string $duree, int $id_entreprise, array $competences = []): bool {
        // Insérer l'offre
        $sql = "INSERT INTO offre (titre, description, gratification, date_offre, duree, id_entreprise)
                VALUES (:titre, :description, :gratification, :date_offre, :duree, :id_entreprise)";

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':gratification' => $gratification,
            ':date_offre' => $date_offre,
            ':duree' => $duree,
            ':id_entreprise' => $id_entreprise
        ]);

        if (!$result) return false;

        // Récupérer l'id de l'offre créée
        $id_offre = (int)$this->pdo->lastInsertId();

        // Insérer les compétences
        if (!empty($competences)) {
            $stmt = $this->pdo->prepare("INSERT INTO offre_competence (id_offre, id_competence) VALUES (:id_offre, :id_competence)");
            foreach ($competences as $id_competence) {
                $stmt->execute([
                    ':id_offre' => $id_offre,
                    ':id_competence' => $id_competence
                ]);
            }
        }

        return true;
    }

    public function getAllEntreprises(): array {
        $sql = "SELECT id_entreprise, nom_entreprise FROM entreprise ORDER BY nom_entreprise ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }


    public function getEntrepriseById(int $id): array|null
    {
        $sql = "
            SELECT
                e.id_entreprise,
                e.nom_entreprise,
                e.description,
                e.email,
                e.telephone,
                a.id_adresse,
                a.nom_rue,
                a.code_postal,
                a.ville,
                a.latitude,
                a.longitude
            FROM entreprise e
            LEFT JOIN entreprise_adresse ea ON e.id_entreprise = ea.id_entreprise
            LEFT JOIN adresse a ON ea.id_adresse = a.id_adresse
            WHERE e.id_entreprise = :id
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $entreprise = $stmt->fetch();

        if (!$entreprise) {
            return null;
        }

        return $this->geocodeAdresseIfNeeded($entreprise);
    }

    public function getOffresByEntrepriseId(int $id): array {
        $sql = "SELECT id_offre, titre, description, gratification, date_offre, duree
                FROM offre
                WHERE id_entreprise = :id
                ORDER BY date_offre DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll();
    }

    public function createEntreprise(string $nom, string $description, string $email, string $telephone, ?int $id_secteur, string $nom_rue = '', string $code_postal = '', string $ville = ''): bool {
        // Créer l'entreprise
        $sql = "INSERT INTO entreprise (nom_entreprise, description, email, telephone, id_secteur)
                VALUES (:nom, :description, :email, :telephone, :id_secteur)";

        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':email' => $email,
            ':telephone' => $telephone,
            ':id_secteur' => $id_secteur
        ]);

        // Récupérer l'id de l'offre créée
        $id_offre = (int)$this->pdo->lastInsertId();

        // Insérer les compétences
        if (!empty($competences)) {
        $stmt = $this->pdo->prepare("INSERT INTO offre_competence (id_offre, id_competence) VALUES (:id_offre, :id_competence)");
        foreach ($competences as $id_competence) {
                $stmt->execute([
                    ':id_offre' => $id_offre,
                    ':id_competence' => $id_competence]);
            $id_entreprise = (int)$this->pdo->lastInsertId();

            // Créer l'adresse 
            if (!empty($nom_rue) || !empty($code_postal)) {
                $sql = "INSERT INTO adresse (nom_rue, code_postal, ville)
                        VALUES (:nom_rue, :code_postal, :ville)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':nom_rue' => $nom_rue,
                    ':code_postal' => $code_postal ?: '00000',
                    ':ville' => $ville
                ]);

                $id_adresse = (int)$this->pdo->lastInsertId();

                // Lier l'adresse à l'entreprise
                $sql = "INSERT INTO entreprise_adresse (id_entreprise, id_adresse)
                        VALUES (:id_entreprise, :id_adresse)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':id_entreprise' => $id_entreprise,
                '   :id_adresse' => $id_adresse
                ]);
            }
        }

        return true;
        }

    }


    /*public function getEntrepriseById(int $id): array|null {
        $sql = "SELECT * FROM entreprise WHERE id_entreprise = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }*/     

    public function updateEntreprise(int $id, string $nom, string $description, string $email, string $telephone, ?int $id_secteur): bool {
        $sql = "UPDATE entreprise SET
                    nom_entreprise = :nom,
                    description = :description,
                    email = :email,
                    telephone = :telephone,
                    id_secteur = :id_secteur
                WHERE id_entreprise = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':description' => $description,
            ':email' => $email,
            ':telephone' => $telephone,
            ':id_secteur' => $id_secteur
        ]);
    }

    public function deleteEntreprise(int $id): bool {
        // Supprimer les adresses liées
        $stmt = $this->pdo->prepare("DELETE FROM entreprise_adresse WHERE id_entreprise = :id");
        $stmt->execute([':id' => $id]);

        // Supprimer les avis liés
        $stmt = $this->pdo->prepare("DELETE FROM avis WHERE id_entreprise = :id");
        $stmt->execute([':id' => $id]);

        // Supprimer les offres liées (et leurs dépendances)
        $offres = $this->pdo->prepare("SELECT id_offre FROM offre WHERE id_entreprise = :id");
        $offres->execute([':id' => $id]);
        foreach ($offres->fetchAll() as $offre) {
            $this->deleteOffre($offre['id_offre']);
        }

        // Supprimer l'entreprise
        $stmt = $this->pdo->prepare("DELETE FROM entreprise WHERE id_entreprise = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getWishlistOffreIdsByUserId(int $userId): array {
        $sql = "
            SELECT id_offre
            FROM wishlist
            WHERE id_utilisateur = :id_utilisateur
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_utilisateur', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return array_map('intval', $rows);
    }

    public function getAllEtudiants() : array {
        $sql = "SELECT * FROM utilisateur WHERE id_role = 0"; 
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getEtudiantsByPiloteId($role) {
        $sql = "SELECT * FROM utilisateur WHERE id_role = 0 AND referent_id = :role";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':role' => $role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    public function getAllPilote() : array {
        $sql = "SELECT * FROM utilisateur WHERE id_role = 1"; 
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
        public function getAllUsers(): array {
            $stmt = $this->pdo->query("SELECT id_utilisateur, mot_de_passe FROM utilisateur");
            return $stmt->fetchAll();
        }

        public function updatePasswordById(int $id, string $password): bool {
            $stmt = $this->pdo->prepare("UPDATE utilisateur SET mot_de_passe = :password WHERE id_utilisateur = :id");
            return $stmt->execute([':password' => $password, ':id' => $id]);
        }

        public function getAdresseByEntrepriseId(int $id): array|null {
            $sql = "SELECT a.*
                    FROM adresse a
                    JOIN entreprise_adresse ea ON a.id_adresse = ea.id_adresse
                    WHERE ea.id_entreprise = :id
                    LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();
            return $result ?: null;
        }

        public function updateEntrepriseWithAdresse(int $id, string $nom, string $description, string $email, string $telephone, ?int $id_secteur, ?int $id_adresse, string $nom_rue, string $code_postal, string $ville): bool {
            // Mettre à jour l'entreprise
            $sql = "UPDATE entreprise SET
                        nom_entreprise = :nom,
                        description = :description,
                        email = :email,
                        telephone = :telephone,
                        id_secteur = :id_secteur
                    WHERE id_entreprise = :id";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                ':id' => $id,
                ':nom' => $nom,
                ':description' => $description,
                ':email' => $email,
                ':telephone' => $telephone,
                ':id_secteur' => $id_secteur
            ]);

            if (!$result) return false;

            // Adresse existante → on la met à jour
            if ($id_adresse) {
                $sql = "UPDATE adresse SET
                            nom_rue = :nom_rue,
                            code_postal = :code_postal,
                            ville = :ville
                        WHERE id_adresse = :id_adresse";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':nom_rue' => $nom_rue,
                    ':code_postal' => $code_postal ?: '00000',
                    ':ville' => $ville,
                    ':id_adresse' => $id_adresse
                ]);

            // Pas d'adresse on en crée une si renseignée
            } elseif (!empty($nom_rue) || !empty($code_postal)) {
                $sql = "INSERT INTO adresse (nom_rue, code_postal, ville)
                        VALUES (:nom_rue, :code_postal, :ville)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':nom_rue' => $nom_rue,
                    ':code_postal' => $code_postal ?: '00000',
                    ':ville' => $ville
                ]);

                $new_id_adresse = (int)$this->pdo->lastInsertId();

                $sql = "INSERT INTO entreprise_adresse (id_entreprise, id_adresse)
                        VALUES (:id_entreprise, :id_adresse)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':id_entreprise' => $id,
                    ':id_adresse' => $new_id_adresse
                ]);
            }

            return true;
        
        }
                
}