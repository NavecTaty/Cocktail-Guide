<?php
//ajax

require_once __DIR__ . '/../models/research.php';

$term = $_GET['term'] ?? '';

if (strlen($term) < 2) {
    echo json_encode([]);
    exit;
}

// Fonction du modèle
$aliments = rechercherAlimentsParNom($term);

// On renvoie seulement les noms (plus simple côté JS)
$noms = array_map(fn($a) => $a['nom'], $aliments);

echo json_encode($noms);
