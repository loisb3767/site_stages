<?php
$config = require 'config.php';

try {
    $dbh = new PDO(
        'mysql:host=localhost;dbname=job2main',
        $config['db_user'],
        $config['db_pass']
    );
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}