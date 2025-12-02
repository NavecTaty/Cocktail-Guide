 <?php include('../include/header.php'); ?>
<?php
session_start();

// Si l'utilisateur est déjà connecté → page modification
if(isset($_SESSION['user_id'])){
    include('../include/modification.php'); // afficher le formulaire de modification
}
// Sinon si un cookie ou info pour reconnaitre utilisateur existe → page connexion
else {
    include('../include/inscription.php'); // afficher le formulaire de connexion
}
?>
 
 <?php include('../include/footer.php'); ?>
</body>
</html>
