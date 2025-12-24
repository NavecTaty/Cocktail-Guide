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
/**
 * Récupère la hierarchie d'un aliment
 */
function getCheminHierarchique($id_Aliment) {
    $chemin = [];

    $courant = getAlimentById($id_Aliment);

    while ($courant) {
        array_unshift($chemin, $courant);

        $parents = getSuperCategories($courant['id_aliment']);
        if (empty($parents)) {
            break;
        }
        // On prend le premier parent
        $courant = $parents[0];
    }

    return $chemin;
}
/**
 * Récupère toutes les sous-catégories 
 */
function getToutesLesSousCategories($idAliment) {
    $resultat = [];
    $sous = getSousCategories($idAliment);

    foreach ($sous as $a) {
        $resultat[] = $a['nom'];
        $resultat = array_merge(
            $resultat,
            getToutesLesSousCategories($a['id_aliment'])
        );
    }

    return $resultat;
}
/**
 * Récupère toutes les recettes pour un aliment et ses descendants
 */
function getAllRecettesParHierarchie($id_Aliment) {
    $aliment = getAlimentById($id_Aliment);
    if (!$aliment) return [];

    // aliment courant + descendants
    $noms = [$aliment['nom']];
    $noms = array_merge($noms, getToutesLesSousCategories($id_Aliment));

    $recettes = [];
    foreach ($noms as $nom) {
        foreach (getRecettesByAliment($nom) as $r) {
            $recettes[$r['id_recette']] = $r; // éviter doublons
        }
    }

    return array_values($recettes);
}
/**
 * Récupère la racine de la hierarchie
 */
function getRacine() {
    global $access;

    $alimentSQL = (" SELECT *
                FROM aliments
                 WHERE id_aliment NOT IN (
                SELECT id_sous_categorie FROM hierarchie
                 )
                ORDER BY nom
    ");

    $stmtAliment = $access->prepare($alimentSQL);
    $stmtAliment->execute();
    return $stmtAliment->fetchAll(PDO::FETCH_ASSOC);
}
