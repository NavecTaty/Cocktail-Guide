<?php
/**
 * Gère les recettes favorites pour un utilisateur
 */
require_once __DIR__ . '/connection.php';

/**
 * ------------------Pour un utilisateur connecté
 */


/**
 * Ajoute une recette favorite pour utilisateur donné
 */
function ajouterFavori($id_utilisateur, $id_recette){
    global $access;

    $favoriSQL = ("INSERT IGNORE INTO favoris 
                (id_utilisateur, id_recette)
                 VALUES (?, ?)");
    $stmtFavori = $access->prepare($favoriSQL);
    return $stmtFavori->execute([$id_utilisateur,$id_recette]);

}

/**
 * Supprime une recette favorite pour utilisateur donné
 */
function supprimerFavori($id_utilisateur, $id_recette){
    global $access;

    $favoriSQL = ("DELETE 
                from  favoris 
                where id_utilisateur = ? and id_recette = ?");
    $stmtFavori = $access->prepare($favoriSQL);
    return $stmtFavori->execute([$id_utilisateur,$id_recette]);

}

/**
 * Récupère les favoris d’un utilisateur donné
 */
function getFavorisUtilisateur($id_Utilisateur) {
    global $access;

    $favoriSQL = ("SELECT*
            FROM recette r
            JOIN favoris f ON r.id_recette = f.id_recette
            WHERE f.id_utilisateur = ?");
    $stmtFavori = $access->prepare($favoriSQL);
    $stmtFavori->execute([$id_Utilisateur]);

    return $stmtFavori->fetchAll(PDO::FETCH_ASSOC);
}
/**
 * Vérifie si une recette est déjà en favori
 */
function estFavori($id_Utilisateur, $id_Recette) {
    global $access;

    $favoriSQL = "SELECT 1 FROM favoris
            WHERE id_utilisateur = ? AND id_recette = ?";
    $stmtFavori = $access->prepare($favoriSQL);
    $stmtFavori->execute([$id_Utilisateur, $id_Recette]);

    return $stmtFavori->fetch() !== false;
}


/**
 * ---------------------pour un utilisateur non connecté
 */

/**
 * Ajoute un favori temporaire
 */
function ajouterFavoriTemporaire($id_recette){
    //mise en place du tableau des favoris
    if (!isset($_SESSION['favoris_temp'])) {
        $_SESSION['favoris_temp'] = [];
    }

    //On stocke la recette 
    if (!in_array($id_recette, $_SESSION['favoris_temp'])) {
        $_SESSION['favoris_temp'][] = $id_recette;
    }
}

/**
 * Supprime un favori temporaire
 */
function supprimerFavoriTemporaire($id_recette){
    if (!isset($_SESSION['favoris_temp'])) {
        null;
    }
    //On retire la recette 
        $_SESSION['favoris_temp']= array_diff(
             $_SESSION['favoris_temp'],[$id_recette]
        );
    
}

/**
 * Vérifie si une recette est déjà en favori que l'utilisateur soit connecté ou pas
 */
function estFavoriGlobal($idRecette) {

    // utilisateur connecté
    if (isset($_SESSION['user'])) {
        return estFavori($_SESSION['user']['id'], $idRecette);
    }

    // utilisateur non connecté
    if (!isset($_SESSION['favoris_temp'])) {
        return false;
    }

    return in_array($idRecette, $_SESSION['favoris_temp'], true);
}
