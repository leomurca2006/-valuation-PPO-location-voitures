<?php


declare(strict_types=1);

namespace App\Entiter;

use InvalidArgumentException;
//classe VeloElectrique qui hérite de la classe Vehicule et représente un vélo électrique avec une autonomie spécifique
final class VeloElectrique extends Vehicule
{
// function constructeur de la classe VeloElectrique qui initialise les propriétés du vélo électrique
    public function __construct(
        string $immatriculation,
        string $marque,
        string $modele,
        float $tarifJournalier,
        private int $autonomie
    ) {
        parent::__construct($immatriculation, $marque, $modele, $tarifJournalier);
    }
//Les fonction de la ligne 23 à 75 renvoie et definisse l'autonomie, calculer le prix HT et retourne le type du vehicule
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