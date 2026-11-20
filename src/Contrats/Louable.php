<?php

declare(strict_types=1);

namespace App\Contrats;

interface Louable
{
    public function louer(int $jours): float;

    public function rendre(): void;

    public function estDisponible(): bool;
}