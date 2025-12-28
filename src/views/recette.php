<?php
require_once __DIR__ . '/../models/recette.php';
require_once __DIR__ . '/../models/recettesFavorites.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "<p>Recette invalide.</p>";
    return;
}

$idRecette = (int) $_GET['id'];
$recette = getRecetteById($idRecette);

if (!$recette) {
    echo "<p>Recette introuvable.</p>";
    return;
}

$image = getRecettePhoto($recette['titre']);
$estFavori = estFavoriGlobal($idRecette);
?>

<section class="recette-detail">

    <h2><?= htmlspecialchars($recette['titre']) ?></h2>
    <img src="<?= $image ?>" alt="<?= htmlspecialchars($recette['titre']) ?>" 
    onerror="this.onerror=null;this.src='/Cocktail-Guide/src/Ressources/Photos/defaut.jpg';"
    class="recette-detail-img">

    <h3>Ingrédients</h3>
    <ul class="ingredients">
        <?php foreach (explode('|', $recette['ingredients']) as $ing): ?>
            <li><?= htmlspecialchars($ing) ?></li>
        <?php endforeach; ?>
    </ul>

    <h3>Préparation</h3>
    <p class="preparation">
        <?= nl2br(htmlspecialchars($recette['preparation'])) ?>
    </p>

</section>
