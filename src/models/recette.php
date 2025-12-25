<?php
/**
 * Gère les accès à la base de données pour toutes les recettes
 */
require_once __DIR__ . '/connection.php';

/**
 * Récupère toutes les recettes de base de donnée
 */
function getAllRecettes(){
    global $access;
    //Préparation de la requete 
    $recetteSQL = ("SELECT * 
                    from recette");
    $stmtRecette = $access->prepare($recetteSQL);
    //Exécution 
    $stmtRecette->execute();
    return $stmtRecette->fetchAll(PDO::FETCH_ASSOC);
   
}
/**
 * Récupère une recette ayant l'identifiant id
 */
function getRecetteById($id){
     global $access;
//Préparation de la requete 
    $recetteSQL = ("SELECT * 
                    from recette
                    where id_recette = ?");
    $stmtRecette = $access->prepare($recetteSQL);
    //Exécution 
    $stmtRecette->execute([$id]);
    return $stmtRecette->fetch(PDO::FETCH_ASSOC);
}
/**
 * Récupère toutes les recettes contenant l'aliment aliment
 */
function getRecettesByAliment($aliment){
     global $access;
//Préparation de la requete 
    $recetteSQL = ( "SELECT *
                    FROM recette r JOIN index_recette i ON r.id_recette = i.id_recette
                     WHERE i.aliment = ?");
    $stmtRecette = $access->prepare($recetteSQL);
    //Exécution 
    $stmtRecette->execute([$aliment]);
    return $stmtRecette->fetchAll(PDO::FETCH_ASSOC);
}
/**
 * Récupère la photo de la recette correspondante à titre
 */
function getRecettePhoto($titre) {
    // Formatage du nom de la photo
    $nom = strtolower($titre);
    $nom = ucfirst($nom);
    $nom = str_replace(" ", "_", $nom);
    $nom = iconv("UTF-8", "ASCII//TRANSLIT", $nom);
    $nom .= ".jpg";

    //On obtient le chemin de la photo si il existe
    $chemin = __DIR__ . '/../Ressources/Photos/' . $nom;
    if(!file_exists($chemin)){
        return "Ressources/Photos/defaut";
    }
    return "Ressources/Photos/" . $nom ;
}