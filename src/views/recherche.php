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

    //la ligne que j'ai rajouté
    $recettes = rechercherRecettesAvecScore( $includeAliments, $excludeAliments );
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

    <p class="info-recherche">
        Les recettes sont classées selon leur degré de correspondance avec votre recherche.
    </p>

    <?php if (empty($recettes)): ?>
        <p class="aucune-recette">
            Aucune recette ne correspond à votre recherche.
        </p>
    <?php else: ?>

        <div class="recettes-grille">

            <?php foreach ($recettes as $r): ?>
                <?php
                    $image = getRecettePhoto($r['titre']);
                    $apercu = mb_substr($r['preparation'], 0, 130);
                    $score  = $r['score'] ?? 0;
                ?>

                <div class="recette-card <?= $score < 100 ? 'approx' : 'exact' ?>">

                    <!-- Badge score -->
                    <div class="score-badge">
                        <?= $score ?>%
                    </div>

                    <a href="index.php?page=recette&id=<?= $r['id_recette'] ?>" class="recette-link">
                        <img src="<?= htmlspecialchars($image) ?>"
                             alt="Photo <?= htmlspecialchars($r['titre']) ?>"
                             onerror="this.onerror=null;this.src='/Cocktail-Guide/src/Ressources/Photos/defaut.jpg';">

                        <h4><?= htmlspecialchars($r['titre']) ?></h4>
                    </a>

                    <p><?= htmlspecialchars($apercu) ?>...</p>

                    <?php if ($score < 100): ?>
                        <span class="approx-label">
                            Correspondance partielle
                        </span>
                    <?php else: ?>
                        <span class="exact-label">
                            Correspondance exacte
                        </span>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>


