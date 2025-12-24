<?php
/**
 * Vue : hiérarchie des aliments (gauche)
 * 
 */

require_once __DIR__ . '/../models/aliment.php';
require_once __DIR__ . '/../models/recette.php'; // pour la partie droite

// Aliment sélectionné
$alimentSelectionne = null;
if (isset($_GET['aliment'])) {
    $alimentSelectionne = getAlimentByName($_GET['aliment']);
}

/**
 * Affichage récursif de la hiérarchie (dépliage progressif)
 */
function afficherHierarchie($aliment, $alimentSelectionne)
{
    $sousCategories = getSousCategories($aliment['id_aliment']);

    if (empty($sousCategories)) {
        return;
    }

    echo '<ul>';

    foreach ($sousCategories as $sous) {

        echo '<li>';
        echo '<a href="?aliment=' . urlencode($sous['nom']) . '">';
        echo htmlspecialchars($sous['nom']);
        echo '</a>';

        // Déplier uniquement le chemin sélectionné
        if (
            $alimentSelectionne &&
            $sous['id_aliment'] == $alimentSelectionne['id_aliment']
        ) {
            afficherHierarchie($sous, $alimentSelectionne);
        }

        echo '</li>';
    }

    echo '</ul>';
}
?>

<div class="page">

    <!-- COLONNE GAUCHE : HIÉRARCHIE -->
    <aside class="hierarchie">
        <h3>Aliments</h3>

        <?php
        $aliments = getAllAliments();

        foreach ($aliments as $aliment) {

            // racines = pas de super-catégorie
            if (!empty(getSuperCategories($aliment['id_aliment']))) {
                continue;
            }

            echo '<div>';
            echo '<a href="?aliment=' . urlencode($aliment['nom']) . '">';
            echo htmlspecialchars($aliment['nom']);
            echo '</a>';

            // Déplier seulement si racine sélectionnée
            if (
                $alimentSelectionne &&
                $alimentSelectionne['id_aliment'] == $aliment['id_aliment']
            ) {
                afficherHierarchie($aliment, $alimentSelectionne);
            }

            echo '</div>';
        }
        ?>
    </aside>

    <!-- COLONNE DROITE : RECETTES -->
    <main class="contenu">
        <?php if ($alimentSelectionne): ?>
            <h2><?= htmlspecialchars($alimentSelectionne['nom']) ?></h2>

            <?php
            $recettes = getRecettesByAliment($alimentSelectionne['nom']);
            ?>

            <?php if (empty($recettes)): ?>
                <p>Aucune recette pour cet aliment.</p>
            <?php else: ?>
                <?php foreach ($recettes as $recette): ?>
                    <article>
                        <h3><?= htmlspecialchars($recette['titre']) ?></h3>

                        <?php if (!empty($recette['photo'])): ?>
                            <img src="<?= htmlspecialchars($recette['photo']) ?>" alt="">
                        <?php endif; ?>

                        <p><?= nl2br(htmlspecialchars($recette['preparation'])) ?></p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php else: ?>
            <h2>Sélectionnez un aliment</h2>
        <?php endif; ?>
    </main>

</div>





