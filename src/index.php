<?php
// Point d’entrée de l’application

session_start();

// Page demandée (par défaut accueil)
$page = $_GET['page'] ?? 'accueil';

// Pages autorisées
$pagesAutorisees = [
    'accueil',
    'profil',
    'inscription',
    'modification',
    'connexion',
    'recettes',
    'hierarchie',
    'deconnexion',
    'favoris',
    'favoris_action'
];

if (!in_array($page, $pagesAutorisees)) {
    $page = 'accueil';
}

//gestion de l'ajout d'un favoris
if($page === 'favoris_action'){
    require_once __DIR__ . '/models/recettesFavorites.php';
    
    //traitement si id_recette >= 0 
        if (isset($_POST['id_recette']) && ctype_digit((string)$_POST['id_recette'])) {
            
            $idRecette = (int) $_POST['id_recette'];
            $action = $_POST['action'] ?? 'ajouter';
           
            if (isset($_SESSION['user'])) {
                // utilisateur connecté
                if ($action === 'supprimer') {
                    supprimerFavori($_SESSION['user']['id'], $idRecette);
                } else {
                    ajouterFavori($_SESSION['user']['id'], $idRecette);
                }
            } else {
                // utilisateur non connecté
                if ($action === 'supprimer') {
                    supprimerFavoriTemporaire($idRecette);
                } else {
                    ajouterFavoriTemporaire($idRecette);
                }
            }
             //redirection à la page précédente
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
            exit;
        }

}
// HEADER GLOBAL
include __DIR__ . '/include/header.php';?>

<main class="page-content">
    <?php 

    //Les vues
    require_once __DIR__ . '/views/' . $page . '.php';
     ?>
</main>
<?php
// FOOTER GLOBAL
include __DIR__ . '/include/footer.php';
?>