<?php

declare(strict_types=1);

namespace App\Entiter;

use App\Contrats\Louable;
use App\Traits\HistoriqueTrait;
use InvalidArgumentException;

/**
 * Classe abstraite représentant un véhicule de l'agence.
 */
abstract class Vehicule implements Louable
{
    use HistoriqueTrait;

    public const TVA = 1.20; // 20 % de TVA

    // Compteur de véhicules créés, inaccessible depuis l'extérieur
    private static int $nbVehicules = 0;

    private string $immatriculation;
    private string $marque;
    private string $modele;
    protected float $tarifJournalier; // HT, accessible aux classes enfants
    private bool $disponible = true;

    // Constructeur : passe par les setters (qui valident les données) puis incrémente le compteur
    public function __construct(string $immatriculation, string $marque, string $modele, float $tarifJournalier)
    {
        $this->setImmatriculation($immatriculation)
             ->setMarque($marque)
             ->setModele($modele)
             ->setTarifJournalier($tarifJournalier);

        self::$nbVehicules++;
    }

    // Nombre total de véhicules créés
    public static function getNbVehicules(): int
    {
        return self::$nbVehicules;
    }

    public function getImmatriculation(): string
    {
        return $this->immatriculation;
    }

    // Format attendu : AB-123-CD
    public function setImmatriculation(string $immatriculation): self
    {
        if (!preg_match('/^[A-Z]{2}-\d{3}-[A-Z]{2}$/', $immatriculation)) {
            throw new InvalidArgumentException("L'immatriculation doit être au format XX-123-XX");
        }
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getMarque(): string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getTarifJournalier(): float
    {
        return $this->tarifJournalier;
    }

    // Refuse un tarif nul ou négatif
    public function setTarifJournalier(float $tarifJournalier): self
    {
        if ($tarifJournalier <= 0) {
            throw new InvalidArgumentException("Le tarif journalier doit être strictement supérieur à 0");
        }
        $this->tarifJournalier = $tarifJournalier;

        return $this;
    }

    public function estDisponible(): bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    // final : non redéfinissable. Renvoie le prix TTC, ou 0 si indisponible ou moins d'1 jour
    final public function louer(int $jours): float
    {
        if (!$this->disponible || $jours < 1) {
            return 0.0;
        }

        $prixHT = $this->calculerPrixHT($jours);
        $prixTTC = round($prixHT * self::TVA, 2);

        $this->disponible = false;
        $this->ajouterHistorique("Location pour {$jours} jour(s) - " . number_format($prixTTC, 2) . " € TTC");

        return $prixTTC;
    }

    // Remet le véhicule à disposition
    public function rendre(): void
    {
        $this->disponible = true;
        $this->ajouterHistorique("Véhicule rendu");
    }

    // À définir dans chaque type de véhicule
    abstract public function calculerPrixHT(int $jours): float;

    abstract public function getType(): string;

    // Carte HTML : type, marque, modèle, immatriculation, tarif TTC et disponibilité
    public function __toString(): string
    {
        $tarifTTC = round($this->tarifJournalier * self::TVA, 2);
        $statut = $this->disponible ? 'Disponible' : 'Indisponible';

        return sprintf(
            '<div class="vehicule-card"><strong>[%s] %s %s</strong> (%s) - Tarif : %.2f € TTC/jour - Statut : %s</div>',
            $this->getType(),
            $this->marque,
            $this->modele,
            $this->immatriculation,
            $tarifTTC,
            $statut
        );
    }
}