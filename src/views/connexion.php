<?php

require_once __DIR__ . '/../models/utilisateur.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = $_POST['login'] ?? '';
    $motdepasse = $_POST['motdepasse'] ?? '';

    $user = connecterUtilisateur($login, $motdepasse);

    if ($user) {
        //création d'une session 
        $_SESSION['user'] = [
            'id' => $user['uti_id'],
            'login' => $user['login']
        ];

        header('Location: index.php?page=modification');
        exit;
    } else {
        $erreur = "Login ou mot de passe incorrect";
    }
}
?>

<div class="inscription-container"> 

    <h2>Connexion</h2>

    <?php if (isset($erreur)): ?>
        <p style="color:red"><?= htmlspecialchars($erreur, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="login">Login :</label><br>
        <input type="text" id="login" name="login" required><br><br>

        <label for="motdepasse">Mot de passe :</label><br>
        <input type="password" id="motdepasse" name="motdepasse" required><br><br>
        
        <button type="submit" name="submit">Me connecter</button>
    </form>

    <p>Pas de compte ? <a href="index.php?page=inscription">Inscrivez-vous</a></p>
</div>
