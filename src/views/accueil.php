<?php
require_once __DIR__ . '/../models/aliment.php';
require_once __DIR__ . '/../models/recette.php';
require_once __DIR__ . '/../models/recettesFavorites.php';


// Récupérer toutes les recettes
$recettes = getAllRecettes();
$recettesAvecImage = [];

foreach ($recettes as $r) {
    $image = getRecettePhoto($r['titre']); // image déduite du titre

    // On garde uniquement les recettes qui ont une image associée
    if (!empty($image)) {
        $recettesAvecImage[] = [
            'id_recette' =>$r['id_recette'],
            'titre' => $r['titre'],
            'preparation' => $r['preparation'],
            'image' => $image
        ];
    }
}
?>

<div class = "recherche-wrapper">
<div class="recherche-container">
    <form action="recherche.php" method="GET">
        <input type="text" name="q" placeholder="Rechercher : noix , oeuf" required>
        <button type="submit">Rechercher</button>
    </form>
</div>
<div class="recherche-container">
    <form action="recherche.php" method="GET">
        <input type="text" name="q" placeholder="Rechercher : noix , oeuf" required>
        <button type="submit">Rechercher</button>
    </form>
</div>
</div>

<div class="texte">
    <p><strong>Qui sommes-nous ?</strong></p>
    <p>
        Bienvenue sur notre Cocktail Guide ! Nous sommes passionnés par l’art des cocktails et la découverte de nouvelles saveurs. Nous aimons partager des recettes créatives et accessibles, que ce soit pour les débutants ou les amateurs expérimentés.
            Pour nous, préparer un cocktail n’est pas seulement suivre une recette : c’est une occasion de laisser libre cours à sa créativité, de s’amuser et de partager des moments conviviaux avec ses proches. Sur notre site, vous trouverez des guides, 
            des astuces et nos recettes préférées, toutes pensées pour rendre votre expérience de mixologie agréable et mémorable.
    
    </p>
</div>
<main class="quelques-recettes" style="flex:1;">
    <h2 alignment = >Découvrez quelques-unes de nos recettes et boissons</h2>
    <div class="quelques-recettes-grille">

        <?php foreach ($recettesAvecImage as $r): ?>
            <?php $apercu = mb_substr($r['preparation'], 0, 120); 
                $estFavori = estFavoriGlobal($r['id_recette']);
            ?>

            <div class="recette-card-bis">
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
                <img src="<?= htmlspecialchars($r['image'], ENT_QUOTES) ?>"
                    alt="Photo <?= htmlspecialchars($r['titre'], ENT_QUOTES) ?>">

                <h4><?= htmlspecialchars($r['titre'], ENT_QUOTES) ?></h4>

                <p><?= htmlspecialchars($apercu, ENT_QUOTES) ?>...</p>
            </div>
        <?php endforeach; ?>
    </div>
</main>
