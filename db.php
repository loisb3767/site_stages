<?php
$config = require 'config.php';

try {
    $dbh = new PDO(
        'mysql:host=localhost;dbname=PROJET_WEB',
        $config['db_user'],
        $config['db_pass']
    );
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}