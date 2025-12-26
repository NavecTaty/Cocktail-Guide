<?php
/**
 * PAGE DE RÉSULTATS DE RECHERCHE
 */

require_once __DIR__ . '/../models/recette.php';
require_once __DIR__ . '/../models/aliment.php';
require_once __DIR__ . '/../models/research.php';

// ===============================
// Récupération des paramètres
// ===============================

$include = $_GET['include'] ?? '';
$exclude = $_GET['exclude'] ?? '';

// Transformation en tableaux propres
$includeAliments = array_filter(array_map('trim', explode(',', $include)));
$excludeAliments = array_filter(array_map('trim', explode(',', $exclude)));

$titrePage = "Résultats de recherche";
$recettes = [];

// ===============================
// Recherche combinée
// ===============================

if (!empty($includeAliments) || !empty($excludeAliments)) {

    //fonction combinée du modèle
    $recettes = rechercherRecettesCombinees(
        $includeAliments,
        $excludeAliments
    );

    // Construction du titre
    if (!empty($includeAliments)) {
        $titrePage .= " avec " . htmlspecialchars(implode(', ', $includeAliments));
    }

    if (!empty($excludeAliments)) {
        $titrePage .= " sans " . htmlspecialchars(implode(', ', $excludeAliments));
    }
}
?>


<main class="recettes" style="margin-top:30px;">

    <h2><?= $titrePage ?></h2>

    <?php if (empty($recettes)): ?>
        <p class="aucune-recette">Aucune recette ne correspond à votre recherche.</p>
    <?php else: ?>

        <div class="recettes-grille">

            <?php foreach ($recettes as $r): ?>
                <?php
                    $image = getRecettePhoto($r['titre']);
                    $apercu = mb_substr($r['preparation'], 0, 130);
                ?>

                <div class="recette-card">

                    <img src="<?= htmlspecialchars($image) ?>"
                         alt="Photo <?= htmlspecialchars($r['titre']) ?>"
                         onerror="this.onerror=null;this.src='/Cocktail-Guide/src/Ressources/Photos/defaut.jpg';">

                    <h4><?= htmlspecialchars($r['titre']) ?></h4>

                    <p><?= htmlspecialchars($apercu) ?>...</p>

                </div>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>

