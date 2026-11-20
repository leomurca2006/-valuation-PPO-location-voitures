<?php

declare(strict_types=1);

namespace App\Contrats;

interface Entretenable
{
    public function ajouterKilometres(int $km): self;

    public function besoinEntretien(): bool;

    public function entretenir(): void;
}
