<?php
/**
 * Gère les modifications des informations personnelles 
 */
require_once __DIR__ . '/../models/utilisateur.php';

/* sécurité : utilisateur connecté */
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=connexion');
    exit;
}

/* récupération utilisateur depuis la BDD */
$idUtilisateur = $_SESSION['user']['id'];
$user = getUtilisateurParId($idUtilisateur);

$erreurs = [];
$valide = true;

/* ENREGISTRER */
if (isset($_POST['enregistrer'])) {

    /* login obligatoire */
    if (empty($_POST['login'])) {
        $erreurs[] = "Le login est obligatoire.";
        $valide = false;
    }

    /* unicité login */
    if ($valide && loginExiste($_POST['login'], $idUtilisateur)) {
        $erreurs[] = "Ce login est déjà utilisé.";
        $valide = false;
    }

    /* email facultatif mais valide */
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Adresse e-mail invalide.";
        $valide = false;
    }

    /* unicité email */
    if (
        $valide &&
        !empty($_POST['email']) &&
        emailExiste($_POST['email'], $idUtilisateur)
    ) {
        $erreurs[] = "Cette adresse e-mail est déjà utilisée.";
        $valide = false;
    }

    /* mot de passe facultatif */
    if (!empty($_POST['motdepasse'])) {
        if ($_POST['motdepasse'] !== $_POST['confirmer']) {
            $erreurs[] = "Les mots de passe ne correspondent pas.";
            $valide = false;
        }
    }

    /* champs facultatifs avec format */
    if (!empty($_POST['cp']) && !preg_match('/^\d{5}$/', $_POST['cp'])) {
        $erreurs[] = "Code postal invalide.";
        $valide = false;
    }

    if (!empty($_POST['telephone']) && !preg_match('/^\d{10}$/', $_POST['telephone'])) {
        $erreurs[] = "Téléphone invalide.";
        $valide = false;
    }

    /* mise à jour */
    if ($valide) {

        modifierUtilisateur($idUtilisateur, $_POST);

        if (!empty($_POST['motdepasse'])) {
            modifierMotDePasse($idUtilisateur, $_POST['motdepasse']);
        }

        header('Location: index.php?page=profil');
        exit;
    }
}

/* ANNULER */
if (isset($_POST['annuler'])) {
    header('Location: index.php?page=profil');
    exit;
}

/* AFFICHAGE */
?>

<div class="inscription-container">
    <h2>Modifier mes informations</h2>

    <?php foreach ($erreurs as $e): ?>
        <p style="color:red"><?= htmlspecialchars($e, ENT_QUOTES) ?></p>
    <?php endforeach; ?>

    <form method="POST">

        <label>Login <span style="color:red">*</span></label><br>
        <input type="text" name="login"
               value="<?= htmlspecialchars($_POST['login'] ?? $user['login'], ENT_QUOTES) ?>"
               required><br><br>

        <label>Email</label><br>
        <input type="email" name="email"
               value="<?= htmlspecialchars($_POST['email'] ?? $user['addr_mail'] ?? '', ENT_QUOTES) ?>"><br><br>

        <label>Nouveau mot de passe</label><br>
        <input type="password" name="motdepasse"><br><br>

        <label>Confirmer mot de passe</label><br>
        <input type="password" name="confirmer"><br><br>

        <label>Nom</label><br>
        <input type="text" name="nom"
               value="<?= htmlspecialchars($_POST['nom'] ?? $user['nom'] ?? '', ENT_QUOTES) ?>"><br><br>

        <label>Prénom</label><br>
        <input type="text" name="prenom"
               value="<?= htmlspecialchars($_POST['prenom'] ?? $user['prenom'] ?? '', ENT_QUOTES) ?>"><br><br>

        <label>Adresse</label><br>
        <input type="text" name="adresse"
               value="<?= htmlspecialchars($_POST['adresse'] ?? $user['addresse'] ?? '', ENT_QUOTES) ?>"><br><br>

        <label>Code postal</label><br>
        <input type="text" name="cp"
               value="<?= htmlspecialchars($_POST['cp'] ?? $user['cp'] ?? '', ENT_QUOTES) ?>"><br><br>

        <label>Ville</label><br>
        <input type="text" name="ville"
               value="<?= htmlspecialchars($_POST['ville'] ?? $user['ville'] ?? '', ENT_QUOTES) ?>"><br><br>

        <label>Téléphone</label><br>
        <input type="tel" name="telephone"
               value="<?= htmlspecialchars($_POST['telephone'] ?? $user['telephone'] ?? '', ENT_QUOTES) ?>"><br><br>

        <div class="form-actions">
            <button type="submit" name="enregistrer" class="btn-primary">
                Enregistrer
            </button>
            <button type="submit" name="annuler" class="btn-secondary">
                Annuler
            </button>
        </div>

    </form>
</div>
