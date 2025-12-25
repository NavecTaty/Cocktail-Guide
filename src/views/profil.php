
<?php

// Si l'utilisateur est déjà connecté → page modification
if(isset($_SESSION['user'])){
    include __DIR__ .'/modification.php'; // afficher le formulaire de modification
}
// Sinon si un cookie ou info pour reconnaitre utilisateur existe → page connexion
else {
    include __DIR__ .'/connexion.php'; // afficher le formulaire de connexion
}
?>
 
</body>
</html>
