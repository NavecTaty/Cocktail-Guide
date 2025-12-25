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
