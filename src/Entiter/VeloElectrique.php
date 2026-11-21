<?php

declare(strict_types=1);

namespace App\Entiter;

use InvalidArgumentException;

// Vélo électrique : non entretenable, et final (aucune classe ne peut en hériter)
final class VeloElectrique extends Vehicule
{
    // Constructeur : autonomie (en km) est promue, le reste est envoyé au parent
    public function __construct(
        string $immatriculation,
        string $marque,
        string $modele,
        float $tarifJournalier,
        private int $autonomie
    ) {
        // La promotion contourne le setter : on valide l'autonomie avant que le parent compte le véhicule
        $this->setAutonomie($autonomie);
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

    // Tarif x jours, -20 % à partir de 7 jours
    public function calculerPrixHT(int $jours): float
    {
        $prix = $this->tarifJournalier * $jours;

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