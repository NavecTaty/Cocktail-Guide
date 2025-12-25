<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cocktail Guide</title>
   <link rel="stylesheet" href="/Cocktail-Guide/src/Ressources/css/style.css">
    <link rel="stylesheet" href="/Cocktail-Guide//src/Ressources/css/header.css">
    <link rel="stylesheet" href="/Cocktail-Guide//src/Ressources/css/footer.css">
     <link rel="stylesheet" href="/Cocktail-Guide//src/Ressources/css/texte.css">
    <link rel="stylesheet" href="/Cocktail-Guide//src/Ressources/css/profil1.css">
     <link rel="stylesheet" href="/Cocktail-Guide//src/Ressources/css/hierarchie.css">
    <link rel="stylesheet" href="/Cocktail-Guide//src/Ressources/css/modification.css">



</head>
<body>
<header class= "header">
    <h1>Recettes</h1>
    <nav>
        <a href= "index.php?page=accueil.">Accueil</a>
        <a href= "index.php?page=recettes">Recettes</a>
        <a href= "index.php?page=favoris">Favoris</a>
        <a href= "index.php?page=profil">profil</a>
        <?php if (isset($_SESSION['user'])): ?>
         <a href="index.php?page=deconnexion">Se déconnecter</a>
        <?php endif; ?>
    </nav>
    <hr>
</header>

