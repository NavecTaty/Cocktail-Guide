<?php
require_once __DIR__ . '/../models/aliment.php';
require_once __DIR__ . '/../models/recette.php';
require_once __DIR__ . '/../models/recettesFavorites.php';

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
   <?php $menuHierarchie = [];
        if ($alimentCourant) {
        // Parents + courant
         $menuHierarchie = getCheminHierarchique($alimentCourant['id_aliment']);
        }
        // Sous-catégories
        $menuEnfants = $sousCategories;
    ?>
    <aside class="menu" style="width:260px;">
        <h3>
            <?= $alimentCourant ? "Sous-catégories de " . htmlspecialchars($alimentCourant['nom']) : "Catégories" ?>
        </h3>

        <?php if (empty($sousCategories)): ?>
            <p>Aucune sous-catégorie.</p>
        <?php else: ?>
         <ul class="menu-hierarchie">
                <?php foreach ($menuHierarchie as $niveau => $a): ?>
                 <li class="niveau-<?= $niveau ?> actif">
                    <a href="index.php?page=hierarchie&id=<?= $a['id_aliment'] ?>">
                         <?= htmlspecialchars($a['nom']) ?>
                    </a>
                 </li>
                <?php endforeach; ?>

                 <?php foreach ($menuEnfants as $enfant): ?>
                  <li class="niveau-<?= count($menuHierarchie) ?>">
                    <a href="index.php?page=hierarchie&id=<?= $enfant['id_aliment'] ?>">
                         <?= htmlspecialchars($enfant['nom']) ?>
                     </a>
                 </li>
                 <?php endforeach; ?>
        </ul>

        <?php endif; ?>
    </aside>

    <main class="recettes" style="flex:1;">
        <h2>Recettes <?= $alimentCourant ? "avec " . htmlspecialchars($alimentCourant['nom']) : "" ?></h2>

        <?php if (empty($recettes)): ?>
            <p class="aucune-recette">Aucune recette à afficher.</p>
        <?php else: ?>
        <div class="recettes-grille">
             <?php foreach ($recettes as $r): ?>
             <?php
                 $image = getRecettePhoto($r['titre']);
                 $apercu = mb_substr($r['preparation'], 0, 120);
                 $estFavori = estFavoriGlobal($r['id_recette']);
             ?>

            <div class="recette-card">
                <!---- icone pour marquer comme favori  ---->
                <form method="post" action="index.php?page=favoris_action" class="favori-form">
                        <input type="hidden" name="id_recette" value="<?= $r['id_recette'] ?>">
                         <input type="hidden" name="action" value="<?= $estFavori ? 'supprimer' : 'ajouter' ?>">
                         <button type="submit"
                             class="favori-btn <?= $estFavori ? 'actif' : 'inactif' ?>"
                            aria-label="<?= $estFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                            <?= $estFavori ? '★' : '☆' ?>
                        </button>
                </form>

                <img src="<?= $image ?>"
                     alt="Photo <?= htmlspecialchars($r['titre']) ?>"
                     onerror="this.onerror=null;this.src='/Cocktail-Guide/src/Ressources/Photos/defaut.jpg';">

                <h4><?= htmlspecialchars($r['titre']) ?></h4>

                <p><?= htmlspecialchars($apercu) ?>...</p>
            
        </div>
    <?php endforeach; ?>
</div>


        <?php endif; ?>
    </main>

</div>
