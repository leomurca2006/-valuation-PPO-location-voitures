<?php
declare(strict_types=1);

namespace App\Traits;

trait HistoriqueTrait
{
    private array $historique = []; // lignes de l'historique

    // Ajoute une ligne au format : [16/11/2026 14:30] Voiture : message
    public function ajouterHistorique(string $message): void
    {
        date_default_timezone_set('Europe/Paris');

        // Dans un trait, __CLASS__ vaut la classe qui l'utilise (App\Entiter\Vehicule) et non "Voiture" :
        // on prend donc le nom court de la vraie classe de l'objet
        $classe = (new \ReflectionClass($this))->getShortName();
        $this->historique[] = '['. date('d/m/Y H:i') .'] '. $classe .' : '. $message;
    }

    // Renvoie l'historique sous forme de liste HTML
    public function afficherHistorique(): string
    {
        $html = '<ul>';
        foreach($this->historique as $ligne){
            $html .= '<li>'. $ligne .'</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}