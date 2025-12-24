<?php
require_once __DIR__ . '/../models/aliment.php';
require_once __DIR__ . '/../models/recette.php';


if (!isset($_GET['id'])) {
    $alimentCourant = null;
    $chemin = [];
    $sousCategories = getRacine();
    $recettes = [];
} else {
    $id = (int) $_GET['id'];
    $alimentCourant = getAlimentById($id);

    if (!$alimentCourant) {
        die("Aliment introuvable");
    }

    $chemin = getCheminHierarchique($id);
    $sousCategories = getSousCategories($id);
    $recettes = getAllRecettesParHierarchie($id);
}

include __DIR__ . '/../include/header.php';
?>

<?php if (!empty($chemin)): ?>
<nav class="breadcrumb">
    <?php foreach ($chemin as $i => $a): ?>
        <a href="index.php?page=hierarchie&id=<?= $a['id_aliment'] ?>">
            <?= htmlspecialchars($a['nom']) ?>
        </a>
        <?php if ($i < count($chemin) - 1): ?> &gt; <?php endif; ?>
    <?php endforeach; ?>
</nav>
<?php endif; ?>

<div class="page-hierarchie" style="display:flex; gap:20px; margin-top:15px;">

    <aside class="menu" style="width:260px;">
        <h3>
            <?= $alimentCourant ? "Sous-catégories de " . htmlspecialchars($alimentCourant['nom']) : "Catégories" ?>
        </h3>

        <?php if (empty($sousCategories)): ?>
            <p>Aucune sous-catégorie.</p>
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

    <main class="recettes" style="flex:1;">
        <h2>Recettes <?= $alimentCourant ? "avec " . htmlspecialchars($alimentCourant['nom']) : "" ?></h2>

        <?php if (empty($recettes)): ?>
            <p>Aucune recette à afficher.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($recettes as $r): ?>
                    <li>
                        <a href="index.php?page=recettes&id=<?= $r['id_recette'] ?>">
                            <?= htmlspecialchars($r['titre']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>

</div>

<?php include __DIR__ . '/../include/footer.php'; ?>
