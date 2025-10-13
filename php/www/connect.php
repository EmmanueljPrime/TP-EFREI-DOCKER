<?php
$host = 'db';
$dbname = getenv('MYSQL_DATABASE') ?: 'gestion_produits';
$username = getenv('MYSQL_USER') ?: 'admin';
$password = getenv('MYSQL_PASSWORD') ?: 'password_secure';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

