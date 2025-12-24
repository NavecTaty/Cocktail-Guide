<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/recette.php';
require_once __DIR__ . '/aliment.php';


// Cas 1 : aucun aliment sélectionné → racines
if (!isset($_GET['id'])) {
    $alimentCourant = null;
    $chemin = [];
    $sousCategories = getRacine();
    $recettes = [];
}
// Cas 2 : un aliment est sélectionné
else {
    $id = (int) $_GET['id'];

    $alimentCourant = getAlimentById($id);

    if (!$alimentCourant) {
        die("Aliment introuvable");
    }

    $chemin = getCheminHierarchique($id);
    $sousCategories = getSousCategories($id);
    $recettes = getAllRecettesParHierarchie($id);
}
?>
<?php if (!empty($chemin)): ?>
<nav class="breadcrumb">
    <?php foreach ($chemin as $i => $a): ?>
        <a href="index.php?page=hierarchie&id=<?= $a['id_aliment'] ?>">
            <?= htmlspecialchars($a['nom']) ?>
        </a>
        <?php if ($i < count($chemin) - 1): ?>
            &gt;
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
<?php endif; ?>
<aside class="menu">
    <h3>Catégories</h3>

    <?php if (empty($sousCategories)): ?>
        <p>Aucune sous-catégorie</p>
    <?php else: ?>
        <ul>
            <?php foreach ($sousCategories as $cat): ?>
                <li>
                    <a href="index.php?page=hierarchie&id=<?= $cat['id_aliment'] ?>">
                        <?= htmlspecialchars($cat['nom']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</aside>
<section class="recettes">
    <h2>Recettes</h2>

    <?php if (empty($recettes)): ?>
        <p>Aucune recette à afficher.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($recettes as $r): ?>
                <li>
                    <a href="index.php?page=recette&id=<?= $r['id_recette'] ?>">
                        <?= htmlspecialchars($r['titre']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>


