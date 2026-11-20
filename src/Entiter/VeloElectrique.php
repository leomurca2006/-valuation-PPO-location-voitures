<?php


declare(strict_types=1);

namespace App\Entiter;

use InvalidArgumentException;
final class VeloElectrique extends Vehicule
{
    public function __construct(
        string $immatriculation,
        string $marque,
        string $modele,
        float $tarifJournalier,
        private int $autonomie
    ) {
        parent::__construct($immatriculation, $marque, $modele, $tarifJournalier);
    }

    public function getAutonomie(): int
    {
        return $this->autonomie;
    }

    public function setAutonomie(int $autonomie): self
    {
        if ($autonomie < 0) {
            throw new InvalidArgumentException("L'autonomie ne peut pas être négative");
        }
        $this->autonomie = $autonomie;

        return $this;
    }

    public function calculerPrixHT(int $jours): float
    {
        return $this->tarifJournalier * $jours;
        
        if ($jours >= 7) {
            $prix *= 0.80;
        }

        return $prix;
    }

    public function getType(): string
    {
        return 'Vélo électrique';
    }
}