<?php

declare(strict_types=1);

// Autoload de Composer (PSR-4 : App\ => src/)
require __DIR__ . '/vendor/autoload.php';

use App\Agence;
use App\Entiter\Client;
use App\Entiter\Location;
use App\Entiter\Utilitaire;
use App\Entiter\Vehicule;
use App\Entiter\VeloElectrique;
use App\Entiter\Voiture;

// Affiche le résultat d'une tentative de location
function afficherLocation(?Location $location): string
{
    return $location !== null
        ? "<p>Location réussie : {$location}</p>"
        : '<p>Location refusée</p>';
}

// 1. Agence, 2 voitures (dont une 7 places), 1 utilitaire et 1 vélo électrique
$agence = new Agence('LocaPlus');
$clio = new Voiture('AB-123-CD', 'Renault', 'Clio', 42, 5);
$espace = new Voiture('EF-456-GH', 'Renault', 'Espace', 60, 7);
$master = new Utilitaire('IJ-789-KL', 'Renault', 'Master', 80, 12);
$velo = new VeloElectrique('MN-012-OP', 'Moustache', 'Samedi 28', 25, 120);

$agence->ajouterVehicule($clio)
       ->ajouterVehicule($espace)
       ->ajouterVehicule($master)
       ->ajouterVehicule($velo);

// 3. Parc : on garde l'affichage avant les locations (les statuts changent ensuite)
$parc = '';
foreach ($agence->getVehicules() as $vehicule) {
    $parc .= $vehicule;
}

// 4. Clients, le 3e a un email invalide
$jean = new Client('Dupont', 'Jean', 'jean.dupont@mail.fr', '0601020304');
$marie = new Client('Martin', 'Marie', 'marie.martin@mail.fr');
try {
    new Client('Durand', 'Paul', 'paul.durand@@mail');
    $erreurClient = '';
} catch (InvalidArgumentException $e) {
    $erreurClient = $e->getMessage();
}

// 5. Espace 3 jours, vélo 8 jours, puis l'Espace à nouveau (doit échouer)
$locationEspace = $agence->louerVehicule($jean, 'EF-456-GH', 3);
$locationVelo = $agence->louerVehicule($marie, 'MN-012-OP', 8);
$locationEspaceDejaLoue = $agence->louerVehicule($marie, 'EF-456-GH', 2);

// 6. Retour de l'Espace avec 16 000 km, puis nouvelle tentative (doit échouer : entretien)
$agence->rendreVehicule('EF-456-GH', 16000);
$besoinEntretien = $espace->besoinEntretien();
$locationSansEntretien = $agence->louerVehicule($marie, 'EF-456-GH', 2);

// 7. Entretien puis nouvelle location (doit réussir)
$espace->entretenir();
$locationApresEntretien = $agence->louerVehicule($marie, 'EF-456-GH', 2);

// 9. Sauvegarde
$fichier = __DIR__ . '/data/agence.txt';
$agence->sauvegarder($fichier);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Agence de location</title>
</head>
<body>
  <h1>Agence <?= $agence->getNom() ?></h1>

  <h2>1. Création de l'agence et des véhicules</h2>
  <p>Agence <?= $agence->getNom() ?> créée avec 2 voitures, 1 utilitaire et 1 vélo électrique.</p>

  <h2>2. Nombre de véhicules créés</h2>
  <p><?= Vehicule::getNbVehicules() ?> véhicules</p>

  <h2>3. Parc de véhicules</h2>
  <?= $parc ?>

  <h2>4. Clients</h2>
  <p>Client créé : <?= $jean ?></p>
  <p>Client créé : <?= $marie ?></p>
  <p>Erreur sur le 3e client : <?= $erreurClient ?></p>

  <h2>5. Locations</h2>
  <p>Jean loue l'Espace (7 places) pour 3 jours :</p>
  <?= afficherLocation($locationEspace) ?>
  <p>Marie loue le vélo pour 8 jours :</p>
  <?= afficherLocation($locationVelo) ?>
  <p>Marie essaie de louer l'Espace, déjà loué :</p>
  <?= afficherLocation($locationEspaceDejaLoue) ?>

  <h2>6. Retour de l'Espace après 16 000 km</h2>
  <p>Besoin d'entretien : <?= $besoinEntretien ? 'oui' : 'non' ?></p>
  <p>Marie essaie de louer l'Espace :</p>
  <?= afficherLocation($locationSansEntretien) ?>

  <h2>7. Entretien de l'Espace puis nouvelle location</h2>
  <?= afficherLocation($locationApresEntretien) ?>

  <h2>8. Bilan</h2>
  <h3>Locations</h3>
  <ul>
    <?php foreach ($agence->getLocations() as $location): ?>
      <li><?= $location ?></li>
    <?php endforeach; ?>
  </ul>
  <p>Chiffre d'affaires : <?= number_format($agence->getChiffreAffaires(), 2) ?> € TTC</p>
  <h3>Historique de l'Espace</h3>
  <?= $espace->afficherHistorique() ?>

  <h2>9. Sauvegarde</h2>
  <p>Agence sauvegardée dans data/agence.txt</p>
</body>
</html>
