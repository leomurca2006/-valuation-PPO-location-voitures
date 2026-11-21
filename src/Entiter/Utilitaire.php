<?php

declare(strict_types=1);

namespace App\Entiter;

use App\Contrats\Entretenable;
use InvalidArgumentException;

// Utilitaire : frais de nettoyage par location, entretien tous les 10 000 km
class Utilitaire extends Vehicule implements Entretenable
{
    public const SEUIL_ENTRETIEN = 10000;
    public const FRAIS_NETTOYAGE = 50; // € HT, facturés une seule fois par location

    // Constructeur : volume (en m³) et kmDepuisEntretien sont promus, le reste est envoyé au parent
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

    // Tarif x jours + frais de nettoyage
    public function calculerPrixHT(int $jours): float
    {
        return $this->tarifJournalier * $jours + self::FRAIS_NETTOYAGE;
    }

    public function getType(): string
    {
        return 'Utilitaire';
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

    // Entretien nécessaire à partir de 10 000 km
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

    // Reprend la carte du parent et ajoute le volume avant la fermeture du </div>
    public function __toString(): string
    {
        return str_replace('</div>', " - Volume : {$this->volume} m³</div>", parent::__toString());
    }
}
