
<?php
/**
 * Formulaire d'inscription  et verification des données
 */
$champsIncorrects = [];
$valide = true;
if (isset($_POST['submit'])) {

    //sexe
     if (!empty($_POST['sexe'])) {
    if (!isset($_POST['sexe']) || !in_array($_POST['sexe'], ['f', 'h'])) {
        $champsIncorrects[] = 'sexe';
        $valide = false;
       
    }

}
    // Nom
     if (!empty($_POST['nom'])) {
    if (!isset($_POST['nom']) || trim($_POST['nom']) === '') {
        $champsIncorrects[] = 'nom';
        $valide = false;
    }
}

    // Prénom
     if (!empty($_POST['prenom'])) {
    if (!isset($_POST['prenom']) || trim($_POST['prenom']) === '') {
        $champsIncorrects[] = 'prenom';
        $valide = false;
    }
     }
    // Date de naissance
    if (!empty($_POST['ddn'])) {
        $parts = explode('-', $_POST['ddn']);
        if (count($parts) !== 3 || !checkdate($parts[1], $parts[2], $parts[0])) {
            $champsIncorrects[] = 'ddn';
            $valide = false;
        }
     else {
        $champsIncorrects[] = 'ddn';
        $valide = false;
    }
    // email
    if (!empty($_POST['email'])) {
    if (!filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
        $champsIncorrects[] = 'email';
        $valide = false;
    }
    }
}
    // login
    if (!isset($_POST['login']) || trim($_POST['login']) === '') {
        $champsIncorrects[] = 'login';
        $valide = false;
    }
    // mot de passe
    if (empty($_POST['motdepasse']) || empty($_POST['confirmer'])) {
        $champsIncorrects[] = 'motdepasse';
        $valide = false;
    } elseif ($_POST['motdepasse'] !== $_POST['confirmer']) {
        $champsIncorrects[] = 'motdepasse';
        $valide = false;
    }
     // code postale
     if (!empty($_POST['cp'])) {
    if (!preg_match('/^[0-9]{5}$/', $_POST['cp'] ?? '')) {
        $champsIncorrects[] = 'cp';
        $valide = false;
    }
}

    // telephone
    if (!empty($_POST['telephone'])) {
    if (!preg_match('/^[0-9]{10}$/', $_POST['telephone'] ?? '')) {
        $champsIncorrects[] = 'telephone';
        $valide = false;
    }
    }
    if($valide){
        echo "Création du compte réussi";
        //INSERTION DANS LA BASES DE DONNées

        //On repart à la page d'acceuil
    }


}
?>
<div class= inscription-container> 
    <h2>Inscription</h2>
    <!--création du formulaire d'inscription-->
    
<form action="#" method="POST">
    <label for="nom">
    Nom : <?php if(in_array('nom', $champsIncorrects)) echo "<span style='color:red;'>minimum 5 lettres</span>"; ?> 
    </label><br>
    <input type="text" id="nom" name="nom" placeholder="Dupont"><br><br>

    <label for="prenom">Prénom : <?php if(in_array('prenom', $champsIncorrects)) echo "<span style='color:red;'> minimu 5 littres</span>"; ?></label><br>
    <input type="text" id="prenom" name="prenom" placeholder="Jean" >
   <br><br>

     Vous êtes :
    <input type="radio" name="sexe" value="f" <?php if(($_POST['sexe'] ?? '') === 'f') echo 'checked'; ?>> une femme
    <input type="radio" name="sexe" value="h" <?php if(($_POST['sexe'] ?? '') === 'h') echo 'checked'; ?>> un homme
    <?php if(in_array('sexe', $champsIncorrects)) echo "<span style='color:red;'> *</span>"; ?><br><br>

    <label for="email">Email : <?php if(in_array('email', $champsIncorrects)) echo "<span style='color:red;'> *</span>"; ?></label><br>
    <input type="email" id="email" name="email" placeholder="jeandupont@gmail.com" >
        <?php if(in_array('email', $champsIncorrects)) echo "<span style='color:red;'> *</span>"; ?>
        <br><br>

    <label for="login">Login:  <?php if(in_array('login', $champsIncorrects)) echo "<span style='color:red;'> *</span>"; ?>
    </label><br>
    <input type="text" id="login" name="login"  required>
    <br><br>

    <label for="motdepasse">Mot de passe :</label><br>
    <input type="password" id="motdepasse" name="motdepasse" placeholder="Votre mot de passe" required>
        <?php if(in_array('motdepasse', $champsIncorrects)) echo "<span style='color:red;'> *les mots de passe ne sont pas identiques</span>"; ?><br><br>

    <label for="confirmer">Confirmer le mot de passe :</label><br>
    <input type="password" id="confirmer" name="confirmer" placeholder="Confirmer le mot de passe" required>
        <?php if(in_array('motdepasse', $champsIncorrects)) echo "<span style='color:red;'> les mots de passes ne sont identiques</span>"; ?><br><br>

    <label for="ddn">Date de naissance :</label><br>
    <input type="date" id="ddn" name="ddn" >
        <?php if(in_array('ddn', $champsIncorrects)) echo "<span style='color:red;'> *</span>"; ?><br><br>

    <label for="adresse">Adresse :</label><br>
    <input type="text" id="adresse" name="adresse" placeholder="Votre adresse" >
        <?php if(in_array('adresse', $champsIncorrects)) echo "<span style='color:red;'> *</span>"; ?><br><br>

    <label for="cp">Code postal :</label><br>
    <input type="text" id="cp" name="cp" placeholder="Votre code postal" >
        <?php if(in_array('cp', $champsIncorrects)) echo "<span style='color:red;'> *</span>"; ?><br><br>

    <label for="ville">Ville :</label><br>
    <input type="text" id="ville" name="ville" placeholder="Votre ville" >
        <?php if(in_array('ville', $champsIncorrects)) echo "<span style='color:red;'> *</span>"; ?><br><br>

    <label for="telephone">Téléphone :</label><br>
    <input type="tel" id="telephone" name="telephone" placeholder="Votre numéro de téléphone" >
        <?php if(in_array('telephone', $champsIncorrects)) echo "<span style='color:red;'> *</span>"; ?><br><br>

    <button type="submit"name= "submit">S'inscrire</button>

</form>
</div>
<!-- insertion dans la base de données-->

