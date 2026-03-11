<?php
namespace App\Models;

class TaskModel extends Model {
    private $offres;

    public function __construct() {
        include 'offres_data.php'; 
        $this->offres = $offres; 
    }

    public function getAllOffres() {
        return $this->offres;
    }

    public function getOffreById($id) {
        foreach ($this->offres as $offre) {
            if ($offre['id'] == $id) {
                return $offre;
            }
        }
        return null;
    }

    public function getPaginatedOffres($page, $parPage) {
        $indexDepart = ($page - 1) * $parPage;
        return array_slice($this->offres, $indexDepart, $parPage);
    }

    public function getTotalCount() {
        return count($this->offres);
    }
}