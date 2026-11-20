<?php

// Chargement des interfaces
require_once './src/Contrats/Louable.php';
require_once './src/Contrats/Entretenable.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Agence de location</title>
</head>
<body>
  <h1>Agence de location de véhicules</h1>

  <ul>
    <li>Interface Louable : <?= interface_exists('Louable') ? 'OK' : 'manquante' ?></li>
    <li>Interface Entretenable : <?= interface_exists('Entretenable') ? 'OK' : 'manquante' ?></li>
  </ul>
</body>
</html>
