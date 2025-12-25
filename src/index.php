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
    'favoris'
];

if (!in_array($page, $pagesAutorisees)) {
    $page = 'accueil';
}

// HEADER GLOBAL
include __DIR__ . '/include/header.php';?>

<main class="page-content">
    <?php require_once __DIR__ . '/views/' . $page . '.php'; ?>
</main>
<?php
// FOOTER GLOBAL
include __DIR__ . '/include/footer.php';
?>