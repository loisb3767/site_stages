<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
use App\Models\TaskModel;

$model = new TaskModel();
$users = $model->getAllUsers();

foreach ($users as $user) {
    if (!password_get_info($user['mot_de_passe'])['algo']) {
        $hashed = password_hash($user['mot_de_passe'], PASSWORD_DEFAULT);
        $model->updatePasswordById($user['id_utilisateur'], $hashed);
    }
}
echo "Migration terminée !";