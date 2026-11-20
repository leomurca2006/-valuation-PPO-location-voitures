<?php

declare(strict_types=1);

namespace App\Entiter;

use App\Contrats\Entretenable;


class Utilitaire extends Vehicule implements Entretenable
{
    public const SEUIL_ENTRETIEN = 10000;
    public const FRAIS_NETTOYAGE = 50;

    public function __construct(
        string $immatriculation,
        string $marque,
        string $modele,
        float $tarifJournalier,
        private float $volume,
        private int $kmDepuisEntretien = 0
    ) {
        parent::__construct($immatriculation, $marque, $modele, $tarifJournalier);
    }

    public function getVolume(): float
    {
        return $this->volume;
    }

    public function setVolume(float $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getKmDepuisEntretien(): int
    {
        return $this->kmDepuisEntretien;
    }

    public function calculerPrixHT(int $jours): float
    {
        return $this->tarifJournalier * $jours + self::FRAIS_NETTOYAGE;
    }

    public function getType(): string
    {
        return 'Utilitaire';
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

    /**
     * Reprend la carte du parent et ajoute le volume avant la fermeture du </div>.
     */
    public function __toString(): string
    {
        return str_replace('</div>', " - Volume : {$this->volume} m³</div>", parent::__toString());
    }
}
