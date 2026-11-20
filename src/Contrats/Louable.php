<?php

interface Louable{
    public function louer(int $jours): float;
    public function rendre(): void;
    public function estDisponible(): bool;
}
