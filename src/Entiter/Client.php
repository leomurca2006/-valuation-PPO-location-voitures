<?php

declare(strict_types=1);

namespace App\Entiter;

use InvalidArgumentException;

class Client
{
    private string $nom;
    private string $prenom;
    private string $email;
    private ?string $telephone;
/*constructeur de client */
    public function __construct(string $nom, string $prenom, string $email, ?string $telephone = null)
    {
        $this->setNom($nom)
             ->setPrenom($prenom)
             ->setEmail($email)
             ->setTelephone($telephone);
    }
/*les fonctions de la ligne 24 a 78 renvoie et definisse le nom prenom, mail, numero de telephone*/
    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email invalide : {$email}");
        }
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNomComplet(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function __toString(): string
    {
        return "{$this->getNomComplet()} ({$this->email})";
    }
}
