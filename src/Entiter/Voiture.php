<?php

declare(strict_types=1);

namespace App\Entiter;

use App\Contrats\Entretenable;


class Voiture extends Vehicule implements Entretenable
{
    public const SEUIL_ENTRETIEN = 15000;
    public const SUPPLEMENT_PLACES = 10;
//constructeur de la classe voiture qui hérite de la classe vehicule
    public function __construct(
        string $immatriculation,
        string $marque,
        string $modele,
        float $tarifJournalier,
        private int $nbPlaces,
        private int $kmDepuisEntretien = 0
    ) {
        parent::__construct($immatriculation, $marque, $modele, $tarifJournalier);
    }
//les fonctions de la ligne 26 a 64 renvoie et definisse le nombre de places dans le vehicule, le nombre de km depuis le dernier entretien, le prix HT, le type du vehicule, ajoute les km parcourus, verifie si l'entretien est nécessaire et effectue l'entretien
    public function getNbPlaces(): int
    {
        return $this->nbPlaces;
    }

    public function setNbPlaces(int $nbPlaces): self
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    public function getKmDepuisEntretien(): int
    {
        return $this->kmDepuisEntretien;
    }

    public function calculerPrixHT(int $jours): float
    {
        $prix = $this->tarifJournalier * $jours;

        if ($this->nbPlaces > 5) {
            $prix += self::SUPPLEMENT_PLACES * $jours;
        }

        return $prix;
    }

    public function getType(): string
    {
        return 'Voiture';
    }

    public function ajouterKilometres(int $km): self
    {
        $this->kmDepuisEntretien += $km;

        return $this;
    }

    public function besoinEntretien(): bool
    {
        return $this->kmDepuisEntretien >= self::SEUIL_ENTRETIEN;
    }

    public function entretenir(): void
    {
        $this->kmDepuisEntretien = 0;
        $this->ajouterHistorique("Entretien effectué");
    }
}
