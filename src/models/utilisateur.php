<?php
/**
 * Stocke les informations des utilisateurs dans la base de données
 */
require_once __DIR__ . '/connection.php';

/**
 * Gère la création d'un nouvel utilisateur dans la base de données
 */
function creerUtilisateur($donnees){
    global $access;
try{
     $userSQL = ("INSERT into utilisateur
                (nom,prenom,mdp,login,sexe,addr_mail,ddn,addresse,cp,ville,telephone)
                values(?,?,?,?,?,?,?,?,?,?,?)");
    $stmtUser = $access->prepare($userSQL);
    return $stmtUser->execute([
        $donnees['nom'],
        $donnees['prenom'],
        password_hash($donnees['motdepasse'],PASSWORD_DEFAULT),
        $donnees['login'],
        $donnees['sexe'],
        $donnees['email'],
        $donnees['ddn'],
        $donnees['adresse'],
        $donnees['cp'],
        $donnees['ville'],
        $donnees['telephone']
    ]);
}catch(PDOException $e) {
        //duplicate key (MySQL)
         if ($e->errorInfo[1] == 1062) {

            // Identifier la colonne concernée
            if (str_contains($e->getMessage(), 'login')) {
                return ['error' => 'LOGIN_EXISTS'];
            }

            if (str_contains($e->getMessage(), 'addr_mail')) {
                return ['error' => 'EMAIL_EXISTS'];
            }
        }

        return ['error' => 'DB_ERROR'];
   
}
}

/**
 * Gère l'authentification d'un utilisateur
 */
function connecterUtilisateur($login,$mdp){
    global $access;

    $userSQL = ("SELECT *
                from utilisateur 
                where login = ? ");
    $stmtUser = $access->prepare($userSQL);
    $stmtUser->execute([$login]);
    $res = $stmtUser->fetch(PDO::FETCH_ASSOC);
    //Vérification correspondance user et mot de passe
    if($res && password_verify($mdp,$res['mdp'])){
        return $res;
    }
    return null;
}
/**
 * Met à jour les informations d'un utilisateur
 */

function modifierUtilisateur($id, $donnees) {
    global $access;

    $params = [
        $donnees['nom'],
        $donnees['prenom'],
        $donnees['sexe'],
        $donnees['email'],
        $donnees['login'],
        $donnees['ddn'],
        $donnees['adresse'],
        $donnees['cp'],
        $donnees['ville'],
        $donnees['telephone'],
        $id
    ];

    $sql = "UPDATE utilisateur SET
            nom = ?, prenom = ?, sexe = ?, addr_mail = ?, login = ?,
            ddn = ?, addresse = ?, cp = ?, ville = ?, telephone = ?
            WHERE uti_id = ?";

    $stmt = $access->prepare($sql);
    return $stmt->execute($params);
}
/**
 * Vérifie si un login existe déjà
 */
function loginExiste($login, $idUtilisateur) {
    global $access;

    $sql = "SELECT 1 FROM utilisateur WHERE login = ? AND uti_id != ?";
    $stmt = $access->prepare($sql);
    $stmt->execute([$login, $idUtilisateur]);

    return $stmt->fetch() !== false;
}

/**
 * Vérifie si une adresse email existe déjà
 */
function emailExiste($email, $idUtilisateur) {
    global $access;

    $sql = "SELECT 1 FROM utilisateur WHERE addr_mail = ? AND uti_id != ?";
    $stmt = $access->prepare($sql);
    $stmt->execute([$email, $idUtilisateur]);

    return $stmt->fetch() !== false;
}
/**
 * Modifie le mot de passe d'un utilisateur dans la base de données
 */
function modifierMotDePasse($id, $motdepasse) {
    global $access;

    $hash = password_hash($motdepasse, PASSWORD_DEFAULT);
    $stmt = $access->prepare("UPDATE utilisateur SET mdp = ? WHERE uti_id = ?");
    return $stmt->execute([$hash, $id]);
}
/**
 * Récupère un utilisateur par son id
 */
function getUtilisateurParId($id) {
    global $access;

    $stmt = $access->prepare("SELECT * FROM utilisateur WHERE uti_id = ?");
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
