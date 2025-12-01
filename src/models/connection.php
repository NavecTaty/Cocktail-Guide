<?php
/**
 * Gère la connection à la base de données via PDO
 */
require_once __DIR__ . '/../config.php';

try {

    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET; //Informations pour la connexion à la bd
    //Connexion à la bd
    $access = new pdo($dsn,DB_USER,DB_PASS);
    $access->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);//Récupération et affichage des erreurs avec PDO

}catch (PDOException $e){
    die('Erreur de connexion: '.$e->getMessage());
}
