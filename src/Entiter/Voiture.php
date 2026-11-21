<?php

declare(strict_types=1);

namespace App\Entiter;

use App\Contrats\Entretenable;
use InvalidArgumentException;

// Voiture : supplément au-delà de 5 places, entretien tous les 15 000 km
class Voiture extends Vehicule implements Entretenable
{
    public const SEUIL_ENTRETIEN = 15000;
    public const SUPPLEMENT_PLACES = 10; // € HT par jour au-delà de 5 places

    // Constructeur : nbPlaces et kmDepuisEntretien sont promus, le reste est envoyé au parent
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

    // Tarif x jours, + 10 € HT par jour si plus de 5 places
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

    // Ajoute les km parcourus depuis le dernier entretien
    public function ajouterKilometres(int $km): self
    {
        if ($km < 0) {
            throw new InvalidArgumentException("Le kilométrage ajouté ne peut pas être négatif");
        }
        $this->kmDepuisEntretien += $km;

        return $this;
    }

    // Entretien nécessaire à partir de 15 000 km
    public function besoinEntretien(): bool
    {
        return $this->kmDepuisEntretien >= self::SEUIL_ENTRETIEN;
    }

    // Remet le compteur à zéro et l'inscrit dans l'historique
    public function entretenir(): void
    {
        $this->kmDepuisEntretien = 0;
        $this->ajouterHistorique("Entretien effectué");
    }
}
