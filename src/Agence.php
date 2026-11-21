<?php

declare(strict_types=1);

namespace App;

use App\Contrats\Entretenable;
use App\Entiter\Client;
use App\Entiter\Location;
use App\Entiter\Vehicule;
use RuntimeException;

/*
 Gestion  du parc de véhicules,des locations(sauvegarde et chargement), representation de l'agence
 */
class Agence
{
    private string $nom;

    private array $vehicules = [];

    private array $locations = [];
    /*
    constructeur de l'agence
     */
    public function __construct(string $nom)
    {
        $this->nom = $nom;
    }

    /* retourne le nom de l'agence*/
    public function getNom(): string
    {
        return $this->nom;
    }

   /* retourne la liste des véhicules de l'agence*/
    public function getVehicules(): array
    {
        return $this->vehicules;
    }

   /* retourne l'historique des locations*/

    public function getLocations(): array
    {
        return $this->locations;
    }

    public function ajouterVehicule(Vehicule $vehicule): self
    {
        $this->vehicules[] = $vehicule;

        return $this;
    }

  /* liste des vehicule disponible*/
    public function getVehiculesDisponibles(): array
    {
        return array_filter($this->vehicules, function (Vehicule $vehicule): bool {
            return $vehicule->estDisponible();
        });
    }

    /* trouve un véhicule par son immatriculation*/
    private function trouverVehicule(string $immatriculation): ?Vehicule
    {
        foreach ($this->vehicules as $vehicule) {
            if ($vehicule->getImmatriculation() === $immatriculation) {
                return $vehicule;
            }
        }

        return null;
    }

  /*fonction de location d'un véhicule à un client, retourne une instance de Location ou null si le véhicule n'est pas disponible*/
    public function louerVehicule(Client $client, string $immatriculation, int $jours): ?Location
    {
        $vehicule = $this->trouverVehicule($immatriculation);

        if ($vehicule === null || !$vehicule->estDisponible()) {
            return null;
        }

        if ($vehicule instanceof Entretenable && $vehicule->besoinEntretien()) {
            return null;
        }

        $montantTTC = $vehicule->louer($jours);
        if ($montantTTC <= 0) {
            return null;
        }

        $location = new Location($client, $vehicule, $jours, $montantTTC);
        $this->locations[] = $location;

        return $location;
    }
/*fonction de retour vehicule qui met a jour l'état du véhicule et ajoute les kilomètres parcourus si le véhicule est entretenable*/
    public function rendreVehicule(string $immatriculation, int $kmParcourus = 0): void
    {
        $vehicule = $this->trouverVehicule($immatriculation);

        if ($vehicule === null) {
            return;
        }

        $vehicule->rendre();

        if ($vehicule instanceof Entretenable && $kmParcourus > 0) {
            $vehicule->ajouterKilometres($kmParcourus);
        }
    }
/* fonction de calcule du chiffre d'affaire*/
    public function getChiffreAffaires(): float
    {
        $total = 0.0;
        foreach ($this->locations as $location) {
            $total += $location->getMontantTTC();
        }

        return round($total, 2);
    }
    public function __serialize(): array
    {/*preparation des données pour la sérialisation  */ 
        return [
            'nom' => $this->nom,
            'vehicules' => $this->vehicules,
            'locations' => $this->locations,
        ];
    }

   
    public function __unserialize(array $data): void
    {
        $this->nom = $data['nom'];
        $this->vehicules = $data['vehicules'];
        $this->locations = $data['locations'];
    }
/*fonctions de sauvegarde de l'agence dans un fichier, crée le dossier si nécessaire*/

    public function sauvegarder(string $fichier): void
    {
        $dossier = dirname($fichier);
        if (!is_dir($dossier)) {
            mkdir($dossier, 0777, true);
        }

        file_put_contents($fichier, serialize($this));
    }

    public static function charger(string $fichier): Agence
    {
        if (!file_exists($fichier)) {
            throw new RuntimeException("Le fichier de sauvegarde est introuvable : {$fichier}");
        }

        $contenu = file_get_contents($fichier);
        if ($contenu === false) {
            throw new RuntimeException("Impossible de lire le fichier : {$fichier}");
        }

        $agence = unserialize($contenu);
        if (!$agence instanceof self) {
            throw new RuntimeException("Le fichier ne contient pas une instance valide d'Agence.");
        }

        return $agence;
    }
}