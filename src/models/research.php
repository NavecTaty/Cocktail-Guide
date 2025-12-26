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


