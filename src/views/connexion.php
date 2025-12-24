<!-- page de connexion au site web -->
<!-- si l'utilisateur existe dans la base de données , connextion établie et retour vers la page d'acceuil-->
<div class= inscription-container> 

    <h2>Connexion</h2>
<!--formuliare de connexion-->
<form action="#" method="POST">
    <label for="login">Login:</label><br>
    <input type="text" id="login" name="login"  required><br><br>

    <label for="motdepasse">Mot de passe :</label><br>
    <input type="password" id="motdepasse" name="motdepasse" placeholder="Votre mot de passe" required><br><br>
    
    <button type="submit" name = "submit">me connecter</button>
    
    </form>
   <p> Pas de compte ?<a href= "inscription_page.php">inscrivez-vous</a></p>
   
    <!--vérification si l'utilisateur existe dans la base de données-->
    <!--si dans la base de données session vers la page d'acceuil-->



    <!--sinon session vers la page modification -->

</div>
