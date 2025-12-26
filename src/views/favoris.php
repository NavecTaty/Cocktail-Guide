<?php
/**
 * Gère les recettes favorites d'un utilisateur
 */
require_once __DIR__ . '/../models/recettesFavorites.php';

if (!isset($_SESSION['user'])) {?>
    <p class="aucune-recette">Connectes-toi, tes favoris t'attendent vite !</p>";
   <?php return;
}

$favoris = getFavorisUtilisateur($_SESSION['user']['id']);
?>

<!------ Affichage des favoris ------>
<h2 class="favoriTitre">Mes recettes favorites</h2>

<?php if (empty($favoris)): ?>
    <p class="aucune-recette">Aucune recette favorite.</p>
<?php else: ?>
    <ul>
        <?php foreach ($favoris as $r):
            //$image = getRecettePhoto($r['titre']); // image déduite du titre
             ?>
            <li>
                <?= htmlspecialchars($r['titre']) ?>
                <form method="post" action="index.php?page=favoris_action" style="display:inline;">
                    <input type="hidden" name="id_recette" value="<?= $r['id_recette'] ?>">
                    <input type="hidden" name="action" value="supprimer">
                    <button>★</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>