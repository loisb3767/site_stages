<?php
// 1. Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $errors = [];
    $uploadDir = 'cv/'; // Ton dossier déjà créé
    
    // 2. Traitement du texte (Lettre de motivation)
    $motivation = isset($_POST['motivation']) ? htmlspecialchars(trim($_POST['motivation'])) : '';

    // 3. Vérification du fichier CV
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['cv'];

        // Vérification des erreurs de transfert
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors du transfert du CV.";
        } else {
            // Vérification du type (PDF)
            if ($file['type'] !== 'application/pdf') {
                $errors[] = "Le CV doit être au format PDF.";
            }

            // Vérification de la taille (ex: 2 Mo)
            if ($file['size'] > 2097152) {
                $errors[] = "Le CV est trop lourd (maximum 2 Mo).";
            }
        }

        // 4. Enregistrement si pas d'erreurs
        if (empty($errors)) {
            // Nettoyage du nom de fichier pour éviter les caractères bizarres
            $fileName = time() . '_' . basename($file['name']);
            $destination = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                echo "<h2>Candidature envoyée avec succès !</h2>";
                echo "<p>Merci, votre CV a bien été enregistré dans le dossier <strong>cv/</strong>.</p>";
                // Ici tu pourrais enregistrer $motivation et $fileName dans une base de données
            } else {
                echo "Erreur lors de l'enregistrement du fichier.";
            }
        } else {
            // Affichage des erreurs
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
