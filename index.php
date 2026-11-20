<?php

declare(strict_types=1);

// Autoload de Composer (PSR-4 : App\ => src/)
require __DIR__ . '/vendor/autoload.php';

use App\Entiter\Vehicule;
use App\Entiter\Voiture;

// 1. Création des véhicules
$clio = new Voiture('AB-123-CD', 'Renault', 'Clio', 42, 5);
$espace = new Voiture('EF-456-GH', 'Renault', 'Espace', 60, 7);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Agence de location</title>
</head>
<body>
  <h1>Agence de location de véhicules</h1>

  <h2>1. Véhicules</h2>
  <?= $clio ?>
  <?= $espace ?>

  <h2>2. Nombre de véhicules créés</h2>
  <p><?= Vehicule::getNbVehicules() ?> véhicules</p>
</body>
</html>
