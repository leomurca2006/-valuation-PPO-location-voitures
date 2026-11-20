<?php

declare(strict_types=1);

// Autoload de Composer (PSR-4 : App\ => src/)
require __DIR__ . '/vendor/autoload.php';

use App\Entiter\Vehicule;
use App\Entiter\Voiture;
use App\Entiter\Utilitaire;
use App\Entiter\VeloElectrique;
use App\Entiter\Client;
use App\Entiter\Location;

// 1. Création des véhicules
$clio = new Voiture('AB-123-CD', 'Renault', 'Clio', 42, 5);
$espace = new Voiture('EF-456-GH', 'Renault', 'Espace', 60, 7);
$master = new Utilitaire('IJ-789-KL', 'Renault', 'Master', 80, 12);
$velo = new VeloElectrique('MN-012-OP', 'Moustache', 'Samedi 28', 25, 120);

// 4. Clients (le 3e a un email invalide)
$jean = new Client('Dupont', 'Jean', 'jean.dupont@mail.fr', '0601020304');
$marie = new Client('Martin', 'Marie', 'marie.martin@mail.fr');
try {
    new Client('Durand', 'Paul', 'paul.durand@@mail');
    $erreurClient = '';
} catch (InvalidArgumentException $e) {
    $erreurClient = $e->getMessage();
}

// Test de Location (en attendant l'Agence, qui créera les locations elle-même)
$locationTest = new Location($jean, $clio, 3, $clio->louer(3));

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
  <?= $master ?>
  <?= $velo ?>

  <h2>2. Nombre de véhicules créés</h2>
  <p><?= Vehicule::getNbVehicules() ?> véhicules</p>

  <h2>4. Clients</h2>
  <p>Client créé : <?= $jean ?></p>
  <p>Client créé : <?= $marie ?></p>
  <p>Erreur sur le 3e client : <?= $erreurClient ?></p>

  <h2>Test Location</h2>
  <p><?= $locationTest ?> (le <?= $locationTest->getDateDebut() ?>)</p>
</body>
</html>
