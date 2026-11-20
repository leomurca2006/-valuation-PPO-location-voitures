<?php

trait HistoriqueTrait {
    private array $historique = [];

    public function ajouterHistorique(string $message): void
    {
        date_default_timezone_set('Europe/Paris');
       
        $this->historique[] = '['. date('d/m/Y H:i') .'] '. get_class($this) .' : '. $message;
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