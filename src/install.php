<?php
/**
 * Importe les données contenues dans Donnees.inc.php pour poupler la base de données
 */

require_once __DIR__ . '/models/connection.php';
require_once __DIR__ . '/Donnees.inc.php';

//Import des recettes et de l'index des aliments
if(!isset($access)){
    die("status: Erreur de connexion à la base de donnée");
}

if(!isset($Recettes)){
    die("status: Erreur, tableau Recette n'est pas défini.");
}
echo " Connexion OK\n";
try {
    //Nettoyage de la base
    $access->exec("SET FOREIGN_KEY_CHECKS = 0;"); //On désactive les clés étrangères

    $access->exec("TRUNCATE TABLE hierarchie;");
    $access->exec("TRUNCATE TABLE index_recette;");
    $access->exec("TRUNCATE TABLE recette;");
    $access->exec("TRUNCATE TABLE aliments;");

    $access->exec("SET FOREIGN_KEY_CHECKS = 1;");//On réactive

    echo "Base réinitialisée.\n";
}catch (PDOException $e){
    die("Erreur lors du nettoyage : " . $e->getMessage());

}
//Préparation des  requetes
try{
    $recetteSQL = ("INSERT INTO recette(id_recette, titre, ingredients, preparation)
                 VALUES (?,?,?,?)");
    $indexSQL = ("INSERT INTO index_recette(id_recette,aliment)
                VALUES (?,?)");

    $stmtRecette = $access->prepare($recetteSQL);
    $stmtIndex = $access->prepare($indexSQL);

    foreach ($Recettes as $id => $recette){
        $stmtRecette->execute([$id,$recette['titre'],$recette['ingredients'],
        $recette['preparation']] //Exécution de la requette
    );
        //Insertion de l'index des aliments
         foreach ($recette['index'] as $aliment){
             $stmtIndex->execute([$id,$aliment]); //Exécution de la requete
         }
    }
    echo " Recettes insérées : " . count($Recettes) . "\n";
}catch (PDOException $e){
    echo $e->getMessage();
}

//Import de la hierarchie des alilemnts
if(!isset($Hierarchie)){
    die("status: Erreur, tableau Hierarchie n'est pas défini.");
}
try{
    //import des aliments
    $tousLesAliments = array_keys($Hierarchie); //liste de tous les aliments
    $alimentSQL = ("INSERT IGNORE INTO aliments(nom)
                    VALUES (?)");
    $stmtAliment = $access->prepare($alimentSQL);//préparation de la requete

    foreach ($tousLesAliments as $aliment){
        $stmtAliment->execute([$aliment]);//exécution de la requete
    }
    echo " Aliments insérés : " . count($tousLesAliments) . "\n";

    //import des relations
    $relationSQL =("INSERT IGNORE INTO hierarchie(id_super_categorie, id_sous_categorie)
                    SELECT a1.id_aliment, a2.id_aliment
                    FROM aliments a1, aliments a2
                    WHERE a1.nom = ? AND a2.nom = ?");
    $stmtRelation = $access->prepare($relationSQL);//preparation de la requete

    foreach ($Hierarchie as $aliment => $relation){

        //Relation Super-categorie - Aliment
        if(isset($relation['super-categorie']) && is_array($relation['super-categorie'])){
            foreach ($relation['super-categorie'] as $parent){
                $stmtRelation->execute([$parent,$aliment]);//exécution de la requete
            }
        }
        //Relation Aliment - Sous-categorie
        if(isset($relation['sous-categorie']) && is_array($relation['sous-categorie'])){
            foreach ($relation['sous-categorie'] as $enfant){
                $stmtRelation->execute([$aliment,$enfant]);//exécution de la requete
            }
        }

    }
}catch (PDOException $e){
    echo $e->getMessage();
}