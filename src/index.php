<?php
// Point d’entrée de l’application

session_start();

// Page demandée (par défaut : accueil )
$page = $_GET['page'] ?? 'accueil';

// pages autorisées
$pagesAutorisees = [
    'accueil',
    'profil',
    'inscription', 
    'modification',
    'connexion',
    'recettes',
    'hierarchie'
];

if (!in_array($page, $pagesAutorisees)) {
    $page = 'accueil';
}

// Inclusion de la page correspondante
require_once __DIR__ . '/views/' . $page . '.php';
