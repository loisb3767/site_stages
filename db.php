<?php
$config = require 'config.php';

try {
    $dbh = new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']}",
        $config['db_user'],
        $config['db_pass']
    );
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}