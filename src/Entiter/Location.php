<?php

declare(strict_types=1);

namespace App\Entiter;

class Location
{
    /*
      Constructeur de la location (enregistre la date du jour par défaut)
     */
    public function __construct(
        private Client $client,
        private Vehicule $vehicule,
        private int $jours,
        private float $montantTTC,
        private ?string $dateDebut = null
    ) {
        // Sans date, on enregistre la date du jour
        $this->dateDebut = $dateDebut ?? date('d/m/Y');
    }
    /* 
     de la ligne 25 a 49 les fonction suivante nous retourne les informations de la location(Prix, date debut loc,le nb de jour que le vehicule est loué, le nom du client et le vehicule loué)
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    public function getVehicule(): Vehicule
    {
        return $this->vehicule;
    }

    public function getJours(): int
    {
        return $this->jours;
    }

    public function getMontantTTC(): float
    {
        return $this->montantTTC;
    }

    public function getDateDebut(): string
    {
        return $this->dateDebut;
    }

    // Ex : Jean Dupont - Renault Clio (AB-123-CD) - 3 jours - 151.20 €
    public function __toString(): string
    {
        return sprintf(
            '%s - %s %s (%s) - %d jours - %.2f €',
            $this->client->getNomComplet(),
            $this->vehicule->getMarque(),
            $this->vehicule->getModele(),
            $this->vehicule->getImmatriculation(),
            $this->jours,
            $this->montantTTC
        );
    }
}
