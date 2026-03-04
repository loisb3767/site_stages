<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $errors = [];
    $uploadDir = 'cv/';
    

    $motivation = isset($_POST['motivation']) ? htmlspecialchars(trim($_POST['motivation'])) : '';


    if (isset($_FILES['cv']) && $_FILES['cv']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['cv'];


        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors du transfert du CV.";
        } else {

            if ($file['type'] !== 'application/pdf') {
                $errors[] = "Le CV doit être au format PDF.";
            }

            if ($file['size'] > 2097152) {
                $errors[] = "Le CV est trop lourd (maximum 2 Mo).";
            }
        }

        if (empty($errors)) {
            $fileName = time() . '_' . basename($file['name']);
            $destination = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                echo "<h2>Candidature envoyée avec succès !</h2>";
                echo "<p>Merci, votre CV a bien été enregistré dans le dossier <strong>cv/</strong>.</p>";
            } else {
                echo "Erreur lors de l'enregistrement du fichier.";
            }
        } else {
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
            echo "<a href='javascript:history.back()'>Retour au formulaire</a>";
        }
    } else {
        echo "Veuillez sélectionner un CV.";
    }
}
?>
