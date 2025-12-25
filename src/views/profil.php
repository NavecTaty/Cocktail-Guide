 <?php include __DIR__ .'/../include/header.php'; ?>
<?php

// Si l'utilisateur est déjà connecté → page modification
if(isset($_SESSION['user_id'])){
    include __DIR__ .'/modification.php'; // afficher le formulaire de modification
}
// Sinon si un cookie ou info pour reconnaitre utilisateur existe → page connexion
else {
    include __DIR__ .'/connexion.php'; // afficher le formulaire de connexion
}
?>
 
 <?php include __DIR__ .'/../include/footer.php'; ?>
</body>
</html>
