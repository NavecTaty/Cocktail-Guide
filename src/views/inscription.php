<?php
require_once __DIR__ . '/../models/utilisateur.php';

/**
 * Formulaire d'inscription et validation
 */

$champsIncorrects = [];
$valide = true;
$erreurs = [];

/* VALIDATION */
if (isset($_POST['submit'])) {

    $valide = true;
    $champsIncorrects = [];

    if (empty($_POST['sexe']) || !in_array($_POST['sexe'], ['f', 'h'])) {
        $champsIncorrects[] = 'sexe';
        $valide = false;
    }

    if (empty($_POST['nom']) || strlen(trim($_POST['nom'])) < 2) {
        $champsIncorrects[] = 'nom';
        $valide = false;
    }

    if (empty($_POST['prenom']) || strlen(trim($_POST['prenom'])) < 2) {
        $champsIncorrects[] = 'prenom';
        $valide = false;
    }

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $champsIncorrects[] = 'email';
        $valide = false;
    }

    if (empty($_POST['login'])) {
        $champsIncorrects[] = 'login';
        $valide = false;
    }

    if (
        empty($_POST['motdepasse']) ||
        empty($_POST['confirmer']) ||
        $_POST['motdepasse'] !== $_POST['confirmer']
    ) {
        $champsIncorrects[] = 'motdepasse';
        $valide = false;
    }

    if (!empty($_POST['ddn'])) {
        $parts = explode('-', $_POST['ddn']);
        if (count($parts) !== 3 || !checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0])) {
            $champsIncorrects[] = 'ddn';
            $valide = false;
        }
    }

    if (!empty($_POST['cp']) && !preg_match('/^\d{5}$/', $_POST['cp'])) {
        $champsIncorrects[] = 'cp';
        $valide = false;
    }

    if (!empty($_POST['telephone']) && !preg_match('/^\d{10}$/', $_POST['telephone'])) {
        $champsIncorrects[] = 'telephone';
        $valide = false;
    }

    if ($valide) {
        $ok = creerUtilisateur($_POST);

        if ($ok === true) {
            ?>
            <script>
                if (confirm("Inscription réussie ! Voulez-vous aller à la page d'accueil ?")) {
                    window.location.href = "index.php?page=accueil";
                } else {
                    window.location.href = "index.php?page=inscription";
                }
            </script>
            <?php
            exit;
        } else {
            switch ($ok['error'] ?? '') {
                case 'LOGIN_EXISTS':
                    $erreurs[] = "Ce login est déjà utilisé.";
                    break;
                case 'EMAIL_EXISTS':
                    $erreurs[] = "Cette adresse e-mail est déjà utilisée.";
                    break;
                default:
                    $erreurs[] = "Erreur technique. Réessayez plus tard.";
            }
        }
    }
}

/*AFFICHAGE */

include __DIR__ . '/../include/header.php';
?>

<div class="inscription-container"> 
    <h2>Inscription</h2>

    <?php if (!empty($erreurs)) : ?>
        <div style="color:red;">
            <?php foreach ($erreurs as $e) echo "<p>$e</p>"; ?>
        </div>
    <?php endif; ?>

    <form action="#" method="POST">

        <label>Nom :
            <?php if (in_array('nom', $champsIncorrects)) echo "<span style='color:red;'> minimum 2 lettres</span>"; ?>
        </label><br>
        <input type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES) ?>"><br><br>

        <label>Prénom :
            <?php if (in_array('prenom', $champsIncorrects)) echo "<span style='color:red;'> minimum 2 lettres</span>"; ?>
        </label><br>
        <input type="text" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '', ENT_QUOTES) ?>"><br><br>

        Vous êtes :
        <input type="radio" name="sexe" value="f" <?= (($_POST['sexe'] ?? '') === 'f') ? 'checked' : '' ?>> Femme
        <input type="radio" name="sexe" value="h" <?= (($_POST['sexe'] ?? '') === 'h') ? 'checked' : '' ?>> Homme
        <?php if (in_array('sexe', $champsIncorrects)) echo "<span style='color:red;'> *</span>"; ?>
        <br><br>

        <label>Email :
            <?php if (in_array('email', $champsIncorrects)) echo "<span style='color:red;'> email invalide</span>"; ?>
        </label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES) ?>"><br><br>

        <label>Login :
            <?php if (in_array('login', $champsIncorrects)) echo "<span style='color:red;'> obligatoire</span>"; ?>
        </label><br>
        <input type="text" name="login" required value="<?= htmlspecialchars($_POST['login'] ?? '', ENT_QUOTES) ?>"><br><br>

        <label>Mot de passe :</label><br>
        <input type="password" name="motdepasse" required>
        <?php if (in_array('motdepasse', $champsIncorrects)) echo "<span style='color:red;'> mots de passe incorrects</span>"; ?>
        <br><br>

        <label>Confirmer mot de passe :</label><br>
        <input type="password" name="confirmer" required><br><br>

        <label>Date de naissance :</label><br>
        <input type="date" name="ddn" value="<?= htmlspecialchars($_POST['ddn'] ?? '', ENT_QUOTES) ?>">
        <?php if (in_array('ddn', $champsIncorrects)) echo "<span style='color:red;'> date invalide</span>"; ?>
        <br><br>

        <label>Adresse :</label><br>
        <input type="text" name="adresse" value="<?= htmlspecialchars($_POST['adresse'] ?? '', ENT_QUOTES) ?>"><br><br>

        <label>Code postal :</label><br>
        <input type="text" name="cp" value="<?= htmlspecialchars($_POST['cp'] ?? '', ENT_QUOTES) ?>">
        <?php if (in_array('cp', $champsIncorrects)) echo "<span style='color:red;'> 5 chiffres</span>"; ?>
        <br><br>

        <label>Ville :</label><br>
        <input type="text" name="ville" value="<?= htmlspecialchars($_POST['ville'] ?? '', ENT_QUOTES) ?>"><br><br>

        <label>Téléphone :</label><br>
        <input type="tel" name="telephone" value="<?= htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES) ?>">
        <?php if (in_array('telephone', $champsIncorrects)) echo "<span style='color:red;'> 10 chiffres</span>"; ?>
        <br><br>

        <button type="submit" name="submit">S'inscrire</button>

    </form>
</div>

<?php include __DIR__ . '/../include/footer.php'; ?>
