<?php
/**
 * Gère les recettes favorites d'un utilisateur
 */
require_once __DIR__ . '/../models/recettesFavorites.php';
require_once __DIR__ . '/../models/recette.php';

//utilisateur non connécté
if (!isset($_SESSION['user'])) {?>

    <h2 class="favoriTitre">Mes recettes favorites</h2>
    <?php if (!empty($_SESSION['favoris_temp'])) :?>
            <div class="recettes-grille">
                    <?php foreach ( $_SESSION['favoris_temp'] as $idRecette): ?>
                    <?php
                        $r = getRecetteById($idRecette);
                        $image = getRecettePhoto($r['titre']);
                        $apercu = mb_substr($r['preparation'], 0, 120);
                        $estFavori = true;
                    ?>

                    <div class="recette-card">
                        <!---- icone pour retirer comme favori  ---->
                        <form method="post" action="index.php?page=favoris_action" class="favori-form">
                            <input type="hidden" name="id_recette" value="<?= $r['id_recette'] ?>">
                            <input type="hidden" name="action" value="supprimer">
                            <button type="submit" class="favori-btn actif">
                            ★
                            </button>
                        </form>
                        <a href="index.php?page=recette&id=<?= $r['id_recette'] ?>" class="recette-link">
                                <img src="<?= $image ?>"
                                        alt="Photo <?= htmlspecialchars($r['titre']) ?>"
                                onerror="this.onerror=null;this.src='/Cocktail-Guide/src/Ressources/Photos/defaut.jpg';">

                                <h4><?= htmlspecialchars($r['titre']) ?></h4>
                        </a>
                        <p><?= htmlspecialchars($apercu) ?>...</p>
            
                    </div>
                     <?php endforeach; ?>
                </div>


        <?php else:?>
                <p class="aucune-recette">Aucune recette favorite.</p>
      <?php 
        endif;
    
    return;
}

//Utilisateur connecté
$favoris = getFavorisUtilisateur($_SESSION['user']['id']);
?>

<!------ Affichage des favoris ------>
<h2 class="favoriTitre">Mes recettes favorites</h2>

<?php if (empty($favoris)): ?>
    <p class="aucune-recette">Aucune recette favorite.</p>
<?php else: ?>
     <div class="recettes-grille">
             <?php foreach ($favoris as $r): ?>
             <?php
                 $image = getRecettePhoto($r['titre']);
                 $apercu = mb_substr($r['preparation'], 0, 120);
                 $estFavori = true;
             ?>

            <div class="recette-card">
                <!---- icone pour retirer comme favori  ---->
                <form method="post" action="index.php?page=favoris_action" class="favori-form">
                <input type="hidden" name="id_recette" value="<?= $r['id_recette'] ?>">
                <input type="hidden" name="action" value="supprimer">
                <button type="submit" class="favori-btn actif">
                    ★
                </button>
            </form>
                <a href="index.php?page=recette&id=<?= $r['id_recette'] ?>" class="recette-link">
                <img src="<?= $image ?>"
                        alt="Photo <?= htmlspecialchars($r['titre']) ?>"
                        onerror="this.onerror=null;this.src='/Cocktail-Guide/src/Ressources/Photos/defaut.jpg';">

                    <h4><?= htmlspecialchars($r['titre']) ?></h4>
                 </a>
                <p><?= htmlspecialchars($apercu) ?>...</p>
            
        </div>
    <?php endforeach; ?>
</div>


        <?php endif; ?>
