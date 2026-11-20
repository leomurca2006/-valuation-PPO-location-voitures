<?php
declare(strict_types=1);

namespace App\Traits;

trait HistoriqueTrait
{
    private array $historique = [];

    public function ajouterHistorique(string $message): void
    {
        date_default_timezone_set('Europe/Paris');

        // get_class() renvoie "App\Entiter\Voiture" : on ne garde que "Voiture"
        $classe = (new \ReflectionClass($this))->getShortName();
        $this->historique[] = '['. date('d/m/Y H:i') .'] '. $classe .' : '. $message;
    }

    
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