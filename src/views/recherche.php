<?php
/**
 * Recherche.php
 * PAGE DE RECHERCHE DE RECETTES
 */
require_once __DIR__ . '/../models/recette.php';
require_once __DIR__ . '/../models/aliment.php';
require_once __DIR__ . '/../models/research.php';

// Récupération des paramètres
$include = $_GET['include'] ?? null;
$exclude = $_GET['exclude'] ?? null;

$recettes = [];
$titrePage = "Résultats de recherche";

// Recherche inclusive
if (!empty($include)) {
    $aliments = array_map('trim', explode(',', $include));
    $recettes = getRecettesInclusivesParNom($aliments);
    $titrePage = "Recettes contenant : " . htmlspecialchars($include);
}


// Recherche exclusive
elseif (!empty($exclude)) {
    $aliments = array_map('trim', explode(',', $exclude));
    $recettes = getRecettesExclusivesParNom($aliments);
    $titrePage = "Recettes sans : " . htmlspecialchars($exclude);
}
?>
<main class="recettes" style="margin-top:20px;">

    <h2><?= $titrePage ?></h2>

    <?php if (empty($recettes)): ?>
        <p>Aucune recette trouvée.</p>
    <?php else: ?>

        <div class="recettes-grille">

            <?php foreach ($recettes as $r): ?>
                <?php
                    $image = getRecettePhoto($r['titre']);
                    $apercu = mb_substr($r['preparation'], 0, 120);
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
