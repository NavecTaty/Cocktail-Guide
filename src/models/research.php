<?php
/**
 * fonctions pour la recherche des boissons
 */


require_once __DIR__ . '/connection.php';

/**
 * Recherche inclusive par noms d'aliments
 * @param array $nomsAliments
 * @return array
 */
function getRecettesInclusivesParNom(array $nomsAliments) {
    $recettes = [];

    foreach ($nomsAliments as $nom) {
        $aliment = getAlimentByName($nom);
        if (!$aliment) continue;

        // recettes de l'aliment + descendants
        $liste = getAllRecettesParHierarchie($aliment['id_aliment']);

        foreach ($liste as $r) {
            $recettes[$r['id_recette']] = $r; // éviter doublons
        }
    }

    return array_values($recettes);
}

/**
 * Recherche exclusive par noms d'aliments
 * @param array $nomsAliments
 * @return array
 */
function getRecettesExclusivesParNom(array $nomsAliments) {

    // 1. Construire la liste des aliments interdits (avec descendants)
    $alimentsInterdits = [];

    foreach ($nomsAliments as $nom) {
        $aliment = getAlimentByName($nom);
        if (!$aliment) continue;

        $alimentsInterdits[] = $aliment['nom'];
        $alimentsInterdits = array_merge(
            $alimentsInterdits,
            getToutesLesSousCategories($aliment['id_aliment'])
        );
    }

    $alimentsInterdits = array_unique($alimentsInterdits);

    // 2. Récupérer toutes les recettes
    $toutesLesRecettes = getAllRecettes();

    // 3. Filtrer (exclusion)
    $resultat = [];

    foreach ($toutesLesRecettes as $recette) {
        $ingredients = getAlimentsByRecette($recette['id_recette']);

        $aExclure = false;
        foreach ($ingredients as $ing) {
            if (in_array($ing['nom'], $alimentsInterdits)) {
                $aExclure = true;
                break;
            }
        }

        if (!$aExclure) {
            $resultat[] = $recette;
        }
    }

    return $resultat;
}
/**
 * Retourn le nomdes aliments qui commencent par une suite de lettre
 * @param  string
 * @return array 
 */
function rechercherAlimentsParNom(string $term): array {
    global $access;

    $sql = "SELECT nom
            FROM aliments
            WHERE nom LIKE ?
            ORDER BY nom
            LIMIT 10";

    $stmt = $access->prepare($sql);
    $stmt->execute([$term . '%']);

    return $stmt->fetchAll();
}


/**
 * Recherche combinée : inclusive + exclusive
 *
 * @param array $include
 * @param array $exclude
 * @return array
 */
function rechercherRecettesCombinees(array $include, array $exclude): array
{
    //  Recherche inclusive
    $recettes = [];

    if (!empty($include)) {
        $recettes = getRecettesInclusivesParNom($include);
    } else {
        // Si pas d'inclusion → toutes les recettes
        $recettes = getAllRecettes();
    }

    //  Si pas d'exclusion → terminé
    if (empty($exclude)) {
        return $recettes;
    }

    // Construction des aliments interdits (avec hiérarchie)
    $alimentsInterdits = [];

    foreach ($exclude as $nom) {
        $aliment = getAlimentByName($nom);
        if (!$aliment) continue;

        $alimentsInterdits[] = $aliment['nom'];
        $alimentsInterdits = array_merge(
            $alimentsInterdits,
            getToutesLesSousCategories($aliment['id_aliment'])
        );
    }

    $alimentsInterdits = array_unique($alimentsInterdits);

    //  Filtrage des recettes
    $resultat = [];

    foreach ($recettes as $recette) {
        $ingredients = getAlimentsByRecette($recette['id_recette']);

        $aExclure = false;
        foreach ($ingredients as $ing) {
            if (in_array($ing['nom'], $alimentsInterdits)) {
                $aExclure = true;
                break;
            }
        }

        if (!$aExclure) {
            $resultat[] = $recette;
        }
    }

    return $resultat;
}


/**
 * Calcul de score de satisfaction d'une recette 
 * score = inclusion - pénalité_exclusion + bonus
 * @param int 
 * @param array 
 * @param array 
 * @return int
 */
function calculerScoreRecette( int $idRecette,array $include,array $exclude): int
 {

    // Récupération des ingrédients de la recette
    $ingredients = getAlimentsByRecette($idRecette);

    if (empty($ingredients)) {
        return 0;
    }

    // Normalisation
    $nomsIngredients = array_map(
        fn($i) => strtolower($i['nom']),
        $ingredients
    );

    $include = array_map('strtolower', $include);
    $exclude = array_map('strtolower', $exclude);

    //calcul score inclusion
    $communsInclus = array_intersect($nomsIngredients, $include);

    if (count($include) > 0) {
        $score = (count($communsInclus) / count($include)) * 100;
    } else {
        // Pas de contrainte = score neutre
        $score = 50;
    }
//cal de pénalité
    $communsExclus = array_intersect($nomsIngredients, $exclude);

    if (!empty($communsExclus)) {
        $score -= count($communsExclus) * 30;
    }
   //bonus
    if (!empty($include) && count($communsInclus) === count($include)) {
        $score += 20;
    }

   //Normaliser le score
    $score = max(0, min(100, round($score)));
    return $score;
}

/**
 * Associe le score à une recette et fait un tri décroissant parmi les recette
 * 
 */
function rechercherRecettesAvecScore(array $include, array $exclude): array
{
    // On récupère les resultat de la recherche
    $recettes = rechercherRecettesCombinees($include, $exclude);

    if (empty($recettes)) {
        return [];
    }

    // on calcul le  score pour CHAQUE recette
    foreach ($recettes as &$recette) {
        $recette['score'] = calculerScoreRecette(
            $recette['id_recette'],
            $include,
            $exclude
        );
    }
    unset($recette);

    // Tri par score décroissant
    usort($recettes, function ($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    return $recettes;
}
