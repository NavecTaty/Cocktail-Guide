<?php
/**
 * Gère les accès à la base de données pour toutes les aliments
 */
require_once __DIR__ . '/connection.php';

/**
 * Récupère toutes les aliments de base de donnée
 */
function getAllAliments(){
    global $access;
    //Préparation de la requete 
    $alimentSQL = ("SELECT * 
                    from aliments");
    $stmtAliment = $access->prepare($alimentSQL);
    //Exécution 
    $stmtAliment->execute();
    return $stmtAliment->fetchAll(PDO::FETCH_ASSOC);
   
}
/**
 * Récupère un aliment par son id
 */
function getAlimentById($id){
     global $access;
//Préparation de la requete 
    $alimentSQL = ("SELECT * 
                    from aliments
                    where id_aliment = ?");
    $stmtAliment = $access->prepare($alimentSQL);
    //Exécution 
    $stmtAliment->execute([$id]);
    return $stmtAliment->fetch(PDO::FETCH_ASSOC);
}
/**
 * Récupère un aliment par son nom
 */
function getAlimentByName($nom){
     global $access;
//Préparation de la requete 
    $alimentSQL = ( "SELECT *
                    from aliments
                     where nom = ?");
    $stmtAliment = $access->prepare($alimentSQL);
    //Exécution 
    $stmtAliment->execute([$nom]);
    return $stmtAliment->fetch(PDO::FETCH_ASSOC);
}
/**
 * Récupère les sous-catégories d'un aliment
 */
function getSousCategories($id_aliment){
         global $access;
//Préparation de la requete
    $alimentSQL = ("SELECT *
                    from hierarchie h
                  join aliments a on h.id_sous_categorie = a.id_aliment
                 where h.id_super_categorie = ?
                 ORDER BY a.nom
    ");
     $stmtAliment = $access->prepare($alimentSQL);
    //Exécution 
    $stmtAliment->execute([$id_aliment]);
    return $stmtAliment->fetchAll(PDO::FETCH_ASSOC);
   
}

/**
 * Récupère les super-catégories d'un aliment
 */
function getSuperCategories($id_aliment){
            global $access;
//Préparation de la requete
    $alimentSQL = ("SELECT *
                    from hierarchie h
                  join aliments a on h.id_super_categorie = a.id_aliment
                 where h.id_sous_categorie = ?
                 ORDER BY a.nom
    ");
     $stmtAliment = $access->prepare($alimentSQL);
    //Exécution 
    $stmtAliment->execute([$id_aliment]);
    return $stmtAliment->fetchAll(PDO::FETCH_ASSOC);
   
}
